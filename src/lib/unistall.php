<?php

// read in DSN
include_once 'conf/config.inc.php';

$directory = getcwd() . '/sql/';

// Remove the database
$answer = configure('DO_SCHEMA_LOAD', 'Y',
    "\nWould you like to remove the data for this application");

if (!responseIsAffirmative($answer)) {
  print "Normal exit.\n";
  exit(0);
}

$answer = configure('CONFIRM_DO_SCHEMA_LOAD', 'N',
    "\nRemoving the data removes any existing schema and/or data.\n" .
    'Are you sure you wish to continue');

if (!responseIsAffirmative($answer)) {
  print "\nNormal exit.\n";
  exit(0);
}

// Setup root DSN
$username = configure('DB_ROOT_USER', 'root', "\nDatabase adminitrator user");
$password = configure('DB_ROOT_PASS', '', "Database administrator password",
    true);
$installer = DatabaseInstaller::getInstaller($CONFIG['DB_DSN'], $username, $password);

// Drop table
$installer->runScript($directory . 'uninstall.sql');

?>
