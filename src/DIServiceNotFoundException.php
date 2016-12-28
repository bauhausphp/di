<?php

namespace Bauhaus\DI;

use Bauhaus\Container\ContainerItemNotFoundException;

class DIServiceNotFoundException extends ContainerItemNotFoundException
{
    protected function messageTemplate(): string
    {
        return 'No service with label \'%s\' was found in this dependency injection container';
    }
}
