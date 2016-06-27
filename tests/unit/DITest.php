<?php

namespace Bauhaus\DI;

use Bauhaus\DI\FakeService;

class DITest extends \PHPUnit_Framework_TestCase
{
    private $di = null;

    protected function setUp()
    {
        $this->di = new DI();
    }

    /**
     * @test
     * @dataProvider callbacksAndItsResults
     */
    public function returnTheResultOfTheRegisteredAnonynousFunction($expected, $callback)
    {
        $this->di->register('service', $callback);

        $this->assertEquals($expected, $this->di->service);
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
        $this->di->register('fakeService', function () {
            return new FakeService();
        });

        $this->assertSame($this->di->fakeService, $this->di->fakeService);
    }

    /**
     * @test
     * @expectedException Bauhaus\DI\Exception\DIServiceNotFound
     * @expectedExceptionMessage No service with label 'wrong' found in this dependency injection container
     */
    public function exceptionOccursWhenTryToRetrieveAServiceWithNonExistingLabel()
    {
        $this->di->wrong;
    }
}
