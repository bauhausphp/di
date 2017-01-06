<?php

namespace Bauhaus\DIContainer;

use Bauhaus\Container\ItemException;

class ServiceAlreadyRegisteredException extends ItemException
{
    protected function message(): string
    {
        return "There is already a service registered with the name '{$this->label()}'";
    }
}
