<?php

namespace drunomics\BehatDrupalUtils\StepDefinition;

use Behat\Mink\Exception\ExpectationException;
use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Provides steps for operating with the javascript on the page.
 */
trait JavascriptUtilStepsTrait {

  /**
   * Gets the mink session.
   *
   * @param string $name
   *   (optional) The name of the session.
   *
   * @return \Behat\Mink\Session
   */
  abstract protected function getSession($name = NULL);

  /**
   * @When I trigger the :eventName event on the :selector element
   */
  public function triggerTheEventOnTheElement($eventName, $selector) {

    $event = <<<JS
      function triggerEvent(node, eventName) {
        // Make sure we use the ownerDocument from the provided node to avoid cross-window problems
        var doc = document;
    
        if (node.dispatchEvent) {
          // Gecko-style approach (now the standard) takes more work
          var eventClass = "";
    
          // Different events have different event classes.
          // If this switch statement can't map an eventName to an eventClass,
          // the event firing is going to fail.
          switch (eventName) {
            case "click": // Dispatching of 'click' appears to not work correctly in Safari. Use 'mousedown' or 'mouseup' instead.
            case "mousedown":
            case "mouseup":
              eventClass = "MouseEvents";
              break;
    
            case "focus":
            case "change":
            case "blur":
            case "select":
            case "input":
              eventClass = "HTMLEvents";
              break;
    
            default:
              throw "triggerEvent: Couldn't find an event class for event '" + eventName + "'.";
              break;
          }
          var event = doc.createEvent(eventClass);
          event.initEvent(eventName, true, true); // All events created as bubbling and cancelable.
    
          event.synthetic = true; // allow detection of synthetic events
          // The second parameter says go ahead with the default action
          node.dispatchEvent(event, true);
        } 
        else if (node.triggerEvent) {
          // IE-old school style, you can drop this if you don't need to support IE8 and lower
          var event = doc.createEventObject();
          event.synthetic = true; // allow detection of synthetic events
          node.triggerEvent("on" + eventName, event);
        }
      };
      
      var node = document.querySelector('{$selector}');
      triggerEvent(node, '{$eventName}');
  
JS;

    $this->getSession()
      ->evaluateScript($event);
  }

  /**
   * @When I trigger the :eventName event on the first link to be found on the page
   */
  public function triggerTheEventOnTheFirstLinkToBeFoundOnThePage($eventName) {

      $element = $this->getSession()->getPage()->find('xpath', '//a');

      if (empty($element)) {
        throw new ExpectationException(t('Could not find a link on the current page.'), $this->getSession());
      }

      $href = $element->getAttribute('href');
      $selector = 'a[href=' . $href . ']';
      $this->triggerTheEventOnTheElement($eventName, $selector);
  }

  /**
   * @Then /^I prepare window load event listener$/
   */
  public function beforeFeature() {
    $script = <<<JS
      window.is_completely_loaded = false;
      var loadEventHandler = function(e) {
        window.is_completely_loaded = true;
      }
      window.addEventListener('load', loadEventHandler);
JS;
    $this->getSession()
      ->evaluateScript($script);
  }

  /**
   * @Then /^I make sure the page is completely loaded$/
   */
  public function iMakeSurePageIsCompletelyLoaded() {
    $this->getSession()->wait(30000, "window.is_completely_loaded === true");
    $script = <<<JS
      window.removeEventListener("load", loadEventHandler);
      
JS;
    $this->getSession()
      ->evaluateScript($script);
  }

}
