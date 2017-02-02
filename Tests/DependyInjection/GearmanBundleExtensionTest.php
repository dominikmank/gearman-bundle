<?php
namespace Dmank\GearmanBundle\Tests\DependencyInjection;

use Dmank\GearmanBundle\DependencyInjection\GearmanBundleExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class GearmanBundleExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testSingleServer()
    {
        $config = ['gearman' => ['servers' => ['default' => ['host' => '127.0.0.1', 'port' => 4730]]]];
        $containerBuilder = new ContainerBuilder();

        $loader = new GearmanBundleExtension();
        $loader->load($config, $containerBuilder);

        $containerBuilder->compile();
    }
}
