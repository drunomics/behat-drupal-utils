<?php

namespace drunomics\BehatDrupalUtils\Context;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\MinkExtension\Context\MinkAwareContext;
use Behat\Testwork\Environment\Environment;
use Drupal\DrupalExtension\Context\DrupalContext;
use Behat\MinkExtension\Context\RawMinkContext;
use Drupal\DrupalExtension\Context\RawDrupalContext;
use Drupal\DrupalExtension\Manager\DrupalAuthenticationManager;

/**
 * Extends drupal context site variant support.
 */
class SiteVariantContext extends RawDrupalContext {

  /**
   * The behat environment.
   *
   * @var Environment
   */
  protected $environment;

  /**
   * @Then I set site variant :variant
   */
  public function setSiteVariant($variant) {
    $variant_host = $variant . getenv('APP_SITE_VARIANT_SEPARATOR') . getenv('DRUPAL_SITE') . getenv('HOST_SEPARATOR') . getenv('MAIN_HOST');
    if ($variant_host) {
      $url_scheme = "";
      if ($url_scheme = getenv('URL_SCHEME')) {
        $url_scheme .= ":";
      }
      $this->setBaseUrl($url_scheme . "//" . $variant_host);
    }
    else {
      throw new \Exception('Specified site variant does not exist.');
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
      if ($context instanceof MinkAwareContext) {
        $context->setMinkParameter('base_url', $url);
      }
    }
    // Also update the authentication manager of the drupal extension.
    $authManager = $this->getAuthenticationManager();
    if ($authManager instanceof DrupalAuthenticationManager) {
      $authManager->setMinkParameter('base_url', $url);
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
    $this->setBaseUrl($this->getMinkParameter('base_url'));
  }

}
