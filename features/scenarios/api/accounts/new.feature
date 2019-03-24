@api
@api_accounts

Feature: As an anonymous user, I need to be able to create an account

  Background:
    Given I load following users:
      | firstname | lastname | username | password | email               | tokenActivation | status             |
      | John      | Doe      | johndoe  | 12345678 | johndoe@yopmail.com | AZERTYTOKEN     | enabled            |
      | Jane      | Doe      | janedoe  | 12345678 | janedoe@yopmail.com | AZERTYTOKEN     | locked             |

  Scenario: [Fail] Try to submit request with auth user
    When After authentication on url "/api/login_check" with method "POST" as user "johndoe" with password "12345678", I send a "POST" request to "/api/accounts" with body:
    """
    {
        "firstname": "Foo",
        "lastname": "Bar",
        "username": "foobar",
        "email": "foobar@yopmail.com",
        "password": "12345678"
    }
    """
    Then the response status code should be 403
    And the JSON node "code" should be equal to "403"
    And the JSON node "message" should be equal to "Vous ne pouvez pas vous inscrire en étant connecté."

  Scenario: [Fail] Request with already user exist
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
    Then the response status code should be 400
    And the JSON should be equal to:
    """
    {
        "": [
            "Utilisateur déjà existant."
        ]
    }
    """
  Scenario: [Fail] Empty payload
    When I send a "POST" request to "/api/accounts" with body:
    """
    {
    }
    """
    Then the response status code should be 400
    And the JSON node "firstname" should exist
    And the JSON node "lastname" should exist
    And the JSON node "email" should exist
    And the JSON node "username" should exist
    And the JSON node "password" should exist

  Scenario: [Fail] Invalid password too short
    When I send a "POST" request to "/api/accounts" with body:
    """
    {
        "firstname": "Foo",
        "lastname": "Bar",
        "username": "foobar",
        "email": "foobar@yopmail.com",
        "password": "123456"
    }
    """
    Then the response status code should be 400
    And the JSON should be equal to:
    """
    {
        "password": [
            "Votre mot de passe doit contenir 8 caractères."
        ]
    }
    """

  Scenario: [Success] Successful create account
    When I send a "POST" request to "/api/accounts" with body:
    """
    {
        "firstname": "Foo",
        "lastname": "Bar",
        "username": "foobar",
        "email": "foobar@yopmail.com",
        "password": "12345678"
    }
    """
    Then the response status code should be 201
    And user with username "foobar" should exist into database
    And user with username "foobar" should have status "pending_activation"
    And 1 mails should have been sent
