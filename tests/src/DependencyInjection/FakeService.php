<?php

namespace Bauhaus\DependencyInjection;

class FakeService
{
    private $privAttr1 = null;
    public $pubAttr1 = 123;
    public $pubAttr2 = 'bla';
    public $pubAttr3 = null;

    public function __construct()
    {
        $this->privAttr1 = 'aeiou';
    }
}
