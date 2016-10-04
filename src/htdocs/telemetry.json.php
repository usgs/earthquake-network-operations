<?php

  /* use configuration variables */
  include_once '../conf/config.inc.php';
  include_once '../lib/classes/StationTelemetryFactory.class.php';
  include_once '../lib/classes/NetworkOperationsWebService.class.php';

  $stf = new StationTelemetryFactory($DB);
  $ws = new NetworkOperationsWebService($stf);
  $obj = $_GET;

  $ws->run($obj);
