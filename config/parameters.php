<?php

use Symfony\Component\DependencyInjection\Loader\Configurator;

return function (Configurator\ContainerConfigurator $containerConfigurator) {
    $containerConfigurator->parameters()
        ->set('app.traefik_acme_json_path', '%env(resolve:TRAEFIK_ACME_JSON_PATH)%')
    ;
};
