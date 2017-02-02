<?php
namespace Dmank\GearmanBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class GearmanJobPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('gearman.repository.default')) {
            return;
        }

        $definition = $container->findDefinition('gearman.repository.default');

        $jobs = $container->findTaggedServiceIds('gearman.job');

        foreach ($jobs as $id => $tags) {
            $definition->addMethodCall('addJob', array(new Reference($id)));
        }
    }
}
