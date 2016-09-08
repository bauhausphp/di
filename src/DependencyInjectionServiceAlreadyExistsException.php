<?php

namespace Bauhaus\DependencyInjection;

use Bauhaus\Container\ContainerItemException;

class DependencyInjectionServiceAlreadyExistsException extends ContainerItemException
{
    protected function messageTemplate(): string
    {
        return 'There is already a service registered with the label \'%s\' in this dependency injection container';
    }
}
