<?php

declare(strict_types=1);

use Smichaelsen\Noti\CompilerPass\EventCompilerPass;
use Smichaelsen\Noti\CompilerPass\NotifierCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container, ContainerBuilder $containerBuilder) {
    $containerBuilder->addCompilerPass(new EventCompilerPass());
    $containerBuilder->addCompilerPass(new NotifierCompilerPass());
};
