<?php
declare(strict_types = 1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\inline_service;

return function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services()->defaults()
        ->private()
        ->autowire()
        ->autoconfigure();

    $containerConfigurator->services()
        ->instanceof(\App\Infrastructure\Http\Action::class)
        ->tag('controller.service_arguments');

    $services
        ->load('App\\', '../src/*')
        ->exclude(
            [
                '../src/Domain/',
                '../src/Infrastructure/Symfony/Kernel.php',
            ]
        );

    $services->set(\App\Infrastructure\Persistence\AcmeJsonRepository::class)
        ->arg(
            '$reader',
            inline_service(\App\Infrastructure\AcmeJson\Reader\FilesystemReader::class)
                ->arg('$parser', inline_service(\App\Infrastructure\AcmeJson\Parser\JsonParser::class))
                ->arg('$path', '%app.traefik_acme_json_path%')
        );

    $services->alias(
        \App\Domain\CertificateEntryRepository::class,
        \App\Infrastructure\Persistence\AcmeJsonRepository::class,
    );

};
