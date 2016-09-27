<?php


class StationTelemetryFactory {

  // the database connection to use
  public $pdo;

  public function __construct($pdo=null) {
    $this->pdo = $pdo;
  }

  public function getTelemetrys() {

  }

}
