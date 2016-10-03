<?php

  /* use configuration variables */
  include_once '../src/conf/config.inc.php';
  include_once '../src/lib/classes/StationTelemetryFactory.class.php';
  include_once '../src/lib/classes/NetworkOperationsWebService.class.php';

  $stf = new StationTelemetryFactory($DB);
  $ws = new NetworkOperationsWebService($stf);
  $obj = $_GET;

  $ws->run($obj);

?>