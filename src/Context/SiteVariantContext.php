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
    $variant_base_url = NULL;
    // @todo Method getHostForSiteVariant was moved from RequestMatcher. Needs simplification.
    if ($variant_host = $this->getHostForSiteVariant($variant)) {
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

  /**
   * Gets the variant host. Moved from drunomics/multisite-request-matcher.
   *
   * @param string $variant
   *   Variant name.
   *
   * @return string|null
   *   Variant hostname or null if variant incorrect.
   */
  protected function getHostForSiteVariant($variant) {
    if ($variants = getenv('APP_SITE_VARIANTS')) {
      $variants = explode(' ', $variants);
    }
    else {
      return NULL;
    }
    $site_variables = $this->getSiteVariables();
    $separator = getenv('APP_SITE_VARIANT_SEPARATOR') ?? '--';
    $variant_host = $variant . $separator . $site_variables['SITE_MAIN_HOST'];

    return $variant_host;
  }

  /**
   * Gets the same site variables as set during request matching.
   *
   * Useful for setting the same environment variables during CLI invocations as
   * during regular request.
   *
   * @param string $site
   *   (optional) The site to use.
   * @param string $site_variant
   *   (optional) The site variant to use.
   *
   * @return array
   *   The array of site variables.
   */
  protected function getSiteVariables($site = NULL, $site_variant = '') {
    $site = $site ?: $this->determineActiveSite();
    $vars = [];
    $vars['SITE'] = $site;
    $vars['SITE_VARIANT'] = $site_variant ?: $this->determineActiveSiteVariant();
    if ($domain = getenv('APP_MULTISITE_DOMAIN')) {
      $host = $site . getenv('APP_MULTISITE_DOMAIN_PREFIX_SEPARATOR') . $domain;
    }
    elseif (getenv('SITE') && getenv('APP_SITE_DOMAIN')) {
      $host = getenv('APP_SITE_DOMAIN');
    }
    else {
      $host = getenv('APP_SITE_DOMAIN__' . str_replace('-', '_', $site));
    }
    if ($vars['SITE_VARIANT']) {
      $separator = getenv('APP_SITE_VARIANT_SEPARATOR') ?: '--';
      $host = $vars['SITE_VARIANT'] . $separator . $host;
    }
    $vars['SITE_HOST'] = $host;
    $vars['SITE_MAIN_HOST'] = $host;
    return $vars;
  }

  /**
   * Determines the currently active site.
   *
   * @return string
   *   The active site's name.
   */
  protected function determineActiveSite() {
    $site = getenv('SITE') ?: getenv('APP_DEFAULT_SITE');
    if (!$site) {
      $sites = explode(' ', getenv('APP_SITES'));
      $site = reset($sites);
    }
    return $site;
  }

  /**
   * Determines the currently active site variant.
   *
   * @return string
   *   The active site variant, '' for the default variant.
   */
  public static function determineActiveSiteVariant() {
    return getenv('SITE_VARIANT') ?: '';
  }

}
