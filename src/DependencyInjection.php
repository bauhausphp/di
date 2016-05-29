<?php

namespace Bauhaus;

use Bauhaus\Container\RegistrableContainer;
use Bauhaus\Container\Exception\ContainerItemNotFoundException;
use Bauhaus\DependencyInjection\Exception\DependencyInjectionServiceNotFoundException;

class DependencyInjection extends RegistrableContainer
{
    public function register(string $serviceName, $service)
    {
        if (is_callable($service)) {
            $service = $service();
        }

        parent::register($serviceName, $service);
    }

    public function get(string $serviceName)
    {
        try {
            return parent::get($serviceName);
        } catch (ContainerItemNotFoundException $e) {
            throw new DependencyInjectionServiceNotFoundException($serviceName);
        }
    }
}
