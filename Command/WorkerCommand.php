<?php
namespace Dmank\GearmanBundle\Command;

use dmank\gearman\ServerCollection;
use dmank\gearman\Worker;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WorkerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('gearman:worker:run')
            ->setAliases(['run:gearman'])
            ->setDefinition([
                new InputArgument('jobrepository', InputArgument::OPTIONAL, 'from which repository should the worker get his jobs?', 'default'),
                new InputArgument('server', InputArgument::OPTIONAL, 'should the worker get his jobs from a specific server?', 'default')
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $serverCollection = $this->getContainer()->get('gearman.server_collection');
        $jobRepository = $this->getContainer()->get('gearman.jobrepository');

        if ($input->getArgument('server') != 'default') {
            $connection = $this->getContainer()->get(sprintf('gearman.server.%s', $input->getArgument('server')));
            $serverCollection = new ServerCollection();
            $serverCollection->add($connection);
        }

        if ($input->getArgument('jobrepository') != 'default') {
            $jobRepository = $this->getContainer()->get(sprintf('gearman.jobrepository.%s', $input->getArgument('jobrepository')));
        }

        $worker = new Worker($serverCollection, $jobRepository, $this->getContainer()->get('gearman.event_dispatcher'));
        $worker->run();
    }


}
