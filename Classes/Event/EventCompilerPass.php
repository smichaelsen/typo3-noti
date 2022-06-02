<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\Event;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EventCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $eventRegistryDefinition = $container->findDefinition(EventRegistry::class);
        if (!$eventRegistryDefinition) {
            return;
        }
        foreach ($container->findTaggedServiceIds('noti.event') as $serviceName => $tags) {
            $variants = $serviceName::getAllPossibleVariants();
            foreach ($variants as $variantName => $eventLabel) {
                $eventKey = $serviceName . '\\' . $variantName;
                $eventRegistryDefinition->addMethodCall('addEvent', [$eventKey, $eventLabel]);
            }
        }
    }
}
