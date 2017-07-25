<?php
namespace Dmank\GearmanBundle\Tests\DependencyInjection;

use Dmank\GearmanBundle\DependencyInjection\GearmanExtension;
use Symfony\Bundle\MonologBundle\DependencyInjection\MonologExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class GearmanExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testSingleServer()
    {
        $config = ['gearman' => ['servers' => ['default' => ['host' => '127.0.0.1', 'port' => 4730]]]];
        $containerBuilder = new ContainerBuilder();

        $loader = new GearmanExtension();
        $loader->load($config, $containerBuilder);

        $otherLoader = new MonologExtension();
        $otherLoader->load([], $containerBuilder);

        $containerBuilder->compile();

    }
}
