@api
@api_group
@api_group_remove_member

Feature: As an auth & owner group user I need to able to remove member from my group

  Background:
    Given I load following users:
      | firstname | lastname | username | password | email               | tokenActivation | status             |
      | John      | Doe      | johndoe  | 12345678 | johndoe@yopmail.com | AZERTYTOKEN     | enabled            |
      | Jane      | Doe      | janedoe  | 12345678 | janedoe@yopmail.com | AZERTYTOKEN     | enabled            |
      | Foo       | Bar      | foobar   | 12345678 | foobar@yopmail.com  | AZERTYTOKEN     | enabled            |
      | Bar       | Foo      | barfoo   | 12345678 | barfoo@yopmail.com  | AZERTYTOKEN     | enabled            |
      | Test      | Test     | test     | 12345678 | test@yopmail.com    | AZERTYTOKEN     | enabled            |
    And I load following group:
      | name | passwordToJoin | owner   |
      | Jane | 12345678       | janedoe |
      | Foob | 12345678       | foobar  |

  Scenario: [Fail] Submit request with anonymous user
    And group with name "Foob" should have id "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa"
    And user with username "barfoo" should have following id "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa"
    When I send a "DELETE" request to "/api/groups/aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa/members/aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa"
    Then the response status code should be 401

  Scenario: [Fail] Unauthorized remove member to other group
    And group with name "Foob" should have id "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa"
    And user with username "barfoo" should have following group name "Foob"
    And user with username "barfoo" should have following id "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa"
    When After authentication on url "/api/login_check" with method "POST" as user "janedoe" with password "12345678", I send a "DELETE" request to "/api/groups/aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa/members/aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa" with body:
    """
    """
    Then the response status code should be 403
    And the JSON node "message" should be equal to "Vous n'êtes pas autorisé à supprimer un membre d'un groupe dont vous n'êtes pas le propriétaire."

  Scenario: [Fail] Unauthorized remove member with invalid permission
    And group with name "Foob" should have id "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa"
    And user with username "barfoo" should have following group name "Foob"
    And user with username "johndoe" should have following group name "Foob"
    And user with username "test" should have following group name "Foob"
    And user with username "test" should have following role:
    | role              |
    | 'ROLE_MEMBER'     |
    | 'ROLE_ADMIN'      |
    | 'ROLE_USER'       |
    And user with username "barfoo" should have following id "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa"
    When After authentication on url "/api/login_check" with method "POST" as user "johndoe" with password "12345678", I send a "DELETE" request to "/api/groups/aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa/members/aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa" with body:
    """
    """
    Then the response status code should be 403
    And the JSON node "message" should be equal to "Vous n'avez pas les permissions suffisantes pour supprimer un membre de votre groupe."

  Scenario: [Success] Remove member with owner
    And group with name "Foob" should have id "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa"
    And user with username "barfoo" should have following group name "Foob"
    And user with username "johndoe" should have following group name "Foob"
    And user with username "test" should have following group name "Foob"
    And user with username "test" should have following role:
      | role              |
      | 'ROLE_MEMBER'     |
      | 'ROLE_ADMIN'      |
      | 'ROLE_USER'       |
    And user with username "barfoo" should have following id "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa"
    When After authentication on url "/api/login_check" with method "POST" as user "foobar" with password "12345678", I send a "DELETE" request to "/api/groups/aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa/members/aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa" with body:
    """
    """
    Then the response status code should be 204

  Scenario: [Success] Remove member with admin
    And group with name "Foob" should have id "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa"
    And user with username "barfoo" should have following group name "Foob"
    And user with username "johndoe" should have following group name "Foob"
    And user with username "test" should have following group name "Foob"
    And user with username "test" should have following role:
      | role              |
      | 'ROLE_MEMBER'     |
      | 'ROLE_ADMIN'      |
      | 'ROLE_USER'       |
    And user with username "barfoo" should have following id "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa"
    When After authentication on url "/api/login_check" with method "POST" as user "test" with password "12345678", I send a "DELETE" request to "/api/groups/aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa/members/aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa" with body:
    """
    """
    Then the response status code should be 204


