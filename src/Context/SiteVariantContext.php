<?php

namespace drunomics\BehatDrupalUtils\Context;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use drunomics\MultisiteRequestMatcher\RequestMatcher;
use Drupal\DrupalExtension\Context\DrupalContext;
use Behat\MinkExtension\Context\RawMinkContext;
use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Extends drupal context site variant support.
 */
class SiteVariantContext extends RawDrupalContext {

  /**
   * Environment array.
   *
   * @var array
   */
  protected $environment;


  /**
   * @Then I set site variant :variant
   */
  public function setSiteVariant($variant) {
    $variant_base_url = NULL;
    $matcher = new RequestMatcher();
    if ($variant_base_url = $matcher->getHostForSiteVariant($variant)) {
      $this->setBaseUrl($variant_base_url);
    } else {
      throw new Exception('Specified site variant does not exist.');
    }
  }

  /**
   * Sets the base URL for all environments.
   *
   * @param string $url
   *   The url to set.
   *
   * @see: https://github.com/Behat/MinkExtension/issues/155#issuecomment-77041296
   */
  private function setBaseUrl($url) {
    foreach ($this->environment->getContexts() as $context) {
      if ($context instanceof RawMinkContext) {
        $context->setMinkParameter('base_url', $url);
      }
    }
  }

  /**
   * Set environments.
   *
   * @BeforeScenario
   */
  public function beforeScenario(BeforeScenarioScope $scope) {
    // Load and save the environment for each scenario.
    $this->environment = $scope->getEnvironment();
    $base_url = $this->getMinkParameter('base_url');
    $this->setBaseUrl($base_url);
  }

}
