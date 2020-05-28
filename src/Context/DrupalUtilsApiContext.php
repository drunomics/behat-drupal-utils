<?php

namespace drunomics\BehatDrupalUtils\Context;

/**
 * Drupal utils requiring the Drupal API driver.
 *
 * Includes all of drush and mink utils.
 */
class DrupalUtilsApiContext extends DrupalUtilsDrushContext {

  /**
   * Clone node by title.
   *
   * @When I clone the :type node with title :foo to a node with title :name
   */
  public function cloneNodeByTitle($type, $foo, $name) {
    $storage = $this->getEntityTypeManager()->getStorage('node');
    $nodes = $storage->loadByProperties([
      'title' => $foo,
      'type' => $type,
    ]);
    if (!$node = reset($nodes)) {
      throw new \Exception('Unable to load node.');
    }
    $clone = $node->createDuplicate();
    $clone->title = $name;
    $clone->set('moderation_state', "published");
    $clone->save();
  }

  /**
   * Visit node page by title.
   *
   * @When I visit :type node with title :title
   */
  public function visitNodeByTitle($type, $title) {
    $storage = $this->getEntityTypeManager()->getStorage('node');
    $nodes = $storage->loadByProperties([
      'title' => $title,
      'type' => $type,
    ]);
    if ($node = reset($nodes)) {
      $this->visitPath($node->toUrl()->toString());
    }
    else {
      throw new \Exception('Unable to load node.');
    }
  }

  /**
   * Visit node custom page by title with optional custom destination.
   * Example: When I visit "article" edit page with title "example article title"
   * Example: When I visit "layout" page of "article" "example article title"
   * Example: When I visit "layout" page of "article" "example article title" with "?destination=/admin/content"
   *
   * @When I visit :type edit page with title :title
   * @When I visit :page page of :type :title
   * @When I visit :page page of :type :title with :destination
   */
  public function visitNodeCustomPageByTitle($type, $title, $page = 'edit', $destination = '') {
    $storage = $this->getEntityTypeManager()->getStorage('node');
    $nodes = $storage->loadByProperties([
      'title' => $title,
      'type' => $type,
    ]);
    if ($node = reset($nodes)) {
      $this->visitPath($this->locatePath('/node/' . $node->id() . '/' . $page . $destination));
    }
    else {
      throw new \Exception('Unable to load node.');
    }
  }

}
