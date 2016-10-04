<?php
// This script prompts user if they would like to set up the latency schema
//
// (1) Create the schema
// (2) Load reference data into the database.
//
// Note: If the user declines any step along the way this script is complete.

include_once 'classes/DatabaseInstaller.class.php';

$directory = getcwd() . '/sql/';

// Load data into database
$answer = promptYesNo('Would you like to load the data for this application',
    false);

if (!$answer) {
  print "Normal exit.\n";
  exit(0);
}

// Setup root DSN
$username = configure('Database adminitrator user', 'root');
$password = configure('Database administrator password', '', true);
$dsn = configure('Database administrator DSN',
    'driver:host=HOST;port=PORT;dbname=DBNAME');

// instantiate installer
$installer = new DatabaseInstaller($dsn, $username, $password);

// Add table/ load data
$installer->runScript($directory . 'install.sql');

print "Data loaded.\n";

?>
