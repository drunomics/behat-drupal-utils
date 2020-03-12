<?php

namespace drunomics\BehatDrupalUtils\Context;

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
    try{
      $this->assertSession()->responseHeaderContains("X-Cache", "HIT");
    }catch (ExpectationException $exception){
      try {
        $this->assertSession()->responseHeaderContains("X-Drupal-Cache", "HIT");
      }catch (ExpectationException $exception1){
        $this->assertSession()->responseHeaderContains("X-Varnish-Cache", "HIT");
      }
    }
  }

}
