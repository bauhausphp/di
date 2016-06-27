<?php

namespace Bauhaus\DI;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;

require_once __DIR__ . '/../bootstrap.php';

class DIClientContext implements Context, SnippetAcceptingContext
{
    private $di = null;
    private $outcome = null;

    /**
     * @Given an empty dependency injection container
     */
    public function aDependencyInjectionContainer()
    {
        $this->di = new DI();
    }

    /**
     * @When I register a service with the label :label using an anonymous function with the follow content:
     */
    public function iRegisterAServiceWithTheLabelUsingAnAnonymousFunctionWithTheFollowContent(
        $label,
        PyStringNode $anonymousFunctionContent
    ) {
        $anonymousFunction = create_function('', $anonymousFunctionContent->getRaw());

        $this->di->register($label, $anonymousFunction);
    }

    /**
     * @When I request the service :label
     */
    public function iRequestTheService($label)
    {
        try {
            $this->outcome = $this->di->$label;
        } catch (\Exception $e) {
            $this->outcome = $e;
        }
    }

    /**
     * @Then I should retrieve an instance of :class
     */
    public function iShouldRetrieveAnInstanceOf($class)
    {
        assertInstanceOf($class, $this->outcome);
    }

    /**
     * @Then I should retrieve the same object when I request the service :label again
     */
    public function iShouldRetrieveTheSameObjectWhenIRequestTheServiceAgain($label)
    {
        assertSame($this->outcome, $this->di->$label);
    }

    /**
     * @Then the exception :label is throwed with the message:
     */
    public function theExceptionIsThrowedWithTheMessage(
        $exceptionClass,
        PyStringNode $message
    ) {
        assertInstanceOf($exceptionClass, $this->outcome);
        assertEquals($message->getRaw(), $this->outcome->getMessage());
    }
}
