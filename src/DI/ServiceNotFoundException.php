<?php

namespace Bauhaus\DI;

use Bauhaus\Container\ItemNotFoundException;

class ServiceNotFoundException extends ItemNotFoundException
{
    protected function message(): string
    {
        return "No service with label '{$this->label()}' was found in this dependency injection container";
    }
}
