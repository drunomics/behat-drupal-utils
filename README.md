# drunomics Behat Drupal utils

## Dependencies:

- Behat
- Drupal
- Drupal Extension
- Behat Driver with Javascript enabled (e.g. `dmore/behat-chrome-extension`)


## Overview:

The package provides:

* Various useful behat contexts
* Helpers for running behat smoke tests

## Smoke tests

The package ensures a simple Drupal login/logout works and a page of your site can be opened, while frontend assets
are loaded and no javascript or watchdog errors are triggered.

### Setup

* Add this package to your project's dev dependencies.
* Feel free to copy the example behat feature.
* Add the provided js file to your sites js. The listener is required to catch js errors.
* Also add the DrupalSmokeContext to your behat.yml.


### Detecting watchdog errors
    
All watchdog entries excluding `Notice`, `Info` and `Debug` will be detected and trigger PHP errors in the behat
PHP runner automatically.

### Helpers

There are also some methods available in the smoke context:

 * iShouldBeRedirectedTo
 * iShouldSeeElementWithTheCssStylePropertyMatching
 * iShouldNotSeeAnyJavascriptErrorsInTheConsole

### Example behat feature

You can find a sample behat feature for smoke testing in the examples directory.

## Credits
 
  Wolfgang Ziegler // fago  
  Maximilian Götz-Mikus // maximilianmikus  
  Jeremy Chinquist // jjchinquist  
  Arthur Lorenz // arthur_lorenz  
  drunomics GmbH, hello@drunomics.com  
  
