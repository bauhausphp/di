<?php

namespace Bauhaus\DependencyInjection;

use Bauhaus\Container\ContainerItemException;

class DependencyInjectionServiceNotFoundException extends ContainerItemException
{
    protected function messageTemplate(): string
    {
        return 'No service with label \'%s\' was found in this dependency injection container';
    }
}
