<?php

  /* use configuration variables */
  include_once '../conf/config.inc.php';
  include_once '../lib/classes/StationTelemetryFactory.class.php';
  include_once '../lib/classes/NetworkOperationsWebService.class.php';

  $stf = new StationTelemetryFactory($CONFIG['DB_DSN'],
      $CONFIG['DB_USER'], $CONFIG['DB_PASS']);
  $ws = new NetworkOperationsWebService($stf);
  $obj = $_GET;

  $ws->run($obj);
