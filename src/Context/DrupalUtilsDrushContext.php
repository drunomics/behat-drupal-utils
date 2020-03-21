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

}
