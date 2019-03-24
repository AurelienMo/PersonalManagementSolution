@api
@api_authenticate

Feature: As an anonymous user, I need to be able to obtain token

  Background:
    Given I load following users:
    | firstname | lastname | username | password | email               | tokenActivation | status             |
    | John      | Doe      | johndoe  | 12345678 | johndoe@yopmail.com | AZERTYTOKEN     | enabled            |
    | Jane      | Doe      | janedoe  | 12345678 | janedoe@yopmail.com | AZERTYTOKEN     | locked             |
    | Foo       | Bar      | foobar   | 12345678 | foobar@yopmail.com  | AZERTYTOKEN     | pending_activation |

  Scenario: [Fail] Try to authenticate with invalid credentials
    When Send auth request with method "POST" request to "/api/login_check" with username "barfoo" and password "12345678"
    Then the response status code should be 401
    And the JSON node "code" should be equal to "401"
    And the JSON node "message" should be equal to "Identifiants invalides."

  Scenario: [Success] Successful authentication
    When Send auth request with method "POST" request to "/api/login_check" with username "johndoe" and password "12345678"
    Then the response status code should be 200
    And the JSON node "token" should exist
    And the JSON node "refresh_token" should exist
    And the JSON node "user.id" should exist
    And the JSON node "user.firstname" should be equal to "John"
    And the JSON node "user.lastname" should be equal to "Doe"
    And the JSON node "user.email" should be equal to "johndoe@yopmail.com"
    And the JSON node "user.username" should be equal to "johndoe"
    And the JSON node "user.roles" should have 1 element
