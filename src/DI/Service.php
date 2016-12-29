<?php

namespace Bauhaus\DI;

class Service
{
    const TYPE_SHARED = 'shared';
    const TYPE_LAZY = 'lazy';
    const TYPE_NOT_SHARED = 'not shared';

    private $type = null;
    private $valueNotEvaluated = null;
    private $valueWasEvaluated = false;
    private $valueEvaluated = null;

    public function __construct(callable $service, string $type)
    {
        if (self::isServiceTypeInvalid($type)) {
            throw new \InvalidArgumentException(
                "The given type '$type' is invalid for creating a new DIItem"
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
        return self::TYPE_SHARED == $this->type;
    }

    private function isNotShared(): bool
    {
        return self::TYPE_NOT_SHARED == $this->type;
    }

    protected static function isServiceTypeInvalid(string $type): bool
    {
        $availableServiceTypes = [
            self::TYPE_SHARED,
            self::TYPE_LAZY,
            self::TYPE_NOT_SHARED,
        ];

        return false === array_search($type, $availableServiceTypes);
    }
}
