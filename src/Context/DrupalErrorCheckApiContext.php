<?php

namespace drunomics\BehatDrupalUtils\Context;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Logger\RfcLogLevel;
use Behat\Mink\Exception\ExpectationException;
use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Ensures there are no erros in watchdog.
 *
 * It also provides an optional step for checking for js console warnings.
 */
class DrupalErrorCheckApiContext extends RawDrupalContext {

  /**
   * If watchdog should fail on notice.
   *
   * @var boolean $fail_on_notice
   */
  protected static $fail_on_notice;

  /**
   * Construct a new entity.
   *
   * @param bool $fail_on_notice
   *   Variable to change severity level of watchdog errors.
   */
  public function __construct($fail_on_notice = FALSE) {
    static::$fail_on_notice = $fail_on_notice;
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
   * @Then /^there are no watchdog errors$/
   */
  public function IshouldSeeNoErrors() {
    static::checkForWatchdogErrors();
  }

  /**
   * @AfterFeature
   */
  public static function checkForWatchdogErrors() {
    $timestamp = static::$timestamp;
    $severity_level = static::$fail_on_notice ? RfcLogLevel::NOTICE : RfcLogLevel::WARNING;
    $query = \Drupal::database()->select('watchdog', 'w');
    $query->fields("w");
    $query->condition('timestamp', $timestamp, '>');
    $query->condition('severity', $severity_level, '<=');
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
