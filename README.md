# drunomics Behat Drupal utils

## Dependencies:

- Behat
- Drupal
- Drupal Extension
- Behat Driver with Javascript enabled (e.g. `dmore/behat-chrome-extension`)

The javascript step definitions are all tested with `dmore/behat-chrome-extension`.

## Overview:

The package provides:

* Various useful behat contexts, organized the following context classes:
  - DrupalUtilsApiContext
  - DrupalUtilsDrushContext
  - MinkUtilsContext
  - HttpHeadersContext

  Depending on your behat configuration, the suiting context classes should be added in. It automatically includes
  all compatible step definitions.
* Optional additional context classes that add automatic checks or cleanup routines like:
  - DrupalCleanTestContentApiContext
  - DrupalErrorCheckApiContext

## Smoke tests

The tests below ./examples/ ensure a simple Drupal login/logout works and a page of your site can be opened, while
frontend assets are loaded and no javascript or watchdog errors are triggered.

### Setup

* Add this package to your project's dev dependencies.
* Add one of the three context to your behat.yml, optionall also add optional contexts.
* Feel free to copy the example behat features.
* Add the provided js file to your sites js. The listener is required to catch js errors.

### Detecting watchdog errors
##### Default behaviour

When DrupalErrorCheckApiContext is added, all watchdog entries excluding `Notice`, `Info` and `Debug` will be detected
and trigger PHP errors in the behat PHP runner automatically.

##### Adjusting watchdog entries severity

To change the error detection severity set the `severity_level` parameter in the behat.yml to the desired level as an int value.
See `RfcLogLevel` for all possible levels.

Example setting severity level to `RfcLogLevel::NOTICE`:
```
contexts:
  - drunomics\BehatDrupalUtils\Context\DrupalErrorCheckApiContext:
      severity_level: 5
```

## Credits
 
  developed by drunomics GmbH, hello@drunomics.com
  Please refer to the commit log individual contributors.  
