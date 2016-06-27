Feature: Dependency Injection Container
   In order to load services, make them accessible and immutable
   As a dependency injection client
   I shorld be able to register and retrieve registered services

   Background:
      Given an empty dependency injection container
      And I register a service with the label "fakeService" using an anonymous function with the follow content:
      """
      return new Bauhaus\DI\FakeService();
      """

   Scenario: Retrieving registered service
      When I request the service "fakeService"
      Then I should retrieve an instance of "Bauhaus\DI\FakeService"

   Scenario: Retrieving always the same object of registered service
      When I request the service "fakeService"
      Then I should retrieve the same object when I request the service "fakeService" again

   Scenario: Trying to retrieve a service with a non existing label
      When I request the service "wrong"
      Then the exception "Bauhaus\DI\Exception\DIServiceNotFound" is throwed with the message:
      """
      No service with label 'wrong' was found in this dependency injection container
      """
