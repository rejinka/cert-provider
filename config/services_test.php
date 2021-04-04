<?php
declare(strict_types = 1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services()->defaults()
        ->private()
        ->autowire()
        ->autoconfigure();

    $services->set('logger', \Psr\Log\NullLogger::class);
};
