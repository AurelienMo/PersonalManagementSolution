@web
@web_doc_api

Feature: I need to be able to access doc api
  Scenario: [Success] Successful access api documentation
    When I go to "/documentation/common"
    Then the response status code should be 200
