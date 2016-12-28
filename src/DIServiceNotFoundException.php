<?php

namespace Bauhaus\DI;

use Bauhaus\Container\ContainerItemException;

class DIServiceNotFoundException extends ContainerItemException
{
    protected function messageTemplate(): string
    {
        return 'No service with label \'%s\' was found in this dependency injection container';
    }
}
