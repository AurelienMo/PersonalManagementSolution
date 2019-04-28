@api
@api_group
@api_group_update_role

Feature: As an auth user & admin or owner role, I need to be able to update role
  Background:
    Given I load following users:
      | firstname | lastname | username | password | email               | tokenActivation | status   |
      | John      | Doe      | johndoe  | 12345678 | johndoe@yopmail.com | AZERTYTOKEN     | enabled  |
      | Jane      | Doe      | janedoe  | 12345678 | janedoe@yopmail.com | AZERTYTOKEN     | enabled  |
      | Foo       | Bar      | foobar   | 12345678 | foobar@yopmail.com  | AZERTYTOKEN     | enabled  |
    And I load following group:
      | name | passwordToJoin | owner   |
      | John | 12345678       | johndoe |

  Scenario: [Fail] Try with anonymous user
    When I send a "PUT" request to "/api/groups/aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa/members/aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa"
    Then the response status code should be 401

  Scenario: [Fail] Try with invalid group
    When After authentication on url "/api/login_check" with method "POST" as user "johndoe" with password "12345678", I send a "PUT" request to "/api/groups/aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaab/members/aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaab" with body:
    """
    {
    }
    """
    Then the response status code should be 404
    And the JSON node "message" should be equal to "Ce groupe n'existe pas."

  Scenario: [Fail] Try with invalid member
    And group with name "John" should have id "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa"
    When After authentication on url "/api/login_check" with method "POST" as user "johndoe" with password "12345678", I send a "PUT" request to "/api/groups/aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa/members/aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaab" with body:
    """
    {
    }
    """
    Then the response status code should be 404
    And the JSON node "message" should be equal to "Ce membre n'existe pas."

  Scenario: [Fail] Try with member from other group
    And group with name "John" should have id "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa"
    And user with username "foobar" should have following group name "John"
    And user with username "foobar" should have following id "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa"
    When After authentication on url "/api/login_check" with method "POST" as user "janedoe" with password "12345678", I send a "PUT" request to "/api/groups/aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa/members/aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa" with body:
    """
    {
    }
    """
    Then the response status code should be 403
    And the JSON node "message" should be equal to "Vous n'êtes pas autorisé à modifier le statut d'un membre d'un groupe dont vous n'êtes pas le propriétaire."


  Scenario: [Fail] Try with insufficient permissions
    And group with name "John" should have id "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa"
    And user with username "foobar" should have following id "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa"
    And user with username "janedoe" should have following group name "John"
    And user with username "johndoe" should have following group name "John"
    And user with username "foobar" should have following group name "John"
    And user with username "janedoe" should have following role:
      | role        |
      | ROLE_MEMBER |
    When After authentication on url "/api/login_check" with method "POST" as user "janedoe" with password "12345678", I send a "PUT" request to "/api/groups/aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa/members/aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa" with body:
    """
    {
    }
    """
    Then the response status code should be 403

  Scenario: [Fail] Submit request with no role given into payload
    And group with name "John" should have id "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa"
    And user with username "johndoe" should have following group name "John"
    And user with username "johndoe" should have following role:
      | role       |
      | ROLE_OWNER |
    And user with username "foobar" should have following group name "John"
    And user with username "foobar" should have following id "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa"
    When After authentication on url "/api/login_check" with method "POST" as user "johndoe" with password "12345678", I send a "PUT" request to "/api/groups/aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa/members/aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa" with body:
    """
    {
    }
    """
    Then the response status code should be 400
    And the response should be equal to following file "api/groups/assertionFiles/update_role_invalid_payload.json"

