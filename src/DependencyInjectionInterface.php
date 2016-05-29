<?php

namespace Bauhaus;

interface DependencyInjectionInterface
{
    public function register(string $serviceName, $service);
    public function get(string $serviceName);
    public function __get(string $serviceName);
}
