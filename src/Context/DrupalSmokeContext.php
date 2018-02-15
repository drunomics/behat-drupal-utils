<?php

/**
 * @file
 * The WatchdogCatcher behat context.
 */


namespace drunomics\BehatDrupalSmoke\Context;

use Behat\MinkExtension\Context\MinkContext;
use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Logger\RfcLogLevel;
use Behat\Mink\Exception\ExpectationException;
use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Defines application features from the specific context.
 */
class DrupalSmokeContext extends RawDrupalContext {

  /**
   * @Then I should be redirected to :url.
   */
  public function iShouldBeRedirectedTo($path) {
    if ($this->getSession()->getCurrentUrl() != $this->locatePath($path)) {
      throw new ExpectationException("URL does not match expected path.", $this->getSession());
    }
  }

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
   * @Then /^I should not see any javascript errors in the console$/
   */
  public function iShouldNotSeeAnyJavascriptErrorsInTheConsole() {
    $errors = $this->getSession()->evaluateScript("window.behat_testing.errors");
    if (!empty($errors)) {
      $error_string = implode("\n", $errors);
      throw new ExpectationException("There were javascript errors logged to the console. \n$error_string", $this->getSession());
    }
  }

  protected static $timestamp;

  /**
   * @BeforeFeature
   */
  public static function beforeFeature() {
    $query = \Drupal::database()->select('watchdog', 'w');
    $query->addField('w', 'timestamp');
    $query->orderBy('timestamp', 'DESC');
    $timestamp = $query->execute()->fetchField();
    static::$timestamp = $timestamp;
  }

  /**
   * @AfterFeature
   */
  public static function afterFeature() {
    $timestamp = static::$timestamp;
    $query = \Drupal::database()->select('watchdog', 'w');
    $query->fields("w");
    $query->condition('timestamp', $timestamp, '>');
    $query->condition('severity', RfcLogLevel::WARNING, '<=');
    $query->condition('type', 'php');
    $query->orderBy('timestamp', 'DESC');
    $log_entries = $query->execute()->fetchAllAssoc('wid');

    if ($log_entries && is_array($log_entries)) {
      foreach ($log_entries as $entry) {
        // @see \Drupal\dblog\Controller\DbLogController::formatMessage()
        $variables = (array) @unserialize($entry->variables);
        $message = (new FormattableMarkup($entry->message, $variables))->__toString();

        if ($entry->severity <= RfcLogLevel::ERROR) {
          trigger_error($message . ':' . $entry->wid . ':' . $entry->type . ':' . $entry->severity, E_USER_WARNING);
        }
        else {
          trigger_error($message . ':' . $entry->wid . ':' . $entry->type . ':' . $entry->severity, E_USER_NOTICE);
        }
      }
    }
  }

}
