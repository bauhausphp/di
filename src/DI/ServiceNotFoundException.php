<?php

namespace Bauhaus\DI;

use Bauhaus\Container\ItemNotFoundException;

class ServiceNotFoundException extends ItemNotFoundException
{
    protected function message(): string
    {
        return "No service found with name '{$this->label()}'";
    }
}
