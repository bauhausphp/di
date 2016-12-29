<?php

namespace Bauhaus\DI;

class ServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The given type 'wrongType' is invalid to a DI service
     */
    public function exceptionOccursWhenAnInvalidTypeIsGivenForCreatingANewItem()
    {
        $serviceFunction = function () {
            return true;
        };
        $serviceType = 'wrongType';

        new Service($serviceFunction, $serviceType);
    }
}
