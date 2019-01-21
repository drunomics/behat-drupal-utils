<?php

/**
 * @file
 * The DrupalSmokeContext behat context.
 */

namespace drunomics\BehatDrupalUtils\StepDefinition;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Logger\RfcLogLevel;
use Behat\Mink\Exception\ExpectationException;
use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Provides steps for operating with mink elements.
 *
 * Note that CSS selectors also support :contains; e.g. "div:contains("foo")".
 */
trait MinkElementTrait  {

  /**
   * Gets the mink session.
   *
   * @param string $name
   *   (optional) The name of the session.
   *
   * @return \Behat\Mink\Session
   */
  abstract protected function getSession($name = NULL);

  /**
   * @Then /^I should see Element "([^"]*)" with the Css Style Property "([^"]*)" matching "([^"]*)"$/
   */
  public function iShouldSeeElementWithTheCssStylePropertyMatching($tag, $property, $value) {
    $actual_value = $this->getSession()->evaluateScript("return window.getComputedStyle(document.querySelector('$tag'))['$property'];");
    if ($actual_value !== $value) {
      throw new ExpectationException("CSS Style property $property does not match expected value. Actual value: $actual_value / Expected: $value", $this->getSession());
    }
  }

  /**
   * Waits for x milliseconds.
   *
   * @Given I wait for :milliseconds ms
   */
  public function waitForSomeTime($milliseconds) {
    sleep($milliseconds / 1000);
  }

  /**
   * Focus some element.
   *
   * @When I focus the element :locator
   * @When I focus the field :locator
   */
  public function focusElement($locator) {
    $element = $this->getSession()->getPage()->find('css', $locator);
    if (!isset($element)) {
      throw new ElementNotFoundException($this->getDriver(), NULL, 'css', $locator);
    }
    $element->focus();
  }

  /**
   * Click some element.
   *
   * @When I click on the element :locator
   * @When I click in the field :locator
   */
  public function clickElement($locator) {
    $element = $this->getSession()->getPage()->find('css', $locator);
    if (!isset($element)) {
      throw new ElementNotFoundException($this->getDriver(), NULL, 'css', $locator);
    }
    $element->click();
  }

  /**
   * Click some link contained in some element.
   *
   * @When I click on :link below the element :locator
   */
  public function clickLinkBelowElement($link, $locator) {
    $element = $this->getSession()->getPage()->find('css', $locator);
    if (!isset($element)) {
      throw new ElementNotFoundException($this->getDriver(), NULL, 'css', $locator);
    }
    $element->clickLink($link);
  }

}
