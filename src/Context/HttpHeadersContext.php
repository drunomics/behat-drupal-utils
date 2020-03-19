<?php

namespace drunomics\BehatDrupalUtils\Context;

use Behat\Mink\Exception\ExpectationException;
use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Defines application features from the specific context.
 */
class HttpHeadersContext extends RawDrupalContext {

  /**
   * @Then the HTTP header :header is :string
   */
  public function theHttpHeaderIs($header, $string) {
    $this->assertSession()->responseHeaderEquals($header, $string);
  }

  /**
   * @Then the HTTP header :header is not :string
   */
  public function theHttpHeaderIsNot($header, $string) {
    $this->assertSession()->responseHeaderNotEquals($header, $string);
  }

  /**
   * @Given the HTTP header :header contains :string
   */
  public function theHttpHeaderContains($header, $string) {
    $this->assertSession()->responseHeaderContains($header, $string);
  }

  /**
   * @Given the HTTP header :header does not contain :string
   */
  public function theHttpHeaderDoesNotContain($header, $string) {
    $this->assertSession()->responseHeaderNotContains($header, $string);
  }

  /**
   * @Given the HTTP header :header matches :string
   */
  public function theHttpHeaderMatches($header, $match) {
    $this->assertSession()->responseHeaderMatches($header, $match);
  }

  /**
   * @Given the HTTP header :header does not match :string
   */
  public function theHttpHeaderDoesNotMatch($header, $match) {
    $this->assertSession()->responseHeaderNotMatches($header, $match);
  }

  /**
   * @Given the HTTP header :header contain(s) the word(s) :words
   */
  public function theHttpHeaderContainsTheWord($header, $words) {
    foreach (explode(',', $words) as $word) {
      $word = trim($word);
      $match = "/(\s|^)$word(\,|\s|$)/";
      $this->assertSession()->responseHeaderMatches($header, $match);
    }
  }

  /**
   * @Given the HTTP header :header does not contain the word(s) :words
   */
  public function theHttpHeaderDoesNotContainTheWord($header, $words) {
    foreach (explode(',', $words) as $word) {
      $word = trim($word);
      $match = "/(\s|^)$word(\,|\s|$)/";
      $this->assertSession()->responseHeaderNotMatches($header, $match);
    }
  }

  /**
   * @Given the HTTP header :header contain(s) the prefixed word(s) :words
   */
  public function theHttpHeaderContainsThePrefixedWord($header, $words) {
    foreach (explode(',', $words) as $word) {
      $word = trim($word);
      $match = "/(\s|^)$word.*?(\,|\s|$)/";
      $this->assertSession()->responseHeaderMatches($header, $match);
    }
  }

  /**
   * @Given the HTTP header :header does not contain the prefixed word(s) :words
   */
  public function theHttpHeaderDoesNotContainThePrefixedWord($header, $words) {
    foreach (explode(',', $words) as $word) {
      $word = trim($word);
      $match = "/(\s|^)$word.*?(\,|\s|$)/";
      $this->assertSession()->responseHeaderNotMatches($header, $match);
    }
  }

  /**
   * @Then I can see there is a cache HIT at least in one of X-Cache, X-Drupal-Cache, X-Varnish-Cache
   */
  public function theCacheHitExists() {
    if ($this->getSession()->getResponseHeader("X-Cache") != "HIT" &&
        $this->getSession()->getResponseHeader("X-Drupal-Cache") != "HIT" &&
        $this->getSession()->getResponseHeader("X-Varnish-Cache") != "HIT") {
      $message = 'The text "HIT" was not found anywhere in the "X-Cache, X-Drupal-Cache" or "X-Varnish-Cache" response headers.';
      throw new ExpectationException($message, $this->getSession()->getDriver());
    }
  }

}
