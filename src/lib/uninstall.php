<?php

// read in DSN
include_once '../conf/config.inc.php';
include_once './install-funcs.inc.php';
include_once 'classes/DatabaseInstaller.class.php';

define('NON_INTERACTIVE', false);

$directory = getcwd() . '/sql/';

// Remove the data
$answer = promptYesNo("Would you like to remove the data for this application",
    false);

if (!$answer) {
  print "Normal exit.\n";
  exit(0);
}

// Setup root DSN
$username = configure("Database adminitrator user", 'root');
$password = configure("Database administrator password", '', true);
$dsn = configure('Database administrator DSN', isset($CONFIG['DB_DSN']) ?
    $CONFIG['DB_DSN'] : 'driver:host=HOST;port=PORT;dbname=DBNAME');

// instantiate installer
$installer = new DatabaseInstaller($dsn, $username, $password);

// Drop table
$installer->runScript($directory . 'uninstall.sql');

print "Data removed.\n";


// Remove the write user
if (!isset($CONFIG['DB_WRITE_USER']) || !$CONFIG['DB_WRITE_USER']) {
  print "No database write user configured, skipping remove\n";
  exit(0);
}

$answer = promptYesNo("Would you like to remove the database write user " .
    "(" . $CONFIG['DB_WRITE_USER'] . ")",
    false);

if (!$answer) {
  print "Normal exit.\n";
  exit(0);
}

$dropUser = $installer->dbh->prepare(
    "DROP USER :username");
$dropUser->execute(array(
  ':username' => $CONFIG['DB_WRITE_USER']
));
$dropUser = null;
print "Write user removed\n";

?>
