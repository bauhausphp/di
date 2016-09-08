<?php

namespace Bauhaus\DependencyInjection;

use Bauhaus\DependencyInjection\FakeService;

class DependencyInjectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @testdox When a service is registered, a new container is returned with the service added
     */
    public function whenAServiceIsRegisteredANewContainerIsReturnedWithTheServiceAdded()
    {
        $oldDi = (new DependencyInjection())
            ->withService('service', function () {
                return 'result of the service';
            });

        $newDi = $oldDi->withService('newService', function () {
            return 'result of the new service';
        });

        // assert that new container was created
        $this->assertNotSame($oldDi, $newDi);

        // assert that the new container contains the new service registered and
        // the old ones
        $servicesOfTheOldDi = $oldDi->all();
        $servicesOfTheNewDi = $newDi->all();

        $this->assertArrayHasKey('newService', $servicesOfTheNewDi);
        unset($servicesOfTheNewDi['newService']);
        $this->assertEquals($servicesOfTheOldDi, $servicesOfTheNewDi);
    }

    /**
     * @test
     * @dataProvider availableServiceTypes
     */
    public function theResultOfTheAnonymousFunctionUsedToRegisterAServiceIsReturnedWhenThisServiceIsCalled(
        string $serviceType
    ) {
        $expectedResult = 'expected result';

        $di = (new DependencyInjection())
            ->withService('service', function () use ($expectedResult) {
                return $expectedResult;
            }, $serviceType);

        $this->assertEquals($expectedResult, $di->service);
    }

    public function availableServiceTypes()
    {
        return [
            [DependencyInjectionItem::SHARED],
            [DependencyInjectionItem::LAZY],
            [DependencyInjectionItem::NOT_SHARED],
        ];
    }

    /**
     * @test
     */
    public function aSharedServiceEvaluatesItsAnonymousFunctionOnlyOnceAndWhenItIsRegistered()
    {
        $before = microtime(true);
        usleep(50);

        $di = (new DependencyInjection())
            ->withSharedService('service', function () {
                $class = new \StdClass();
                $class->during = microtime(true);

                return $class;
            });

        usleep(50);
        $after = microtime(true);

        $firstEvaluation = $di->service;
        $secondEvaluation = $di->service;
        $thirdEvaluation = $di->service;

        $this->assertSame($firstEvaluation, $secondEvaluation);
        $this->assertSame($firstEvaluation, $thirdEvaluation);

        $this->assertGreaterThan($before, $firstEvaluation->during);
        $this->assertGreaterThan($firstEvaluation->during, $after);
    }

    /**
     * @test
     */
    public function aLazyServiceEvaluatesItsAnonymousFunctionOnlyOnceAndWhenItIsCalled()
    {
        $before = microtime(true);
        usleep(50);

        $di = (new DependencyInjection())
            ->withLazyService('service', function () {
                $class = new \StdClass();
                $class->during = microtime(true);

                return $class;
            });

        usleep(50);
        $after = microtime(true);

        $firstEvaluation = $di->service;
        $secondEvaluation = $di->service;
        $thirdEvaluation = $di->service;

        $this->assertSame($firstEvaluation, $secondEvaluation);
        $this->assertSame($firstEvaluation, $thirdEvaluation);

        $this->assertGreaterThan($before, $firstEvaluation->during);
        $this->assertGreaterThan($after, $firstEvaluation->during);
    }

    /**
     * @test
     */
    public function aNotSharedServiceEvaluatesItsAnonymousFunctionAlwaysWhenItIsCalled()
    {
        $di = (new DependencyInjection())
            ->withNotSharedService('service', function () {
                return new \StdClass();
            });

        $firstEvaluation = $di->service;
        $secondEvaluation = $di->service;
        $thirdEvaluation = $di->service;

        $this->assertNotSame($firstEvaluation, $secondEvaluation);
        $this->assertNotSame($firstEvaluation, $thirdEvaluation);
        $this->assertNotSame($secondEvaluation, $thirdEvaluation);
    }

    /**
     * @test
     * @expectedException Bauhaus\DependencyInjection\DependencyInjectionServiceNotFoundException
     * @expectedExceptionMessage No service with label 'nonExisting' was found in this dependency injection container
     */
    public function exceptionOccursWhenTryingToRetrieveAServiceWithNonExistingLabel()
    {
        $di = new DependencyInjection();

        $di->nonExisting;
    }

    /**
     * @test
     * @expectedException Bauhaus\DependencyInjection\DependencyInjectionServiceAlreadyExistsException
     * @expectedExceptionMessage There is already a service registered with the label 'alreadTaken' in this dependency injection container
     */
    public function exceptionOccursWhenTryingToRegisterAServiceWithAnAlreadyTakenLabel()
    {
        $di = (new DependencyInjection())
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
     * @expectedExceptionMessage The item with label 'wrongItem' does not contain a DependencyInjectionItem
     */
    public function exceptionOccursWhenTryingToCreateUsingArrayThatContainsValueThat()
    {
        $di = new DependencyInjection([
            'wrongItem' => 'notDependencyInjectionItem',
        ]);
    }
}
