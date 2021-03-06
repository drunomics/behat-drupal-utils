<?php

namespace drunomics\BehatDrupalUtils\StepDefinition;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Mink\Exception\ExpectationException;
use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Steps for operationg with entity browser widgets.
 *
 * Requres javascript.
 */
trait DrupalFormJsEntityBrowserTrait {

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
   * Function to build jQuery selector for entity browser item.
   *
   * @param string $title_field_class
   *   Class of field containing label e.g. .views-field-title.
   * @param string $label
   *   Label of entity browser item.
   *
   * @return string
   *   Selector for jQuery to target entity browser item.
   */
  protected function getEntityBrowserItemSelector($title_field_class, $label) {
    return ".views-row $title_field_class .media-info:contains($label)";
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
   * @Given I click on item :label in entity browser :entity_browser in field with class :title_field_class
   */
  public function iClickOnItemInEntityBrowser($label, $enity_browser, $title_field_class = ".views-field-title") {
    $item_selector = $this->getEntityBrowserItemSelector($title_field_class, $label);
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
   * @Given I click on item with name :name in entity browser :entity_browser
   */
  public function iClickOnItemWithNameInEntityInBrowser($name, $entity_browser) {
    $found_element = $this->getSession()
      ->evaluateScript("jQuery(\"#entity_browser_iframe_$entity_browser\").contents().find(\".views-field-name:contains('$name')\").length > 0");
    if (!$found_element) {
      throw new ExpectationException('Element not found.', $this->getSession());
    }
    $this->getSession()
      ->evaluateScript("jQuery(\"#entity_browser_iframe_$entity_browser\").contents().find(\".views-field-name:contains('$name')\").first().closest(\".views-row\").click()");
  }

  /**
   * @Given Item with label :label in entity browser :entity_browser should have the class :class_name
   * @Given Item with label :label in entity browser :entity_browser in field with class :title_field_class should have the class :class_name
   */
  public function itemInEntityBrowserShouldHaveClass($label, $enity_browser, $title_field_class = ".views-field-title", $class_name) {
    $item_selector = $this->getEntityBrowserItemSelector($title_field_class, $label);
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
    $this->getSession()
      ->getDriver()
      ->wait(5000, "jQuery(\"$entity_browser_selector\").contents().find(\"$element_selector\").length > 0");
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
    $this->getSession()
      ->getDriver()
      ->wait(5000, "jQuery(\"$entity_browser_selector\").length == 0");
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
