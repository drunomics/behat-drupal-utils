@api
@js
Feature: JavaScript basically functions

  @javascript
  Scenario: I can trigger a click event on the first link to be found on the page
    Given I am on "/"
    And I trigger the "click" event on the first link to be found on the page
    And I should get a 200 HTTP response