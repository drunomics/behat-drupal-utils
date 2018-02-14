<?php

/**
 * @file
 * The WatchdogCatcher behat context.
 */

use Drupal\Component\Render\FormattableMarkup;
use Drupal\DrupalExtension\Context\DrupalContext;
use Drupal\Core\Logger\RfcLogLevel;
/**
 * Defines application features from the specific context.
 */
class WatchdogCatcher extends DrupalContext {

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
