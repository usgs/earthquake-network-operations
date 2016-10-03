<?php
  /**
   * Prompts user for a configuration $option and returns the resulting input.
   *
   * @param $option {String}
   *      The name of the option to configure.
   * @param $default {String} Optional, default: <none>
   *      The default value to use if no answer is given.
   * @param $comment {String} Optional, default: $option
   *      Help text used when prompting the user. Also used as a comment in
   *      the configuration file.
   * @param $secure {Boolean} Optional, default: false
   *      True if user input should not be echo'd back to the screen as it
   *      is entered. Useful for passwords.
   * @param $unknown {Boolean} Optional, default: false
   *      True if the configuration option is not a well-known option and
   *      a warning should be printed.
   *
   * @return {String}
   *      The configured value for the requested option.
   */
  function configure ($option, $default=null, $comment='', $secure=false,
      $unknown=false) {

    if (NON_INTERACTIVE) {
      return $default;
    }

    // check if windows
    static $isWindows = null;
    if ($isWindows === null) {
      $isWindows = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
    }

    if ($unknown) {
      // Warn user about an unknown configuration option being used.
      print "\nThis next option ($option) is an unknown configuration" .
          " option, which may mean it has been deprecated or removed.\n\n";
    }

    // Make sure we have good values for I/O.
    $help = ($comment !== null && $comment !== '') ? $comment : $option;

    // Prompt for and read the configuration option value
    printf("%s [%s]: ", $help, ($default === null ? '<none>' : $default));
    if ($secure && !$isWindows) {system('stty -echo');}
    $value = trim(fgets(STDIN));
    if ($secure && !$isWindows) {system('stty echo'); print "\n";}

    // Check the input
    if ($value === '' && $default !== null) {
      $value = $default;
    }

    // Always return the value
    return $value;
  }


  // UTILITY FUNCTIONS
  /**
   * Prompt user with a yes or no question.
   *
   * @param $prompt {String}
   *        yes or no question, should include question mark if desired.
   * @param $default {Boolean}
   *        default null (user must enter y or n).
   *        true for yes to be default answer, false for no.
   *        default answer is used when user presses enter with no other input.
   * @return {Boolean} true if user entered yes, false if user entered no.
   */
  function promptYesNo ($prompt='Yes or no?', $default=null) {
    $question = $prompt . ' [' .
        ($default === true ? 'Y' : 'y') . '/' .
        ($default === false ? 'N' : 'n') . ']: ';
    $answer = null;

    if (NON_INTERACTIVE) {
      return $default;
    }

    while ($answer === null) {
      echo $question;
      $answer = strtoupper(trim(fgets(STDIN)));
      if ($answer === '') {
        if ($default === true) {
          $answer = 'Y';
        } else if ($default === false) {
          $answer = 'N';
        }
      }
      if ($answer !== 'Y' && $answer !== 'N') {
        $answer = null;
        echo PHP_EOL;
      }
    }
    return ($answer === 'Y');
  }


  /**
   * Checks if the given response seems to be in the affirmative.
   *
   * @param response {String}
   *        The input response.
   * @return True if the response seems to be affirmative. False otherwise.
   */
  function responseIsAffirmative ($response) {
    return ($response === 'Y' || $response === 'y' || $response === 'yes' ||
        $response === 'Yes' || $response === 'YES');
  }

?>
