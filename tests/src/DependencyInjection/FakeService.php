<?php

namespace Bauhaus\DependencyInjection;

class FakeService
{
    private $privAttr1 = 'someValue';
    private $privAttr2 = null;
    private $privAttr3 = null;
    public $pubAttr1 = 123;
    public $pubAttr2 = 'bla';
    public $pubAttr3 = null;

    public function __construct()
    {
        $this->privAttr2 = 'aeiou';
    }
}
