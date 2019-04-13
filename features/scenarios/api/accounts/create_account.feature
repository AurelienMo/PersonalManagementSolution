@api
@api_account
@api_account_create

Feature: As an anonymous user, I need to be able to submit request to create an account

  Background:
    Given I load following users:
      | firstname | lastname | username | password | email               | tokenActivation | status             |
      | John      | Doe      | johndoe  | 12345678 | johndoe@yopmail.com | AZERTYTOKEN     | enabled            |

  Scenario: [Fail] Submit request with empty payload
    When I send a "POST" request to "/api/accounts" with body:
    """
    {
    }
    """
    Then the response status code should be 400
    And the JSON node "firstname" should exist
    And the JSON node "lastname" should exist
    And the JSON node "username" should exist
    And the JSON node "email" should exist
    And the JSON node "password" should exist

  Scenario: [Fail] Submit request with auth user
    When After authentication on url "/api/login_check" with method "POST" as user "johndoe" with password "12345678", I send a "POST" request to "/api/accounts" with body:
    """
    {
        "firstname": "Jane",
        "lastname": "Doe",
        "username": "janedoe",
        "email": "janedoe@yopmail.com",
        "password": "12345678"
    }
    """
    Then the response status code should be 403
    And the JSON node message should be equal to "Vous ne pouvez pas vous inscrire en étant connecté."

  Scenario: [Fail] Submit request with already exist user
    When I send a "POST" request to "/api/accounts" with body:
    """
    {
        "firstname": "John",
        "lastname": "Doe",
        "username": "johndoe",
        "email": "janedoe@yopmail.com",
        "password": "12345678"
    }
    """
    Then the response status code should be 400

  Scenario: [Fail] Submit request with already email exist
    When I send a "POST" request to "/api/accounts" with body:
    """
    {
        "firstname": "John",
        "lastname": "Doe",
        "username": "janedoe",
        "email": "johndoe@yopmail.com",
        "password": "12345678"
    }
    """
    Then the response status code should be 400

  Scenario: [Success] Submit valid request and create an account
    When I send a "POST" request to "/api/accounts" with body:
    """
    {
        "firstname": "Jane",
        "lastname": "Doe",
        "username": "janedoe",
        "email": "janedoe@yopmail.com",
        "password": "12345678"
    }
    """
    Then the response status code should be 201
    And 1 mail should have been sent
    And user with username "janedoe" should exist into database
    And user with username "janedoe" should have status "enabled"
