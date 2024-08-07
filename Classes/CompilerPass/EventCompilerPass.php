<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\CompilerPass;

use Smichaelsen\Noti\Service\EventRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EventCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $eventRegistryDefinition = $container->findDefinition(EventRegistry::class);
        foreach ($container->findTaggedServiceIds('noti.event') as $serviceName => $tags) {
            $eventRegistryDefinition->addMethodCall('addEvent', [$serviceName]);
        }
    }
}
