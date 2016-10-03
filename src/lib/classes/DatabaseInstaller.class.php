<?php

/**
 * Base class for database installer.
 */
class DatabaseInstaller {

  // PDO handle
  protected $dbh = null;
  // PDO url
  protected $url;
  // PDO user
  protected $user;
  // PDO password
  protected $pass;


  /**
   * Constructor, called by subclasses.
   *
   * @param $url {String}
   *        PDO url.
   * @param $user {$tring}
   *        DB username.
   * @param $pass {String}
   *        DB password.
   */
  public function __construct($url, $user, $pass) {
    $this->url = $url;
    $this->user = $user;
    $this->pass = $pass;
  }

  /**
   * Connect to the database.
   *
   * @return {PDO} PDO connection with exception mode.
   */
  public function connect () {
    if ($this->dbh === null) {
      $this->dbh = new PDO($this->url, $this->user, $this->pass);
      $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    return $this->dbh;
  }

  /**
   * Run one or more sql statements.
   *
   * Removes c-style comments before execution.
   *
   * @param $statements {String}
   *        semi-colon delimited list of statements to execute.
   */
  public function run ($statements) {
    // make sure connected
    $dbh = $this->connect();

    // Remove /* */ comments
    $statements = preg_replace('#/\*.*\*/#', '', $statements);
    // split on semicolons that are outside of single quotes
    // http://stackoverflow.com/questions/21105360/regex-find-comma-not-inside-quotes
    $statements = preg_split("/(?!\B'[^']*);(?![^']*'\B)/", $statements);

    foreach ($statements as $sql) {
      $sql = trim($sql);
      if ($sql !== '') {
        try {
          $this->dbh->exec($sql);
        } catch (Exception $e) {
          echo 'SQL Exception: ' . $e->getMessage() . PHP_EOL .
              'While running:' . PHP_EOL . $sql . PHP_EOL;
          throw $e;
        }
      }
    }
    $dbh = null;
  }

  /**
   * Run sql statements from a file.
   *
   * Same as $this->run(file_get_contents($file)).
   *
   * @param $file {String}
   *        path to sql script.
   */
  public function runScript ($file) {
    $this->run(file_get_contents($file));
  }

}
