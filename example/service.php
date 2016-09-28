<?php

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
  include_once '../src/lib/classes/NetworkOperationsWebService.class.php';

  $stf = new StationTelemetryFactory($dbh);
  $ws = new NetworkOperationsWebService($stf);
  $obj = $_GET;

  $ws->run($obj);

?>