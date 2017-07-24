<?php

namespace Dmank\GearmanBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class GearmanJobPass implements CompilerPassInterface
{
    private $repository;
    private $tagName;

    public function __construct($tagName = 'gearman.job', $repository = 'default')
    {
        $this->tagName = $tagName;
        $this->repository = $repository;
    }

    public function process(ContainerBuilder $container)
    {
        $repositoryDefinitionName = sprintf('gearman.jobrepository.%s', $this->repository);

        if (!$container->has($repositoryDefinitionName)) {
            return;
        }

        $definition = $container->findDefinition($repositoryDefinitionName);

        $jobs = $container->findTaggedServiceIds($this->tagName);

        foreach ($jobs as $id => $tags) {
            $definition->addMethodCall('add', array(new Reference($id)));
        }
    }
}
