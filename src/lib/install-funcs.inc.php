<?php
  /**
   * Prompts user for a configuration $option and returns the resulting input.
   *
   * @param $prompt {String} Optional, default: $option
   *      Help text used when prompting the user. Also used as a comment in
   *      the configuration file.
   * @param $default {String} Optional, default: <none>
   *      The default value to use if no answer is given.
   * @param $secure {Boolean} Optional, default: false
   *      True if user input should not be echo'd back to the screen as it
   *      is entered. Useful for passwords.
   *
   * @return {String}
   *      The configured value for the requested option.
   */
  function configure ($prompt, $default = null, $secure = false) {

    echo $prompt;
    if ($default != null) {
      echo ' [' . $default . ']';
    }
    echo ': ';

    if (NON_INTERACTIVE) {
      // non-interactive
      echo '(Non-interactive, using default)' . PHP_EOL;
      return $default;
    }

    if ($secure) {
      system('stty -echo');
      $answer = trim(fgets(STDIN));
      system('stty echo');
      echo "\n";
    } else {
      $answer = trim(fgets(STDIN));
    }

    if ($answer == '') {
      $answer = $default;
    }

    return $answer;
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
