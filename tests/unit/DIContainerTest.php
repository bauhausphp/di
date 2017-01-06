<?php

namespace Bauhaus;

use Bauhaus\DIContainer\Service;
use Bauhaus\DIContainer\ServiceType;

class DIContainerTest extends \PHPUnit_Framework_TestCase
{
    private $diEmptyContainer = null;

    protected function setUp()
    {
        $this->diEmptyContainer = new DIContainer();
    }

    /**
     * @test
     */
    public function aNewContainerIsReturnedWhenANewServiceIsRegistered()
    {
        // arrange
        $diContainer = $this->diEmptyContainer
            ->withService('service', function () {
                return 'service result';
            });

        // act
        $newDiContainer = $diContainer
            ->withService('newService', function () {
                return 'service result';
            });

        // assert
        $services = $diContainer->items();
        $newServices = $newDiContainer->items();

        $this->assertNotSame($diContainer, $newDiContainer); // new container returned
        $this->assertTrue($newDiContainer->has('newService')); // new service was registed
        unset($newServices['newService']);
        $this->assertEquals($services, $newServices); // new di is equal to the old one with the new service added
    }

    /**
     * @test
     * @dataProvider availableServiceTypes
     */
    public function theResultOfTheAnonymousFunctionUsedToRegisterTheNewServiceIsReturnedWhenThisServiceIsCalled(
        string $serviceType
    ) {
        // arrange
        $diContainer = $this->diEmptyContainer
            ->withService('service', function () use ($serviceType) {
                return 'result';
            }, $serviceType);

        // act
        $resultOfGet = $diContainer->get('service');
        $resultOfMagicGet = $diContainer->service;

        // assert
        $this->assertEquals('result', $resultOfGet);
        $this->assertEquals('result', $resultOfMagicGet);
    }

    public function availableServiceTypes()
    {
        return [
            [ServiceType::SHARED],
            [ServiceType::LAZY],
            [ServiceType::NOT_SHARED],
        ];
    }

    /**
     * @test
     */
    public function aSharedServiceEvaluatesItsAnonymousFunctionOnlyOnceAndWhenItIsRegistered()
    {
        // arrange
        $before = microtime(true);
        usleep(100);

        $diContainer = $this->diEmptyContainer
            ->withSharedService('service', function () {
                $class = new \StdClass();
                $class->during = microtime(true);

                return $class;
            });

        usleep(100);
        $after = microtime(true);

        // act
        usleep(100);
        $firstCall = $diContainer->service;
        usleep(100);
        $secondCall = $diContainer->service;

        // assert
        $this->assertSame($firstCall, $secondCall);
        $this->assertTrue($before < $firstCall->during);
        $this->assertTrue($after > $firstCall->during);
    }

    /**
     * @test
     */
    public function aLazyServiceEvaluatesItsAnonymousFunctionOnlyOnceAndWhenItIsCalledTheFirstTime()
    {
        // arrange
        $before = microtime(true);
        usleep(100);

        $diContainer = $this->diEmptyContainer
            ->withLazyService('service', function () {
                $class = new \StdClass();
                $class->during = microtime(true);

                return $class;
            });

        usleep(100);
        $after = microtime(true);

        // act
        usleep(100);
        $firstCall = $diContainer->service;
        usleep(100);
        $secondCall = $diContainer->service;

        // assert
        $this->assertSame($firstCall, $secondCall);
        $this->assertTrue($before < $firstCall->during);
        $this->assertTrue($after < $firstCall->during);
    }

    /**
     * @test
     */
    public function aNotSharedServiceEvaluatesItsAnonymousFunctionEveryTimeItIsCalled()
    {
        $diContainer = $this->diEmptyContainer
            ->withNotSharedService('service', function () {
                return new \StdClass();
            });

        $firstCall = $diContainer->service;
        $secondCall = $diContainer->service;
        $thirdCall = $diContainer->service;

        $this->assertNotSame($firstCall, $secondCall);
        $this->assertNotSame($firstCall, $thirdCall);
        $this->assertNotSame($secondCall, $thirdCall);
    }

    /**
     * @test
     */
    public function retrieveAllServicesValuesWhenConvertingToArray()
    {
        $expectedResult = [
            'service1' => 'value1',
            'service2' => 'value2',
        ];

        $diContainer = $this->diEmptyContainer
            ->withService('service1', function () {
                return 'value1';
            })
            ->withService('service2', function () {
                return 'value2';
            });

        $this->assertEquals(
            [
                'service1' => 'value1',
                'service2' => 'value2',
            ],
            $diContainer->asArray()
        );
    }

    /**
     * @test
     * @expectedException Bauhaus\DIContainer\ServiceNotFoundException
     * @expectedExceptionMessage No service found with name 'nonExisting'
     */
    public function exceptionOccursWhenTryingToRetrieveAServiceWithNonExistingLabel()
    {
        $this->diEmptyContainer->nonExisting;
    }

    /**
     * @test
     * @expectedException Bauhaus\DIContainer\ServiceAlreadyRegisteredException
     * @expectedExceptionMessage There is already a service registered with the name 'alreadTaken'
     */
    public function exceptionOccursWhenTryingToRegisterAServiceWithAnAlreadyTakenLabel()
    {
        $this->diEmptyContainer
            ->withService('alreadTaken', function () {
                return 'result';
            })
            ->withService('alreadTaken', function () {
                return 'result';
            });
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The service 'wrongItem' is not an instance of Bauhaus\DI\Service
     */
    public function exceptionOccursWhenTryingToCreateUsingArrayThatContainsValueThat()
    {
        new DIContainer([
            'wrongItem' => 'notDIItem',
        ]);
    }
}
