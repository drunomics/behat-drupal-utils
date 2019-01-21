<?php

namespace drunomics\BehatDrupalUtils\StepDefinition;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Logger\RfcLogLevel;
use Behat\Mink\Exception\ExpectationException;
use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Misc Drupal utils working with the drush driver.
 */
trait DrupalDrushMiscTrait  {

  /**
   * Gets the mink session.
   *
   * @return \Behat\Mink\Session
   */
  abstract protected function getSession();

  /**
   * Determine if the a user is already logged in.
   *
   * @return boolean
   *   Returns TRUE if a user is logged in for this session.
   */
  abstract protected function loggedIn();

  /**
   * @Then I wait for the page to be loaded
   */
  public function iWaitForThePageToBeLoaded() {
    $this->getSession()->wait(30000, "document.readyState === 'complete'");
  }

  /**
   * @Then I should be logged in.
   */
  public function iShouldbeLoggedIn() {
    if (!$this->loggedIn()) {
      throw new ExpectationException("No user is logged in.", $this->getSession());
    }
  }

  /**
   * @Then I should not be logged in.
   */
  public function iShouldNotbeLoggedIn() {
    if ($this->loggedIn()) {
      throw new ExpectationException("A user is logged in, but should not.", $this->getSession());
    }
  }

  /**
   * @Then I should be redirected to :url.
   */
  public function iShouldBeRedirectedTo($path) {
    if ($this->getSession()->getCurrentUrl() != $this->locatePath($path)) {
      throw new ExpectationException("URL does not match expected path.", $this->getSession());
    }
  }

}
