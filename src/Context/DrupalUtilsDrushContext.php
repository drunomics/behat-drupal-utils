<?php

namespace drunomics\BehatDrupalUtils\Context;

use drunomics\BehatDrupalUtils\StepDefinition\DrupalDrushMiscTrait;
use drunomics\BehatDrupalUtils\StepDefinition\DrupalFormJsEntityBrowserTrait;
use drunomics\BehatDrupalUtils\StepDefinition\DrupalFormParagraphTrait;
use drunomics\BehatDrupalUtils\StepDefinition\JavascriptUtilStepsTrait;
use drunomics\BehatDrupalUtils\StepDefinition\MinkElementTrait;
use Drupal\DrupalExtension\Context\RawDrupalContext;
use drunomics\ServiceUtils\Core\Entity\EntityTypeManagerTrait;

/**
 * Drupal utils leveraging the drush driver, no Drupal API.
 *
 * Includes mink utils.
 */
class DrupalUtilsDrushContext extends RawDrupalContext {

  // Mink-requiring traits.
  use JavascriptUtilStepsTrait;
  use MinkElementTrait;
  // Drupal-drush requiring traits.
  use DrupalDrushMiscTrait;
  use DrupalFormJsEntityBrowserTrait;
  use DrupalFormParagraphTrait;
  use EntityTypeManagerTrait;

  /**
   * Clone node by title.
   *
   * @When I clone the :type node with title :foo to a node with title :name
   */
  public function cloneNodeByTitle($type,$foo, $name) {
    $storage = $this->getEntityTypeManager()->getStorage('node');
    $nodes = $storage->loadByProperties([
        'title' => $foo,
        'type' => $type,
    ]);
    if (!$node = reset($nodes)) {
      throw new \Exception('Unable to load node.');
    }
    $clone = $node->createDuplicate();
    // change title
    $clone->title = $name;
    $clone->set('moderation_state', "published");
    $clone->save();
  }
}
