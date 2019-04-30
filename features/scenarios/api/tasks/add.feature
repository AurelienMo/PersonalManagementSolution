@api
@api_tasks
@api_tasks_add

Feature: As an auth user, I need to be able to add task for me or my group
  Background:
    Given I load following users:
      | firstname | lastname | username | password | email               | tokenActivation | status             |
      | John      | Doe      | johndoe  | 12345678 | johndoe@yopmail.com | AZERTYTOKEN     | enabled            |
      | Jane      | Doe      | janedoe  | 12345678 | janedoe@yopmail.com | AZERTYTOKEN     | enabled            |
      | Foo       | Bar      | foobar   | 12345678 | foobar@yopmail.com  | AZERTYTOKEN     | enabled            |
    And I load following group:
      | name    | passwordToJoin | owner   |
      | JohnDoe | 12345678       | johndoe |
    And user with username "johndoe" should have following role:
     | role       |
     | ROLE_OWNER |
    And user with username "janedoe" should have following role:
      | role       |
      | ROLE_ADMIN |
    And user with username "foobar" should have following role:
      | role        |
      | ROLE_MEMBER |

  Scenario: [Fail] Try with anonymous user
    When I send a "POST" request to "/api/tasks" with body:
    """
    {
    }
    """
    Then the response status code should be 401

  Scenario: [Fail] Try with no datas send
    When After authentication on url "/api/login_check" with method "POST" as user "johndoe" with password "12345678", I send a "POST" request to "/api/tasks" with body:
    """
    {
    }
    """
    Then the response status code should be 400
    And the response should be equal to following file "api/tasks/assertionFiles/add_empty_datas.json"

  Scenario: [Fail] Try with due date less than start date
    And user with username "johndoe" should have following id "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa"
    When After authentication on url "/api/login_check" with method "POST" as user "johndoe" with password "12345678", I send a "POST" request to "/api/tasks" with body:
    """
    {
      "name": "Vider et remplir le lave vaisselle",
      "category": "Rangement",
      "startDate": "2019-05-01 19:00",
      "dueDate": "2019-04-30 10:00"
    }
    """
    Then the response status code should be 400
    And the response should be equal to following file "api/tasks/assertionFiles/add_due_date_less_than_start.json"

  Scenario: [Fail] Try to adding task with role member & affect this to my group
    When After authentication on url "/api/login_check" with method "POST" as user "foobar" with password "12345678", I send a "POST" request to "/api/tasks" with body:
    """
    {
      "name": "Vider et remplir le lave vaisselle",
      "category": "Rangement",
      "affectTo": "John Doe"
    }
    """
    Then the response status code should be 403
    And the JSON should be equal to:
    """
    {
      "code": 403,
      "message": "Vous ne pouvez pas affecter une tâche à un admin ou créateur du groupe."
    }
    """

  Scenario: [Success] Successful adding task for me
    When After authentication on url "/api/login_check" with method "POST" as user "johndoe" with password "12345678", I send a "POST" request to "/api/tasks" with body:
    """
    {
      "name": "Vider et remplir le lave vaisselle",
      "category": "Rangement"
    }
    """
    Then the response status code should be 201
    And task with name "Vider et remplir le lave vaisselle" should be exist and affect to "John Doe"

  Scenario: [Success] Successful adding task for group & not affected
    When After authentication on url "/api/login_check" with method "POST" as user "johndoe" with password "12345678", I send a "POST" request to "/api/tasks" with body:
    """
    {
      "name": "Vider et remplir le lave vaisselle",
      "category": "Rangement",
      "displayInGroup": true
    }
    """
    Then the response status code should be 201
    And task with name "Vider et remplir le lave vaisselle" should be exist and display in group

  Scenario: [Success] Successful adding task for other person in my group
    And user with username "johndoe" should have following group name "JohnDoe"
    And user with username "janedoe" should have following group name "JohnDoe"
    When After authentication on url "/api/login_check" with method "POST" as user "johndoe" with password "12345678", I send a "POST" request to "/api/tasks" with body:
    """
    {
      "name": "Vider et remplir le lave vaisselle",
      "category": "Rangement",
      "displayInGroup": true,
      "affectTo": "Jane Doe"
    }
    """
    Then the response status code should be 201
    And task with name "Vider et remplir le lave vaisselle" should be exist, display in group and affect to "Jane Doe"
