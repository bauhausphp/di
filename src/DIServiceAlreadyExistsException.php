<?php

namespace Bauhaus\DI;

use Bauhaus\Container\ContainerItemException;

class DIServiceAlreadyExistsException extends ContainerItemException
{
    protected function messageTemplate(): string
    {
        return 'There is already a service registered with the label \'%s\' in this dependency injection container';
    }
}
