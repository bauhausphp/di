<?php

namespace Bauhaus\DI;

class DIItem
{
    const SHARED = 'shared';
    const LAZY = 'lazy';
    const NOT_SHARED = 'not shared';

    private $type = null;
    private $valueNotEvaluated = null;
    private $valueWasEvaluated = false;
    private $valueEvaluated = null;

    public function __construct(callable $service, string $type)
    {
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
        return self::SHARED == $this->type;
    }

    private function isNotShared(): bool
    {
        return self::NOT_SHARED == $this->type;
    }
}
