@api
@smoke
Feature: Drupal basically works.

  Make sure Drupal generates the front page, error pages as well as logging in
  and out.

  Scenario: Drupal generates a page
    Given I am on "/"
    Then the response should contain "Drupal 8 (Thunder | http://www.thunder.org)"

  Scenario: Drupal generates a 404 response
    Given I am an anonymous user
    And I am on "some-not-existing-page"
    Then I should see "404"

  Scenario: Drupal generates a 403 response
    Given I am an anonymous user
    And I am on "/admin"
    Then I should see "403"

  Scenario: I can log in and logout.
    Given I am logged in as a user with the "authenticated user" role
    Then I should see the link "Abmelden"
    When I click "Abmelden"
    Then I should not see the link "Abmelden"

  @javascript
  Scenario: Frontend assets are loaded.
    Given I am on "/"
    Then I should see Element "body" with the Css Style Property "background-color" matching "rgb(255, 255, 255)"

  @javascript
  Scenario: No javascript errors are generated.
    Given I am on "/"
    Then I should not see any javascript errors in the console