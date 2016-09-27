<?php


class StationTelemetryFactory {

  // the database connection to use
  public $pdo;

  public function __construct($pdo=null) {
    if (!$pdo) {
      throw new Exception('PDO connection is not configured');
    }
    $this->pdo = $pdo;
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
    $statement = $this->pdo->prepare(
      'SELECT * ' .
      'FROM netops_station ' .
      'WHERE (network_code = :network OR :network is null) '.
      'AND (station_code = :station OR :station is null)'
    );
    $statement->bindValue(':network', $network, PDO::PARAM_STR);
    $statement->bindValue(':station', $station, PDO::PARAM_STR);

    if ($statement->execute() === FALSE) {
      // something went wrong
      $errorInfo = $statement->errorInfo();
      throw new Exception($errorInfo[2]);
    } else {
      $telemetrys = $statement->fetchAll(PDO::FETCH_ASSOC);
      $statement->closeCursor();
    }

    return $telemetrys;
  }

}
