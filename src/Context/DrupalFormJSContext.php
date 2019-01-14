<?php

/**
 * @file
 * The DrupalFormContext behat context.
 */

namespace drunomics\BehatDrupalUtils\Context;

use Behat\Mink\Exception\ExpectationException;

/**
 * Defines application features from the specific context.
 *
 * Requires JS.
 */
class DrupalFormJSContext extends DrupalFormContextBase {

  /**
   * @Then I add a paragraph :paragraph_type_label at slot number :slot
   */
  public function iAddAParagraphAtSlotNumber($paragraph_type_label, $slot) {
    $xpath_add_button = "//div[contains(@class, 'field--widget-paragraphs')]//table[contains(@class, 'field-multiple-table--paragraphs')]/tbody/tr[contains(@class, 'paragraphs-features__add-in-between__row')][$slot]";
    $xpath_paragraph_list = "//div[contains(@class, 'ui-dialog')]//ul";
    $this->getSession()->getDriver()->click($xpath_add_button);
    $this->getSession()->getDriver()
      ->find($xpath_paragraph_list)[0]
      ->findButton($paragraph_type_label)
      ->click();
  }

  /**
   * @Then I fill in the Wysiwyg :locator with :value in paragraph number :slot
   */
  public function iFillInTheWysiwygWithInParagraphNumber($locator, $value, $slot) {
    $paragraph = $this->getParagraph($slot);
    $this->setDataInWysiwyg($locator, $value, $paragraph);
  }

  /**
   * Sets value in Wysiwyg editor of given field.
   *
   * @param string $locator
   *   The field locator.
   * @param mixed $value
   *   The value.
   * @param \Behat\Mink\Element\NodeElement $parent
   *   The field's parent.
   */
  protected function setDataInWysiwyg($locator, $value, $parent = NULL) {
    if (empty($parent)) {
      $parent = $this->getSession()->getPage();
    }

    $field = $parent->findField($locator);

    if (empty($field)) {
      throw new ExpectationException('Could not find WYSIWYG in field ' . $locator, $this->getSession());
    }

    $fieldId = $field->getAttribute('id');

    if (empty($fieldId)) {
      throw new \Exception('Could not find an id for field ' . $locator);
    }

    $this->getSession()
      ->executeScript("CKEDITOR.instances['$fieldId'].setData('$value');");
  }

  /**
   * @Then I wait for :css_selector in paragraph number :slot
   */
  public function iWaitForInParagraphNumber($css_selector, $slot) {
    $css_selector = ".field--widget-paragraphs tr:nth-child($slot) $css_selector";
    $this->getSession()->wait('5000', "jQuery(\"$css_selector\").length == 1");
  }

}