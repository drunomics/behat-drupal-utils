<?php

namespace drunomics\BehatDrupalUtils\StepDefinition;

use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Steps around paragraph widgets in Drupal forms.
 *
 * Partly requires JS.
 */
trait DrupalFormParagraphTrait {

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
   * @Then I fill in :field with :input in paragraph number :slot
   *
   * @param string $field
   *   Field's id, name or label.
   * @param mixed $input
   *   Value of field.
   * @param int $slot
   *   Slot number of paragraph.
   */
  public function iFillInWithInParagraphNumber($field, $input, $slot) {
    $this->getParagraphForm($slot)->fillField($field, $input);
  }

  /**
   * @Given I press :button in paragraph number :slot
   */
  public function iPressInParagraphNumber($button, $slot) {
    $this->getParagraphForm($slot)->pressButton($button);
  }

  /**
   * Returns paragraph form of specified slot ("delta").
   *
   * @param int $slot
   *   Slot number of paragraph.
   *
   * @return \Behat\Mink\Element\NodeElement
   */
  protected function getParagraphForm($slot) {
    $xpath = "//table[contains(@class, 'field-multiple-table--paragraphs')]/tbody/tr[not(contains(@class, 'paragraphs-features__add-in-between__row'))][$slot]";
    return $this->getParagraphFormFieldWrapper()
      ->find('xpath', $xpath);
  }

  /**
   * @return \Behat\Mink\Element\NodeElement
   */
  protected function getParagraphFormFieldWrapper() {
    $xpath = "//div[contains(@class, 'field--widget-paragraphs')]";
    return $this->getSession()->getDriver()->find($xpath)[0];
  }

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

    $index = $slot - 1;
    $condition = "return jQuery(\"div[data-drupal-selector=edit-field-paragraphs-$index]\").length";
    $result = $this->getSession()->wait(5000, $condition);
    if (!$result) {
      throw new ExpectationException("Unable to add paragraph $paragraph_type_label at slot $slot.");
    }
  }

  /**
   * @Then I fill in the Wysiwyg :locator with :value in paragraph number :slot
   *
   * Requires javascript.
   */
  public function iFillInTheWysiwygWithInParagraphNumber($locator, $value, $slot) {
    $paragraph = $this->getParagraphForm($slot);
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
   *
   * Requires javascript.
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
   *
   * Requires javascript.
   */
  public function iWaitForInParagraphNumber($css_selector, $slot) {
    $css_selector = ".field--widget-paragraphs tr:nth-child($slot) $css_selector";
    $this->getSession()->wait('5000', "jQuery(\"$css_selector\").length == 1");
  }

}
