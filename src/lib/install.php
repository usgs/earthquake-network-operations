<?php
// This script prompts user if they would like to set up the latency schema
//
// (1) Create the schema
// (2) Load reference data into the database.
//
// Note: If the user declines any step along the way this script is complete.

$directory = getcwd() . '/sql/';

// Remove the database
$answer = configure('DO_SCHEMA_LOAD', 'Y',
    "\nWould you like to load the data for this application");

if (!responseIsAffirmative($answer)) {
  print "Normal exit.\n";
  exit(0);
}

// Setup root DSN
$username = configure('DB_ROOT_USER', 'root', "\nDatabase adminitrator user");
$password = configure('DB_ROOT_PASS', '', "Database administrator password",
    true);
$installer = DatabaseInstaller::getInstaller($CONFIG['DB_DSN'], $username, $password);

// Add table/ load data
$installer->runScript($directory . 'install.sql');

?>