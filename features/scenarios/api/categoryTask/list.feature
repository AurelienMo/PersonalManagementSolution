@api
@api_category_task
@api_category_task_list

Feature: As an auth user, I need to be able to get list category task
  Background:
    Given I load following users:
      | firstname | lastname | username | password | email               | tokenActivation | status  |
      | John      | Doe      | johndoe  | 12345678 | johndoe@yopmail.com | AZERTYTOKEN     | enabled |

  Scenario: [Fail] Try with anonymous user
    When I send a "GET" request to "/api/taskCategories"
    Then the response status code should be 401

  Scenario: [Success] Successful list with no datas
    When After authentication on url "/api/login_check" with method "POST" as user "johndoe" with password "12345678", I send a "GET" request to "/api/taskCategories" with body:
    """
    {
    }
    """
    Then the response status code should be 204
    And the response should be empty

  Scenario: [Success] Successful list with datas
    And I load following task categories:
      | name           |
      | Course         |
      | Tâche ménagère |
      | Ecole          |
      | Centre aéré    |
    When After authentication on url "/api/login_check" with method "POST" as user "johndoe" with password "12345678", I send a "GET" request to "/api/taskCategories" with body:
    """
    {
    }
    """
    Then the response status code should be 200
    And the JSON node "root" should have 4 elements
    And the JSON node "root[0].name" should be equal to "Course"
    And the JSON node "root[1].name" should be equal to "Tâche ménagère"
    And the JSON node "root[2].name" should be equal to "Ecole"
    And the JSON node "root[3].name" should be equal to "Centre aéré"

  Scenario: [Success] Successful list with datas filtered
    And I load following task categories:
      | name           |
      | Course         |
      | Tâche ménagère |
      | Ecole          |
      | Centre aéré    |
    When After authentication on url "/api/login_check" with method "POST" as user "johndoe" with password "12345678", I send a "GET" request to "/api/taskCategories?search=Ecole" with body:
    """
    {
    }
    """
    Then the response status code should be 200
    Then the response status code should be 200
    And the JSON node "root" should have 1 element
    And the JSON node "root[0].name" should be equal to "Ecole"
