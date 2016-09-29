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
include_once '../src/lib/classes/StationTelemetryFactory.class.php';

$stf = new StationTelemetryFactory($DB);

print '<pre>';
print_r($stf->getTelemetrys());
print '</pre>';

?>
