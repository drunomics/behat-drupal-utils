// Add error listener for behat as suggested here: https://github.com/minkphp/MinkSelenium2Driver/issues/189
window.behat_testing = {
  errors: [],
};
window.onerror = function logErr(error, url, line) {
  window.behat_testing.errors.push(`${error} Line: ${line}`);
};
