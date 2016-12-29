<?php

namespace Bauhaus;

use Bauhaus\Container;
use Bauhaus\Container\ItemNotFoundException;
use Bauhaus\DI\Service;
use Bauhaus\DI\ServiceNotFoundException;
use Bauhaus\DI\ServiceAlreadyExistsException;

class DI extends Container
{
    public function __construct(array $services = [])
    {
        foreach ($services as $name => $service) {
            if (!$service instanceof Service) {
                throw new \InvalidArgumentException(
                    "The service '$name' is not an instance of Bauhaus\DI\Service"
                );
            }
        }

        parent::__construct($services);
    }

    public function get($name)
    {
        try {
            $service = parent::get($name);
        } catch (ItemNotFoundException $e) {
            throw new ServiceNotFoundException($name);
        }

        return $service->value();
    }

    public function all(): array
    {
        $arr = [];
        foreach ($this->items() as $name => $service) {
            $arr[$name] = $service->value();
        }

        return $arr;
    }

    public function withService(string $name, callable $service, $type = Service::TYPE_SHARED): self
    {
        if ($this->has($name)) {
            throw new ServiceAlreadyExistsException($name);
        }

        $services = $this->items();
        $services[$name] = new Service($service, $type);

        return new self($services);
    }

    public function withSharedService(string $name, callable $service): self
    {
        return $this->withService($name, $service, Service::TYPE_SHARED);
    }

    public function withLazyService(string $name, callable $service): self
    {
        return $this->withService($name, $service, Service::TYPE_LAZY);
    }

    public function withNotSharedService(string $name, callable $service): self
    {
        return $this->withService($name, $service, Service::TYPE_NOT_SHARED);
    }

    private function items(): array
    {
        return parent::all();
    }
}
