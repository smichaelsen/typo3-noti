<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\Notifier;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class NotifierCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $notifierRegistryDefinition = $container->findDefinition(NotifierRegistry::class);
        if (!$notifierRegistryDefinition) {
            return;
        }
        foreach ($container->findTaggedServiceIds('noti.notifier') as $serviceName => $tags) {
            $label = $tags[0]['label'] ?? $serviceName;
            $notifierRegistryDefinition->addMethodCall('addNotifier', [$serviceName, $label]);
        }
    }
}
