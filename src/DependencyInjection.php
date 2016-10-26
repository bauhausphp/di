<?php

namespace Bauhaus\DependencyInjection;

use Bauhaus\Container\Container;
use Bauhaus\Container\ContainerItemNotFoundException;

class DependencyInjection extends Container
{
    public function __construct(array $services = [])
    {
        foreach ($services as $label => $item) {
            if (!$item instanceof DependencyInjectionItem) {
                throw new \InvalidArgumentException(
                    "The item with label '$label' does not contain a DependencyInjectionItem"
                );
            }
        }

        parent::__construct($services);
    }

    public function get($label)
    {
        try {
            $item = parent::get($label);
        } catch (ContainerItemNotFoundException $e) {
            throw new DependencyInjectionServiceNotFoundException($label);
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

    public function withService(string $label, callable $service, $type = DependencyInjectionItem::SHARED): self
    {
        if ($this->has($label)) {
            throw new DependencyInjectionServiceAlreadyExistsException($label);
        }

        $services = $this->items();
        $services[$label] = new DependencyInjectionItem($service, $type);

        return new self($services);
    }

    public function withSharedService(string $label, callable $service): self
    {
        return $this->withService($label, $service, DependencyInjectionItem::SHARED);
    }

    public function withLazyService(string $label, callable $service): self
    {
        return $this->withService($label, $service, DependencyInjectionItem::LAZY);
    }

    public function withNotSharedService(string $label, callable $service): self
    {
        return $this->withService($label, $service, DependencyInjectionItem::NOT_SHARED);
    }

    private function items(): array
    {
        return parent::all();
    }
}
