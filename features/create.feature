Feature:
  As an API consumer
  I want to add new books

  Scenario: Create a new book
    Given I am an api consumer
    When I create the following book
      | isbn           | title                                       | author        | category | price |
      | 978-1491905012 | Modern PHP: New Features and Good Practices | Josh Lockhart | PHP      | 18.99 |
    Then the response status code should be 201
    And the body should contain "978-1491905012"
    And the body should contain "Modern PHP: New Features and Good Practices"
    And the body should contain "Josh Lockhart"
    And the body should contain "PHP"
    And the body should contain "18.99"

  Scenario: Create a new book
    Given I am an api consumer
    When I create the following book
      | isbn                        | title                                       | author        | category | price |
      | 978-INVALID-ISBN-1491905012 | Modern PHP: New Features and Good Practices | Josh Lockhart | PHP      | 18.99 |
    Then the response status code should be 400
    And the body should contain "Invalid ISBN"