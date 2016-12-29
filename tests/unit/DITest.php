<?php

namespace Bauhaus;

use Bauhaus\DI\Service;
use Bauhaus\DI\ServiceType;

class DITest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function aNewContainerIsReturnedWhenANewServiceIsRegistered()
    {
        // arrange
        $diContainer = (new DI())->withService('service', function () {
            return 'service result';
        });

        $newServiceName = 'newService';
        $newService = function () {
            return 'result of the new service';
        };

        // act
        $newDi = $diContainer->withService($newServiceName, $newService);

        // assert
        $services = $diContainer->items();
        $newServices = $newDiContainer->items();

        $this->assertNotSame($diContainer, $newDiContainer); // new container returned
        $this->assertTrue($newDi->has($newServiceName)); // new service was registed
        unset($newServices[$newServiceName]);
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
        $serviceName = 'service';
        $expectedResult = "result: $serviceType";
        $service = function () use ($expectedResult) {
            return $expectedResult;
        };
        $diContainer = (new DI())->withService($serviceName, $service, $serviceType);

        // act
        $resultOfGet = $diContainer->get($serviceName);
        $resultOfMagicGet = $diContainer->$serviceName;

        // assert
        $this->assertEquals($expectedResult, $resultOfGet);
        $this->assertEquals($expectedResult, $resultOfMagicGet);
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

        $diContainer = (new DI())
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

        $diContainer = (new DI())
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
        $diContainer = (new DI())
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

        $diContainer = (new DI())
            ->withService('service1', function () {
                return 'value1';
            })
            ->withService('service2', function () {
                return 'value2';
            });

        $this->assertEquals($expectedResult, $diContainer->toArray());
    }

    /**
     * @test
     * @expectedException Bauhaus\DI\ServiceNotFoundException
     * @expectedExceptionMessage No service found with name 'nonExisting'
     */
    public function exceptionOccursWhenTryingToRetrieveAServiceWithNonExistingLabel()
    {
        $diContainer = new DI();

        $diContainer->nonExisting;
    }

    /**
     * @test
     * @expectedException Bauhaus\DI\ServiceAlreadyRegisteredException
     * @expectedExceptionMessage There is already a service registered with the name 'alreadTaken'
     */
    public function exceptionOccursWhenTryingToRegisterAServiceWithAnAlreadyTakenLabel()
    {
        (new DI())
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
        new DI([
            'wrongItem' => 'notDIItem',
        ]);
    }
}
