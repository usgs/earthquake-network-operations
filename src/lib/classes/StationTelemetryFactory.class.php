<?php


class StationTelemetryFactory {

  // the database connection to use
  public $pdo;
  public $telemetryQuery;

  public function __construct($pdo = null) {
    if (!$pdo) {
      throw new Exception('PDO connection is not configured');
    }

    // set pdo connection
    $this->pdo = $pdo;

    // prepare statements
    $this->telemetryQuery = $this->pdo->prepare(
      'SELECT * ' .
      'FROM netops_station ' .
      'WHERE (network_code = :network OR :network is null) '.
      'AND (station_code = :station OR :station is null)'
    );

    // set fetch mode
    $this->telemetryQuery->setFetchMode(PDO::FETCH_ASSOC);
  }

  /**
   * Get telemetry data.
   *
   * Returns all stations if no params are specified,
   * otherwise only matching networks and stations are returned.
   *
   * @param network {String}
   *      The network code
   * @param station {String}
   *      The station code
   *
   * @return {Array}
   *      An array of telemetry data
   */
  public function getTelemetrys($network = null, $station = null) {
    // bind values
    $this->telemetryQuery->bindValue(':network', $network, PDO::PARAM_STR);
    $this->telemetryQuery->bindValue(':station', $station, PDO::PARAM_STR);

    try {
      $this->telemetryQuery->execute();
      $telemetrys = $this->telemetryQuery->fetchAll();
    } catch (Exception $e) {
      print $this->telemetryQuery->errorInfo();
    } finally {
      $this->telemetryQuery->closeCursor();
    }

    return $telemetrys;
  }

}
