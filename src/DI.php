<?php

namespace Bauhaus;

use Bauhaus\Container;
use Bauhaus\Container\Factory;
use Bauhaus\Container\ItemAlreadyExistsException;
use Bauhaus\DI\Service;
use Bauhaus\DI\ServiceType;
use Bauhaus\DI\ServiceNotFoundException;
use Bauhaus\DI\ServiceAlreadyRegisteredException;

class DI extends Container
{
    public function get($name)
    {
        $service = parent::get($name);

        return $service->value();
    }

    public function asArray(): array
    {
        $arr = [];
        foreach ($this->items() as $name => $service) {
            $arr[$name] = $service->value();
        }

        return $arr;
    }

    public function withService(string $name, callable $callable, $type = ServiceType::SHARED): self
    {
        $factory = new Factory($this);
        $service = new Service($callable, $type);

        try {
            return $factory->containerWithItemAdded($name, $service);
        } catch (ItemAlreadyExistsException $e) {
            throw new ServiceAlreadyRegisteredException($name);
        }
    }

    public function withSharedService(string $name, callable $service): self
    {
        return $this->withService($name, $service, ServiceType::SHARED);
    }

    public function withLazyService(string $name, callable $service): self
    {
        return $this->withService($name, $service, ServiceType::LAZY);
    }

    public function withNotSharedService(string $name, callable $service): self
    {
        return $this->withService($name, $service, ServiceType::NOT_SHARED);
    }

    protected function canContain($service): bool
    {
        return $service instanceof Service;
    }

    protected function itemCanNotBeContainedExceptionMessage(string $name): string
    {
        return "The service '$name' is not an instance of Bauhaus\DI\Service";
    }

    protected function itemNotFoundHandler(string $name)
    {
        throw new ServiceNotFoundException($name);
    }
}
