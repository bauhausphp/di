<?php

namespace Bauhaus\DI;

use Bauhaus\Container\ItemException;

class ServiceAlreadyRegisteredException extends ItemException
{
    protected function message(): string
    {
        return "There is already a service registered with the name '{$this->label()}'";
    }
}
