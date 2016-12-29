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
        $di = (new DI())->withService('service', function () {
            return 'service result';
        });

        $newServiceName = 'newService';
        $newService = function () {
            return 'result of the new service';
        };

        // act
        $newDi = $di->withService($newServiceName, $newService);

        // assert
        $services = $di->items();
        $newServices = $newDi->items();

        $this->assertNotSame($di, $newDi); // new container returned
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
        $di = (new DI())->withService($serviceName, $service, $serviceType);

        // act
        $resultUsingGetMethod = $di->get($serviceName);
        $resultUsingMagicMethod = $di->$serviceName;

        // assert
        $this->assertEquals($expectedResult, $resultUsingGetMethod);
        $this->assertEquals($expectedResult, $resultUsingMagicMethod);
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

        $di = (new DI())
            ->withSharedService('service', function () {
                $class = new \StdClass();
                $class->during = microtime(true);

                return $class;
            });

        usleep(100);
        $after = microtime(true);

        // act
        usleep(100);
        $firstCall = $di->service;
        usleep(100);
        $secondCall = $di->service;

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

        $di = (new DI())
            ->withLazyService('service', function () {
                $class = new \StdClass();
                $class->during = microtime(true);

                return $class;
            });

        usleep(100);
        $after = microtime(true);

        // act
        usleep(100);
        $firstCall = $di->service;
        usleep(100);
        $secondCall = $di->service;

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
        $di = (new DI())
            ->withNotSharedService('service', function () {
                return new \StdClass();
            });

        $firstCall = $di->service;
        $secondCall = $di->service;
        $thirdCall = $di->service;

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

        $di = (new DI())
            ->withService('service1', function () {
                return 'value1';
            })
            ->withService('service2', function () {
                return 'value2';
            });

        $this->assertEquals($expectedResult, $di->toArray());
    }

    /**
     * @test
     * @expectedException Bauhaus\DI\ServiceNotFoundException
     * @expectedExceptionMessage No service found with name 'nonExisting'
     */
    public function exceptionOccursWhenTryingToRetrieveAServiceWithNonExistingLabel()
    {
        $di = new DI();

        $di->nonExisting;
    }

    /**
     * @test
     * @expectedException Bauhaus\DI\ServiceAlreadyExistsException
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
