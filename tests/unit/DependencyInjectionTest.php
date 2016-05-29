<?php

namespace Bauhaus;

use Bauhaus\DependencyInjection\FakeService;

class DpendencyInjectionTest extends \PHPUnit_Framework_TestCase
{
    private $di = null;

    protected function setUp()
    {
        $this->di = new DependencyInjection();
    }

    /**
     * @test
     * @dataProvider callbacksAndItsResults
     */
    public function returnTheResultOfTheRegisteredCallback($expected, $callback)
    {
        $this->di->register('callbackService', $callback);

        $this->assertEquals($expected, $this->di->callbackService);
    }

    public function callbacksAndItsResults()
    {
        return [
            [0, function () { return 0; }],
            [1, function () { return 1; }],
            [1, function () { return 0 + 1; }],
            [2, function () { return 1 + 1; }],
            [3, function () { return 1 + 2; }],
            [5, function () { return 2 + 3; }],
            [8, function () { return 3 + 5; }],
            [13, function () { return 5 + 8; }],
            [21, function () { return 8 + 13; }],
            [34, function () { return 13 + 21; }],
            [55, function () { return 21 + 34; }],
            [89, function () { return 34 + 55; }],
            [144, function () { return 55 + 89; }],
            [233, function () { return 89 + 144; }],
            [377, function () { return 144 + 233; }],
            [610, function () { return 233 + 377; }],
            [987, function () { return 377 + 610; }],
        ];
    }

    /**
     * @test
     */
    public function returnSameInstaceOfClassWhenRequireTheSameRegisteredCallbackItem()
    {
        $this->di->register('service', function () {
            $service = new FakeService();
            $service->pubAttr2 = 345;

            return $service;
        });

        $expected = $this->di->service;

        $this->assertSame($expected, $this->di->service);
    }

    /**
     * @test
     * @expectedException Bauhaus\DependencyInjection\Exception\DependencyInjectionServiceNotFoundException
     */
    public function exceptionOccursWhenTryToRetrieveANonExistingItem()
    {
        $this->di->nonExistingItem;
    }
}
