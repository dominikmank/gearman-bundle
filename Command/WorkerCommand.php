<?php
namespace Dmank\GearmanBundle\Command;

use dmank\gearman\event\subscriber\MaxRuntime;
use dmank\gearman\event\subscriber\MemoryLimit;
use dmank\gearman\ServerCollection;
use dmank\gearman\Worker;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class WorkerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('gearman:worker:run')
            ->setAliases(['run:gearman'])
            ->setDefinition([
                new InputOption('jobrepository', 'r',InputOption::VALUE_OPTIONAL, 'from which repository should the worker get his jobs?', 'default'),
                new InputOption('server', 's', InputOption::VALUE_OPTIONAL, 'should the worker get his jobs from a specific server?', 'default'),
                new InputOption('memory_limit', 'ml', InputOption::VALUE_OPTIONAL, 'how much memory can the worker use before restarting?', ini_get('memory_limit')),
                new InputOption('time_limit','tl',  InputOption::VALUE_OPTIONAL, 'how long should the worker run before restarting?', '+24 hours'),
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $serverCollection = $this->getContainer()->get('gearman.server_collection');
        $jobRepository = $this->getContainer()->get('gearman.jobrepository');

        if ($input->getOption('server') != 'default') {
            $connection = $this->getContainer()->get(sprintf('gearman.server.%s', $input->getOption('server')));
            $serverCollection = new ServerCollection();
            $serverCollection->add($connection);
        }

        if ($input->getOption('jobrepository') != 'default') {
            $jobRepository = $this->getContainer()->get(sprintf('gearman.jobrepository.%s', $input->getOption('jobrepository')));
        }

        $this->getContainer()->get('gearman.event_dispatcher')
            ->addSubscriber(new MemoryLimit($input->getOption('memory_limit'), $this->getContainer()->get('monolog.logger.gearman')));
        $this->getContainer()->get('gearman.event_dispatcher')
            ->addSubscriber(new MaxRuntime($input->getOption('time_limit'), $this->getContainer()->get('monolog.logger.gearman')));

        $worker = new Worker($serverCollection, $jobRepository, $this->getContainer()->get('gearman.event_dispatcher'));
        $worker->run();
    }
}
