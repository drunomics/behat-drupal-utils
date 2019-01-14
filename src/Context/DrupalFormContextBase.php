<?php

namespace drunomics\BehatDrupalUtils\Context;

use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Helper functions for testing forms with behat.
 */
abstract class DrupalFormContextBase extends RawDrupalContext {

  /**
   * Returns paragraph of specified slot.
   *
   * @param int $slot
   *   Slot number of paragraph.
   *
   * @return \Behat\Mink\Element\NodeElement
   */
  protected function getParagraph($slot) {
    $xpath = "//table[contains(@class, 'field-multiple-table--paragraphs')]/tbody/tr[not(contains(@class, 'paragraphs-features__add-in-between__row'))][$slot]";
    return $this->getParagraphFieldWrapper()
      ->find('xpath', $xpath);
  }

  /**
   * @return \Behat\Mink\Element\NodeElement
   */
  protected function getParagraphFieldWrapper() {
    $xpath = "//div[contains(@class, 'field--widget-paragraphs')]";
    return $this->getSession()->getDriver()->find($xpath)[0];
  }

}