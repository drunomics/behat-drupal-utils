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
 */
trait MinkElementTrait  {

  /**
   * Gets the mink session.
   *
   * @return \Behat\Mink\Session
   */
  abstract protected function getSession();

  /**
   * @Then /^I should see Element "([^"]*)" with the Css Style Property "([^"]*)" matching "([^"]*)"$/
   */
  public function iShouldSeeElementWithTheCssStylePropertyMatching($tag, $property, $value) {
    $actual_value = $this->getSession()->evaluateScript("return window.getComputedStyle(document.querySelector('$tag'))['$property'];");
    if ($actual_value !== $value) {
      throw new ExpectationException("CSS Style property $property does not match expected value. Actual value: $actual_value / Expected: $value", $this->getSession());
    }
  }

}
