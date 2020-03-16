<?php

namespace drunomics\BehatDrupalUtils\Context;

use Behat\MinkExtension\Context\RawMinkContext;
use drunomics\BehatDrupalUtils\StepDefinition\JavascriptUtilStepsTrait;
use drunomics\BehatDrupalUtils\StepDefinition\MinkElementTrait;

/**
 * Extends mink context with custom step definitions.
 */
class MinkUtilsContext extends RawMinkContext {

  use JavascriptUtilStepsTraitStepsTrait;
  use MinkElementTrait;

  /**
   * Clone node by title.
   *
   * @When I clone the node with title :title to node with title :name
   */
  public function cloneNodeByTitle($title, $name) {
    $storage = \Drupal::entityTypeManager()->getStorage('node');
    $nodes = $storage->loadByProperties(['title' => $title]);
    if ($node = reset($nodes)) {
      $original_values = $node->toArray();

      // assign content type as string, the array causes an error when creating a new node
      $original_values['type'] = $node->bundle();

      // change title
      $original_values['title'] = $name;

      // remove nid and uuid, the cloned node is assigned new ones when saved
      unset($original_values['nid']);
      unset($original_values['uuid']);

      // remove revision data, the latest revision becomes the only one in the new node
      unset($original_values['vid']);
      unset($original_values['revision_translation_affected']);
      unset($original_values['revision_uid']);
      unset($original_values['revision_log']);
      unset($original_values['revision_timestamp']);

      $node_cloned = $storage->create($original_values);
      $node_cloned->save();
    }else {
      throw new \Exception('Unable to load node.');
    }
  }
}
