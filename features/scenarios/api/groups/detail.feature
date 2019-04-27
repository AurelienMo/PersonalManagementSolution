@api
@api_group
@api_group_detail

Feature: As an auth user, I need to be access to my group detail

  Background:
    Given I load following users:
      | firstname | lastname | username | password | email               | tokenActivation | status             |
      | John      | Doe      | johndoe  | 12345678 | johndoe@yopmail.com | AZERTYTOKEN     | enabled            |
      | Jane      | Doe      | janedoe  | 12345678 | janedoe@yopmail.com | AZERTYTOKEN     | enabled            |
      | Foo       | Bar      | foobar   | 12345678 | foobar@yopmail.com  | AZERTYTOKEN     | enabled            |
    And I load following group:
      | name | passwordToJoin | owner   |
      | Jane | 12345678       | janedoe |
      | Foob | 12345678       | foobar  |

  Scenario: [Fail] Submit request with anonymous user
    And group with name "Jane" should have id "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa"
    When I send a "GET" request to "/api/groups/aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa" with body:
    """
    {
    }
    """
    Then the response status code should be 401

  Scenario: [Fail] Submit request to invalid group
    When After authentication on url "/api/login_check" with method "POST" as user "janedoe" with password "12345678", I send a "GET" request to "/api/groups/BAD_ID" with body:
    """
    {
    }
    """
    Then the response status code should be 404
    And the response should be equal to following file "api/groups/assertionFiles/detail_group_not_found.json"

  Scenario: [Fail] Unauthorized to access detail group
    And group with name "Jane" should have id "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa"
    When After authentication on url "/api/login_check" with method "POST" as user "foobar" with password "12345678", I send a "GET" request to "/api/groups/aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa" with body:
    """
    {
    }
    """
    Then the response status code should be 403
    And the response should be equal to following file "api/groups/assertionFiles/detail_group_unauthorizd.json"

  Scenario: [Success] Obtain detail group informations
    And group with name "Foob" should have id "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa"
    And user "foobar" should have group with id "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa"
    When After authentication on url "/api/login_check" with method "POST" as user "foobar" with password "12345678", I send a "GET" request to "/api/groups/aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa" with body:
    """
    {
    }
    """
    Then the response status code should be 200
    And the JSON node "id" should be equal to "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa"
    And the JSON node "name" should be equal to "Foob"
    And the JSON node "slug" should be equal to "foob"
    And the JSON node "members" should have 1 element
    And the JSON node "owner.firstname" should be equal to "Foo"
    And the JSON node "owner.lastname" should be equal to "Bar"
