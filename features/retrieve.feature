@fixtures
Feature:
  As an API consumer
  I want to retrieve a list of books

  Scenario: Retrieve books by Author: Robin Nixon
    Given I am an api consumer
    When I query for a list of books
    And I filter by author "Robin Nixon"
    Then I should receive a 200 response
    And the content type should be "application/json"
    And the body should contain 2 results
    And the body should contain "978-1491918661"
    And the body should contain "978-0596804848"

  Scenario: Retrieve books by Author: Christopher Negus
    Given I am an api consumer
    When I query for a list of books
    Then I filter by author "Christopher Negus"
    Then I should receive a 200 response
    And the content type should be "application/json"
    And the body should contain 1 result
    And the body should contain "978-1118999875"

  Scenario: Retrieve a list of all categories
    Given I am an api consumer
    When  I query the api for a list of categories
    Then I should receive a 200 response
    And the content type should be "application/json"
    And the body should contain 3 results
    And the body should contain "PHP"
    And the body should contain "Javascript"
    And the body should contain "Linux"

  Scenario: Retrieve books by Category: Linux
    Given I am an api consumer
    When I query for a list of books
    And I filter by category "Linux"
    Then I should receive a 200 response
    And the content type should be "application/json"
    And the body should contain 2 results
    And the body should contain "978-0596804848"
    And the body should contain "978-1118999875"

  Scenario: Retrieve books by Category: PHP
    Given I am an api consumer
    When I query for a list of books
    And I filter by category "PHP"
    Then I should receive a 200 response
    And the content type should be "application/json"
    And the body should contain 1 result
    And the body should contain "978-1491918661"

  Scenario: Retrieve books by Author: Robin Nixon & Category: Linux
    Given I am an api consumer
    When I query for a list of books
    And I filter by author "Robin Nixon"
    And I filter by category "Linux"
    Then I should receive a 200 response
    And the content type should be "application/json"
    And the body should contain 1 result
    And the body should contain "978-0596804848"