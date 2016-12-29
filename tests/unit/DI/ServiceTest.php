<?php

namespace Bauhaus\DI;

class ServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The given type 'wrongType' is invalid for creating a new DIItem
     */
    public function exceptionOccursWhenAnInvalidTypeIsGivenForCreatingANewItem()
    {
        new Service(function () {
            return true;
        }, 'wrongType');
    }
}
