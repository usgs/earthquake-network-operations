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
$answer = promptYesNo('Would you like to create the write database user',
    false);

if (!$answer) {
  print "Normal exit.\n";
  exit(0);
}

if (!$CONFIG['DB_WRITE_USER'] || !$CONFIG['DB_WRITE_PASS']) {
  print "Database write username and password cannot be empty\n";
  exit(1);
}

$createUser = $installer->dbh->prepare(
    "CREATE USER IF NOT EXISTS :username@'%' IDENTIFIED BY :password");
$createUser->execute(array(
  ':username' => $CONFIG['DB_WRITE_USER'],
  ':password' => $CONFIG['DB_WRITE_PASS']
));
$createUser = null;
print "Write user created\n";

$grantUser = $installer->dbh->prepare(
    "GRANT SELECT,INSERT,UPDATE,DELETE ON netops_station TO :username@'%'");
$grantUser->execute(array(
  ':username' => $CONFIG['DB_WRITE_USER']
));
$grantUser = null;
print "Write user granted permissions\n";

?>
