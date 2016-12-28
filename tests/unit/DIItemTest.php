<?php

namespace Bauhaus\DI;

class DIItemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The given type 'wrongType' is invalid for creating a new DIItem
     */
    public function exceptionOccursWhenAnInvalidTypeIsGivenForCreatingANewItem()
    {
        new DIItem(function () {
            return true;
        }, 'wrongType');
    }
}
