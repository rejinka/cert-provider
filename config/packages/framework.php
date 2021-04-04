<?php
declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container): void {
    $container->extension('framework', [
        'php_errors' => [
            'log' => true,
        ],
    ]);
};
