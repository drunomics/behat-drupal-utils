# Behat Drupal Smoke

## Dependencies:

- Behat
- Drupal
- Drupal Extension
- Behat Driver with Javascript enabled (e.g. `dmore/behat-chrome-extension`)


## Overview:

The modules provides functionality for running smoke and login tests for drupal.

### Install 

* Feel free to copy the example behat feature. 
* Add the provided js file to your sites js. The listener is required to catch js errors.
* Also add the DrupalSmokeContext to your behat.yml.


### Drupal Smoke Context
    
All watchdog entries excluding `Notice`, `Info` and `Debug` will be detected.

There are also some methods available in the Context:

 * iShouldBeRedirectedTo
 * iShouldSeeElementWithTheCssStylePropertyMatching
 * iShouldNotSeeAnyJavascriptErrorsInTheConsole

### Example behat feature

You can find a sample behat feature for smoke testing in the examples directory.

## Credits
 
  Wolfgang Ziegler // fago
  
  Maximilian Götz-Mikus // maximilianmikus
  
  drunomics GmbH, hello@drunomics.com
  
