<?php

// read in DSN
include_once '../conf/config.inc.php';
include_once './install-funcs.inc.php';
include_once 'classes/DatabaseInstaller.class.php';

define('NON_INTERACTIVE', false);

$directory = getcwd() . '/sql/';

// Remove the data
$answer = promptYesNo("Would you like to remove the data for this application",
    true);

if (!$answer) {
  print "Normal exit.\n";
  exit(0);
}

// Setup root DSN
$username = configure("Database adminitrator user", 'root');
$password = configure("Database administrator password", '', true);

// instantiate installer
$installer = new DatabaseInstaller($CONFIG['DB_DSN'], $username, $password);

// Drop table
$installer->runScript($directory . 'uninstall.sql');

print "Data removed.\n";

?>
