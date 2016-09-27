<?php
if (!isset($TEMPLATE)) {

  $TITLE = 'Station Telemetry Factory Index';

  // If you want to include section navigation.
  // The nearest _navigation.inc.php file will be used by default
  $NAVIGATION = true;

  include 'template.inc.php';
}

/* use configuration variables */
include_once '../src/conf/config.inc.php';

$dsn = 'mysql:dbname=' . $CONFIG['DB_NAME'] . ';host=' . $CONFIG['DB_HOST'] . '';
$user = $CONFIG['DB_USER'];
$password = $CONFIG['DB_PASS'];

try {
    $dbh = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

include_once '../src/lib/classes/StationTelemetryFactory.class.php';

$stf = new StationTelemetryFactory($dbh);

print '<pre>';
print_r($stf->getTelemetrys());
print '</pre>';

?>
