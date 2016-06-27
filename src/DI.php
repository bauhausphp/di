<?php

namespace Bauhaus\DI;

use Bauhaus\Container\ReadableContainer;
use Bauhaus\Container\Exception\ContainerItemNotFound;
use Bauhaus\DI\Exception\DIServiceNotFound;

class DI extends ReadableContainer implements DIInterface
{
    public function register(string $label, callable $service)
    {
        parent::_register($label, $service());
    }

    public function __get(string $label)
    {
        try {
            return parent::__get($label);
        } catch (ContainerItemNotFound $e) {
            throw new DIServiceNotFound($label);
        }
    }
}
