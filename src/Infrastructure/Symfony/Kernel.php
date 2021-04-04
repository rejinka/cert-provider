<?php

namespace App\Infrastructure\Symfony;

use App\Infrastructure\Http\Action;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

final class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        yield new \Symfony\Bundle\FrameworkBundle\FrameworkBundle();
    }

    public function getCacheDir()
    {
        if ('test' === $this->environment) {
            return sys_get_temp_dir() . '/var/cache/test';
        }

        return parent::getCacheDir();
    }

    public function getLogDir()
    {
        if ('test' === $this->environment) {
            return sys_get_temp_dir() . '/var/log';
        }

        return parent::getLogDir();
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $confDir = $this->getProjectDir() . '/config';

        $container->import($confDir . '/parameters.php', 'php');
        $container->import($confDir . '/{packages}/*.php', 'glob');
        $container->import($confDir . '/{packages}/' . $this->environment . '/*.php', 'glob');
        $container->import($confDir . '/{services}.php', 'glob');
        $container->import($confDir . '/{services}_' . $this->environment . '.php', 'glob');
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $confDir = $this->getProjectDir() . '/config';

        $routes->import($confDir . '/{routes}/' . $this->environment . '/*.php', 'glob');
        $routes->import($confDir . '/{routes}/*.php', 'glob');
        $routes->import($confDir . '/{routes}.php', 'glob');
    }

}
