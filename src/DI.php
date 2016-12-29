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
        foreach ($services as $label => $item) {
            if (!$item instanceof Service) {
                throw new \InvalidArgumentException(
                    "The item with label '$label' does not contain a DIItem"
                );
            }
        }

        parent::__construct($services);
    }

    public function get($label)
    {
        try {
            $item = parent::get($label);
        } catch (ItemNotFoundException $e) {
            throw new ServiceNotFoundException($label);
        }

        return $item->value();
    }

    public function all(): array
    {
        $arr = [];
        foreach ($this->items() as $label => $item) {
            $arr[$label] = $item->value();
        }

        return $arr;
    }

    public function withService(string $label, callable $service, $type = Service::TYPE_SHARED): self
    {
        if ($this->has($label)) {
            throw new ServiceAlreadyExistsException($label);
        }

        $services = $this->items();
        $services[$label] = new Service($service, $type);

        return new self($services);
    }

    public function withSharedService(string $label, callable $service): self
    {
        return $this->withService($label, $service, Service::TYPE_SHARED);
    }

    public function withLazyService(string $label, callable $service): self
    {
        return $this->withService($label, $service, Service::TYPE_LAZY);
    }

    public function withNotSharedService(string $label, callable $service): self
    {
        return $this->withService($label, $service, Service::TYPE_NOT_SHARED);
    }

    private function items(): array
    {
        return parent::all();
    }
}
