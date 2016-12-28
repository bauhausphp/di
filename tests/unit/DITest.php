<?php

namespace Bauhaus\DI;

class DITest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function aNewContainerIsReturnedWhenANewServiceIsRegistered()
    {
        $oldDiContainer = (new DI())
            ->withService('service', function () {
                return 'result of the service';
            });

        $newDiContainer = $oldDiContainer->withService('newService', function () {
            return 'result of the new service';
        });

        // assert that new container was created
        $this->assertNotSame($oldDiContainer, $newDiContainer);

        // assert that the new container contains the new service registered and
        // the old ones
        $oldServices = $oldDiContainer->all();
        $newServices = $newDiContainer->all();

        $this->assertArrayHasKey('newService', $newServices);
        unset($newServices['newService']);
        $this->assertEquals($oldServices, $newServices);
    }

    /**
     * @test
     * @dataProvider availableServiceTypes
     */
    public function theResultOfTheAnonymousFunctionUsedToRegisterTheNewServiceIsReturnedWhenThisServiceIsCalled(
        string $serviceType
    ) {
        $expectedResult = 'expected result';

        $diContainer = (new DI())
            ->withService('service', function () use ($expectedResult) {
                return $expectedResult;
            }, $serviceType);

        $this->assertEquals($expectedResult, $diContainer->service);
    }

    public function availableServiceTypes()
    {
        foreach (DIItem::availableServiceTypes() as $serviceType) {
            yield [$serviceType];
        }
    }

    /**
     * @test
     */
    public function aSharedServiceEvaluatesItsAnonymousFunctionOnlyOnceAndWhenItIsRegistered()
    {
        $before = microtime(true);
        usleep(50);

        $diContainer = (new DI())
            ->withSharedService('service', function () {
                $class = new \StdClass();
                $class->during = microtime(true);

                return $class;
            });

        usleep(50);
        $after = microtime(true);

        $firstEvaluation = $diContainer->service;
        $secondEvaluation = $diContainer->service;
        $thirdEvaluation = $diContainer->service;

        $this->assertSame($firstEvaluation, $secondEvaluation);
        $this->assertSame($firstEvaluation, $thirdEvaluation);

        $this->assertGreaterThan($before, $firstEvaluation->during);
        $this->assertGreaterThan($firstEvaluation->during, $after);
    }

    /**
     * @test
     */
    public function aLazyServiceEvaluatesItsAnonymousFunctionOnlyOnceAndWhenItIsCalledTheFirstTime()
    {
        $before = microtime(true);
        usleep(50);

        $diContainer = (new DI())
            ->withLazyService('service', function () {
                $class = new \StdClass();
                $class->during = microtime(true);

                return $class;
            });

        usleep(50);
        $after = microtime(true);

        $firstEvaluation = $diContainer->service;
        $secondEvaluation = $diContainer->service;
        $thirdEvaluation = $diContainer->service;

        $this->assertSame($firstEvaluation, $secondEvaluation);
        $this->assertSame($firstEvaluation, $thirdEvaluation);

        $this->assertGreaterThan($before, $firstEvaluation->during);
        $this->assertGreaterThan($after, $firstEvaluation->during);
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

        $firstEvaluation = $diContainer->service;
        $secondEvaluation = $diContainer->service;
        $thirdEvaluation = $diContainer->service;

        $this->assertNotSame($firstEvaluation, $secondEvaluation);
        $this->assertNotSame($firstEvaluation, $thirdEvaluation);
        $this->assertNotSame($secondEvaluation, $thirdEvaluation);
    }

    /**
     * @test
     * @expectedException Bauhaus\DI\DIServiceNotFoundException
     * @expectedExceptionMessage No service with label 'nonExisting' was found in this dependency injection container
     */
    public function exceptionOccursWhenTryingToRetrieveAServiceWithNonExistingLabel()
    {
        $diContainer = new DI();

        $diContainer->nonExisting;
    }

    /**
     * @test
     * @expectedException Bauhaus\DI\DIServiceAlreadyExistsException
     * @expectedExceptionMessage There is already a service registered with the label 'alreadTaken' in this dependency injection container
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
     * @expectedExceptionMessage The item with label 'wrongItem' does not contain a DIItem
     */
    public function exceptionOccursWhenTryingToCreateUsingArrayThatContainsValueThat()
    {
        new DI([
            'wrongItem' => 'notDIItem',
        ]);
    }
}
