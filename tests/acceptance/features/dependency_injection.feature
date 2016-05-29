Feature: Dependency Injection container
   In order to register, load and access dependencies services
   As a dependency injection user
   I should be able to retrieve loaded services I registered

   Background:
      Given an empty dependency injection container

   Scenario Outline: Retrieving result of registered callback item
      When I register the item "<name>" with a callback that returns "<className>"
      Then I should retrieve an instance of "<className>" when request the item "<name>"

      Examples:
         | name    | className                               |
         | service | Bauhaus\DependencyInjection\FakeService |

   Scenario Outline: Retrieving always the same instance of the registered item callback result
      When I register the item "<name>" with a callback that returns "<className>"
      And I retrieve and store the item "<name>" somewhere in the code
      Then I should receive the same intance when retrieve the item "<name>" again

      Examples:
         | name    | className                               |
         | service | Bauhaus\DependencyInjection\FakeService |

