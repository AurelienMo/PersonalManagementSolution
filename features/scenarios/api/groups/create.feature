@api
@api_group
@api_group_create

Feature: As an auth user, I need to be able to create a group

  Background:
    Given I load following users:
      | firstname | lastname | username | password | email               | tokenActivation | status             |
      | John      | Doe      | johndoe  | 12345678 | johndoe@yopmail.com | AZERTYTOKEN     | enabled            |
      | Jane      | Doe      | janedoe  | 12345678 | janedoe@yopmail.com | AZERTYTOKEN     | enabled            |
    And I load following group:
      | name | passwordToJoin | owner   |
      | Jane | 12345678       | janedoe |

  Scenario: [Fail] Submit request with anonymous user
    When I send a "POST" request to "/api/groups"
    Then the response status code should be 401

  Scenario: [Fail] Submit request with not found payload
    When After authentication on url "/api/login_check" with method "POST" as user "johndoe" with password "12345678", I send a "POST" request to "/api/groups" with body:
    """
    {
    }
    """
    Then the response status code should be 400
    And the response should be equal to following file "api/groups/assertionFiles/create_group_no_payload.json"

  Scenario: [Fail] Submit request with an already exist group name
    When After authentication on url "/api/login_check" with method "POST" as user "johndoe" with password "12345678", I send a "POST" request to "/api/groups" with body:
    """
    {
        "name": "Jane",
        "passwordToJoin": "12345678"
    }
    """
    Then the response status code should be 400
    And the response should be equal to following file "api/groups/assertionFiles/create_group_already_exist_group.json"

  Scenario: [Fail] submit request with user already member to a group
    When After authentication on url "/api/login_check" with method "POST" as user "janedoe" with password "12345678", I send a "POST" request to "/api/groups" with body:
    """
    {
        "name": "John",
        "passwordToJoin": "12345678"
    }
    """
    Then the response status code should be 403
    And the response should be equal to following file "api/groups/assertionFiles/create_group_already_group_member.json"
