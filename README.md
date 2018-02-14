# Behat Drupal Smoke

## Dependencies:

- Behat
- Drupal
- Drupal Extension
- Behat Driver with Javascript enabled (e.g. `dmore/behat-chrome-extension`)


## Overview:

The modules provides functionality for running smoke and login tests for drupal.

### Watchdog Catcher Context
    
You can extend the WatchdogCatcher Behat Context to add automatic watchdog error catching.
All watchdog entries excluding `Notice`, `Info` and `Debug` will be detected. 

### Example behat feature

You can find a sample behat feature for smoke testing in the examples directory.

## Credits
 
  Wolfgang Ziegler // fago
  
  Maximilian Götz-Mikus // maximilianmikus
  
  drunomics GmbH, hello@drunomics.com
  
