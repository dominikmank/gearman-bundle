<?php

namespace Dmank\GearmanBundle;

use Dmank\GearmanBundle\DependencyInjection\Compiler\GearmanJobPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class GearmanBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new GearmanJobPass());
    }
}
