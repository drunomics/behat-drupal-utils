<?php

/**
 * @file
 * The DrupalFormContext behat context.
 */

namespace drunomics\BehatDrupalUtils\Context;

/**
 * Defines application features from the specific context.
 */
class DrupalFormContext extends DrupalFormContextBase {

  /**
   * @When I click :tab in local tasks
   */
  public function iClickInLokalTasks($tab) {
    $xpath = "//div[contains(@id, 'local-tasks')]//a[contains(text(), '$tab')]";
    $this->getSession()->getDriver()
      ->click($xpath);
  }

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
      $this->getParagraph($slot)->fillField($field, $input);
  }

  /**
   * @Then I add a paragraph :paragraph_label
   *
   * Only works when JS is not enabled.
   */
  public function iAddAParagraph($paragraph_label) {
    $this->getParagraphFieldWrapper()->findButton($paragraph_label)->click();
  }

  /**
   * @Given I press :button in paragraph number :slot
   */
  public function iPressInParagraphNumber($button, $slot) {
    $this->getParagraph($slot)->pressButton($button);
  }

}
