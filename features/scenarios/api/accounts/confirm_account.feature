@api
@api_accounts
@api_accounts_activate

Feature: As an anonymous user, I need to be able to activate my account
  Background:
    Given I load following users:
      | Foo       | Bar      | foobar   | 12345678 | foobar@yopmail.com  | AZERTYTOKEN     | pending_activation |

  Scenario: [Fail] Submit request with empty payload
    When I send a "POST" request to "/api/accounts/activate" with body:
    """
    {
    }
    """
    Then the response status code should be 400
    And the JSON node "token" should exist
    And the JSON node "email" should exist

  Scenario: [Fail] Submit request with invalid activation token
    When I send a "POST" request to "/api/accounts/activate" with body:
    """
    {
        "token": "AAAAAAA",
        "email": "foobar@yopmail.com"
    }
    """
    Then the response status code should be 400
    And the JSON should be equal to:
    """
    {
        "token": [
            "Clé d'activation invalide."
        ]
    }
    """

  Scenario: [Fail] Submit request with valid token but invalid related email
    When I send a "POST" request to "/api/accounts/activate" with body:
    """
    {
        "token": "AZERTYTOKEN",
        "email": "johndoe@yopmail.com"
    }
    """
    Then the response status code should be 400
    And the JSON should be equal to:
    """
    {
        "email": [
            "Email associé à la clé d'activation invalide."
        ]
    }
    """

  Scenario: [Success] Submit request with valid token and valid email
    When I send a "POST" request to "/api/accounts/activate" with body:
    """
    {
        "token": "AZERTYTOKEN",
        "email": "foobar@yopmail.com"
    }
    """
    Then the response status code should be 201
    And user with username "foobar" should have status "enabled"
    And the JSON node "token" should exist
    And the JSON node "refresh_token" should exist
    And the JSON node "id" should exist
    And the JSON node "user.firstname" should be equal to "Foo"
    And the JSON node "user.lastname" should be equal to "Bar"
    And the JSON node "user.username" should be equal to "foobar"
    And the JSON node "user.email" should be equal to "foobar@yopmail.com"
    And the JSON node "user.roles" should exist
