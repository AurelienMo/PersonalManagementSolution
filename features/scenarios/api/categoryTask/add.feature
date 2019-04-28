@api
@api_category_task
@api_category_task_add

Feature: As an auth user, I need to be able to add task category
  Background:
    Given I load following users:
      | firstname | lastname | username | password | email               | tokenActivation | status  |
      | John      | Doe      | johndoe  | 12345678 | johndoe@yopmail.com | AZERTYTOKEN     | enabled |

  Scenario: [Fail] try with anonymous user
    When I send a "POST" request to "/api/taskCategories" with body:
    """
    {
    }
    """
    Then the response status code should be 401

  Scenario: [Fail] try with no payload
    When After authentication on url "/api/login_check" with method "POST" as user "johndoe" with password "12345678", I send a "POST" request to "/api/taskCategories" with body:
    """
    {
    }
    """
    Then the response status code should be 400
    And the response should be equal to following file "api/categoryTask/assertionFiles/add_no_payload.json"


  Scenario: [Fail] try with already exist name
    And I load following task categories:
      | name           |
      | Course         |
    When After authentication on url "/api/login_check" with method "POST" as user "johndoe" with password "12345678", I send a "POST" request to "/api/taskCategories" with body:
    """
    {
      "name": "Course"
    }
    """
    Then the response status code should be 400
    And the response should be equal to following file "api/categoryTask/assertionFiles/add_name_already_exist.json"

  Scenario: [Success] Successful adding task category
    When After authentication on url "/api/login_check" with method "POST" as user "johndoe" with password "12345678", I send a "POST" request to "/api/taskCategories" with body:
    """
    {
      "name": "Course"
    }
    """
    Then the response status code should be 201
    And the response should be empty
    And database should count "1" entry for entity "App\Entity\CategoryTask" into database
