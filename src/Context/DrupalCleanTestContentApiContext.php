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
      ->accessCheck(FALSE)
      ->condition('title', 'BEHAT:', 'STARTS_WITH')
      ->execute();
    if (!empty($nids)) {
      $nodes = \Drupal::entityTypeManager('node')
        ->getStorage('node')
        ->loadMultiple($nids);
      if (!empty($nodes)) {
        \Drupal::entityTypeManager('node')
          ->getStorage('node')
          ->delete($nodes);
      }
    }

    $tids = \Drupal::entityQuery('taxonomy_term')
      ->accessCheck(FALSE)
      ->condition('name', 'BEHAT:', 'STARTS_WITH')
      ->execute();
    if (!empty($tids)) {
      $terms = \Drupal::entityTypeManager('taxonomy_term')
        ->getStorage('taxonomy_term')
        ->loadMultiple($tids);
      if (!empty($terms)) {
        \Drupal::entityTypeManager('taxonomy_term')
          ->getStorage('taxonomy_term')
          ->delete($terms);
      }
    }
  }

}
