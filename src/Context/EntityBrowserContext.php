<?php

namespace drunomics\BehatDrupalUtils\Context;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Mink\Exception\ExpectationException;
use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Defines application features from the specific context.
 */
class EntityBrowserContext extends RawDrupalContext {

  /**
   * Function to build jQuery selector for entity browser item.
   *
   * @param string $label
   *   Label of entity browser item.
   *
   * @return string
   *   Selector for jQuery to target entity browser item.
   */
  protected function getEntityBrowserItemSelector($label) {
    return ".views-row .views-field-title .media-info:contains($label)";
  }

  /**
   * Function to build entity browser selector from entity browser name.
   *
   * @param string $entity_browser
   *   Machine name of entity browser.
   *
   * @return string
   *   Selector for jQuery to target entity browser.
   */
  protected function getEntityBrowserSelector($entity_browser) {
    return "#entity_browser_iframe_$entity_browser";
  }

  /**
   * @Given I click on item :label in entity browser :entity_browser
   */
  public function iClickOnItemInEntityBrowser($label, $enity_browser) {
    $item_selector = $this->getEntityBrowserItemSelector($label);
    $entity_browser_selector = $this->getEntityBrowserSelector($enity_browser);
    $found_element = $this->getSession()
      ->evaluateScript("jQuery(\"$entity_browser_selector\").contents().find(\"$item_selector\").length > 0");
    if (!$found_element) {
      throw new ExpectationException('Element not found.', $this->getSession());
    }
    $this->getSession()
      ->evaluateScript("jQuery(\"$entity_browser_selector\").contents().find(\"$item_selector\").click()");
  }

  /**
   * @Given Item with label :label in entity browser :entity_browser should have the class :class_name
   */
  public function itemInEntityBrowserShouldHaveClass($label, $enity_browser, $class_name) {
    $item_selector = $this->getEntityBrowserItemSelector($label);
    $entity_browser_selector = $this->getEntityBrowserSelector($enity_browser);
    $this->getSession()->getDriver()->wait(1000, "");
    $result = $this->getSession()
      ->evaluateScript("jQuery(\"$entity_browser_selector\").contents().find(\"$item_selector\").closest('.views-row').hasClass(\"$class_name\")");
    if (!$result) {
      throw new ExpectationException('Class not set.', $this->getSession());
    }
  }

  /**
   * @Then I wait for :element_selector in entity browser :entity_browser
   */
  public function iWaitForInEntityBrowser($element_selector, $entity_browser) {
    $entity_browser_selector = $this->getEntityBrowserSelector($entity_browser);
    $this->getSession()->getDriver()->wait(5000, "jQuery(\"$entity_browser_selector\").contents().find(\"$element_selector\").length > 0");
  }

  /**
   * @Given I click on :element_selector in entity browser :entity_browser
   */
  public function iClickOnInEntityBrowser($element_selector, $entity_browser) {
    $entity_browser_selector = $this->getEntityBrowserSelector($entity_browser);
    $found_element = $this->getSession()
      ->evaluateScript("jQuery(\"$entity_browser_selector\").contents().find(\"$element_selector\").length > 0");
    if (!$found_element) {
      throw new ExpectationException('Element not found.', $this->getSession());
    }
    $this->getSession()
      ->evaluateScript("jQuery(\"$entity_browser_selector\").contents().find(\"$element_selector\").click()");
  }

  /**
   * @Given I wait for entity browser :entity_browser to close
   */
  public function iWaitForEntityBrowserToClose($entity_browser) {
    $entity_browser_selector = $this->getEntityBrowserSelector($entity_browser);
    $this->getSession()->getDriver()->wait(5000, "jQuery(\"$entity_browser_selector\").length == 0");
  }



  /**
   * @Then :element_selector in entity browser :entity_browser should have the class :class_name
   */
  public function inEntityBrowserShouldHaveTheClass($element_selector, $entity_browser, $class_name) {
    $entity_browser_selector = $this->getEntityBrowserSelector($entity_browser);
    $result = $this->getSession()
      ->evaluateScript("jQuery(\"$entity_browser_selector\").contents().find(\"$element_selector\").hasClass(\"$class_name\")");
    if (!$result) {
      throw new ExpectationException('Class not set.', $this->getSession());
    }
  }

  /**
   * @Then :element_selector in entity browser :entity_browser should have at least :count child elements
   */
  public function inEntityBrowserShouldHaveAtLeastChildElements($element_selector, $entity_browser, $count) {
    $entity_browser_selector = $this->getEntityBrowserSelector($entity_browser);
    $result = $this->getSession()
      ->evaluateScript("jQuery(\"$entity_browser_selector\").contents().find(\"$element_selector\").children().length >= $count");
    $result_2 = $this->getSession()
      ->evaluateScript("jQuery(\"$entity_browser_selector\").contents().find(\"$element_selector\").children().length");
    if (!$result) {
      throw new ExpectationException('Element has less children than expected.', $this->getSession());
    }
  }

}
