<?php

namespace Bauhaus\DIContainer;

class Service
{
    private $type = null;
    private $valueNotEvaluated = null;
    private $valueWasEvaluated = false;
    private $valueEvaluated = null;

    public function __construct(callable $service, string $type)
    {
        if (self::isServiceTypeInvalid($type)) {
            throw new \InvalidArgumentException(
                "The given type '$type' is invalid to a DI container service"
            );
        }

        $this->type = $type;
        $this->valueNotEvaluated = $service;

        if ($this->isShared()) {
            $this->evaluateValue();
        }
    }

    public function value()
    {
        if ($this->isNotShared() || !$this->valueWasEvaluated) {
            return $this->evaluateValue();
        }

        return $this->valueEvaluated;
    }

    private function evaluateValue()
    {
        $valueEvalueted = call_user_func($this->valueNotEvaluated);
        $this->valueWasEvaluated = true;

        return $this->valueEvaluated = $valueEvalueted;
    }

    private function isShared(): bool
    {
        return ServiceType::SHARED == $this->type;
    }

    private function isNotShared(): bool
    {
        return ServiceType::NOT_SHARED == $this->type;
    }

    protected static function isServiceTypeInvalid(string $type): bool
    {
        $availableServiceTypes = [
            ServiceType::SHARED,
            ServiceType::LAZY,
            ServiceType::NOT_SHARED,
        ];

        return false === array_search($type, $availableServiceTypes);
    }
}
