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
$dsn = configure('Database administrator DSN', isset($CONFIG['DB_DSN']) ?
    $CONFIG['DB_DSN'] : 'driver:host=HOST;port=PORT;dbname=DBNAME');

// instantiate installer
$installer = new DatabaseInstaller($dsn, $username, $password);

// Add table/ load data
$installer->runScript($directory . 'install.sql');

print "Data loaded.\n";


// Create write user for database updates
if (!isset($CONFIG['DB_WRITE_USER']) || !$CONFIG['DB_WRITE_USER'] ||
    !isset($CONFIG['DB_WRITE_PASS']) || !$CONFIG['DB_WRITE_PASS']) {
  print "Database write user or password not configured, skipping create\n";
  exit(0);
}

$answer = promptYesNo('Would you like to create the write database user',
    false);

if (!$answer) {
  print "Normal exit.\n";
  exit(0);
}

$createUser = $installer->dbh->prepare(
    "CREATE USER :username@'%' IDENTIFIED BY :password");
try {
  $createUser->execute(array(
    ':username' => $CONFIG['DB_WRITE_USER'],
    ':password' => $CONFIG['DB_WRITE_PASS']
  ));
  print "Write user created\n";
} catch (Exception $e) {
  print "Exception creating write user\n";
  print_r($installer->dbh->errorInfo());
}
$createUser = null;

$grantUser = $installer->dbh->prepare(
    "GRANT SELECT,INSERT,UPDATE,DELETE ON netops_station TO :username@'%'");
try {
  $grantUser->execute(array(
    ':username' => $CONFIG['DB_WRITE_USER']
  ));
  print "Write user granted permissions\n";
} catch (Exception $e) {
  print "Exception granting permissions to write user\n";
  print_r($installer->dbh->errorInfo());
}
$grantUser = null;

?>
