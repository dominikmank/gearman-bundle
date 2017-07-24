<?php
namespace Dmank\GearmanBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class GearmanExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');


        if (isset($config['servers'])) {
            foreach ($config['servers'] as $serverName => $server) {
                $definitionName = sprintf('gearman.server.%s', $serverName);
                $container->setDefinition($definitionName, new DefinitionDecorator('gearman.server.abstract'))
                    ->setArguments([
                        $server['host'],
                        $server['port']
                    ]);
                $definition = $container->getDefinition('gearman.server_collection');
                $definition->addMethodCall('add', [new Reference($definitionName)]);
            }
        }
    }
}
