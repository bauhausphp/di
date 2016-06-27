<?php

namespace Bauhaus\DI;

interface DIInterface
{
    public function __get(string $label);
    public function register(string $label, callable $service);
}
