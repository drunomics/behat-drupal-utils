<?php

namespace drunomics\BehatDrupalUtils\Context;

use Behat\MinkExtension\Context\RawMinkContext;
use drunomics\BehatDrupalUtils\StepDefinition\JavascriptUtilStepsTrait;
use drunomics\BehatDrupalUtils\StepDefinition\MinkElementTrait;

/**
 * Extends mink context with custom step definitions.
 */
class MinkUtilsContext extends RawMinkContext {

  use JavascriptUtilStepsTrait;
  use MinkElementTrait;
}
