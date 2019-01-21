<?php

namespace drunomics\BehatDrupalUtils\Context;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Logger\RfcLogLevel;
use Behat\Mink\Exception\ExpectationException;
use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Deletes various entities labeled with the BEHAT: prefix.
 *
 * For now, this only covers nodes.
 */
class DrupalCleanTestContentApiContext extends RawDrupalContext {

  /**
   * Clean-up content we created.
   *
   * Runs after each scenario but also before a feature if previous runs where
   * aborted in the middle.
   *
   * @BeforeFeature
   * @AfterScenario
   */
  public static function cleanupContent() {
    $nids = \Drupal::entityQuery('node')
      ->condition('title', 'BEHAT:', 'STARTS_WITH')
      ->execute();
    if (!$nids) {
      return;
    }
    $nodes = \Drupal::entityTypeManager('node')
      ->getStorage('node')
      ->loadMultiple($nids);
    if (!$nodes) {
      return;
    }
    \Drupal::entityTypeManager('node')
      ->getStorage('node')
      ->delete($nodes);
  }

}
