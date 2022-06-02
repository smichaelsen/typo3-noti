<?php

declare(strict_types=1);

use Smichaelsen\Noti\Event\EventCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container, ContainerBuilder $containerBuilder) {
    $containerBuilder->addCompilerPass(new EventCompilerPass());
};
