<?php

namespace Bauhaus;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

require_once __DIR__ . '/../bootstrap.php';

/**
 * Defines application features from the specific context.
 */
class DependencyInjectionUserContext implements Context, SnippetAcceptingContext
{
    private $di = null;
    private $outcome = null;

    /**
     * @Given an empty dependency injection container
     */
    public function anEmptyDependencyInjectionContainer()
    {
        $this->di = new DependencyInjection();
    }

    /**
     * @When I register the item :serviceName with a callback that returns :className
     */
    public function iRegisterTheItemWithACallbackThatReturns($serviceName, $className)
    {
        $this->di->register($serviceName, function () use ($className) {
            return new $className();
        });
    }

    /**
     * @When I retrieve and store the item :serviceName somewhere in the code
     */
    public function iRetrieveAndStoreTheItemSomewhereInTheCode($serviceName)
    {
        $this->outcome = $this->di->$serviceName;
    }

    /**
     * @Then I should retrieve an instance of :className when request the item :itemName
     */
    public function iShouldRetrieveAnInstanceOfWhenRequestTheItem($className, $itemName)
    {
        assertInstanceOf($className, $this->di->$itemName);
    }

    /**
     * @Then I should receive the same intance when retrieve the item :serviceName again
     */
    public function iShouldReceiveTheSameIntanceWhenRetrieveTheItemAgain($serviceName)
    {
        assertSame($this->outcome, $this->di->$serviceName);
    }
}
