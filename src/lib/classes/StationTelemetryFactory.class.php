<?php


class StationTelemetryFactory {

  // the database connection to use
  public $dsn;
  public $pass;
  public $pdo;
  public $telemetryQuery;
  public $user;

  public function __construct($dsn, $user, $pass) {
    $this->dsn = $dsn;
    $this->user = $user;
    $this->pass = $pass;

    $this->pdo = null;
    $this->telemetryQuery = null;
  }

  /**
   * Initiates the connection to the database if not already done.
   *
   * @return {StationTelemetryFactory}
   *     A reference to this factory for method chaining purposes.
   */
  protected function connect () {
    if ($this->pdo == null) {
      try {
        $this->pdo = new PDO($this->dsn, $this->user, $this->pass);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (Exception $ex) {
        $handled = new Exception('Failed to connect to the database');
        $handled->httpStatus = 500;
        throw $handled;
      }
    }

    return $this;
  }

  /**
   * Initiates the statements to execute if not already done.
   *
   * @return {StationTelemetryFactory}
   *     A reference to this factory for method chaining purposes.
   */
  protected function prepare () {
    if ($this->telemetryQuery == null) {
      // prepare statements
      $this->telemetryQuery = $this->pdo->prepare('
        SELECT
          id,
          unix_timestamp(updated) AS updated,
          network_code,
          station_code,
          telemetry
        FROM
          netops_station
        WHERE
          (network_code = :network OR :network is null)
          AND
          (station_code = :station OR :station is null)
      ');

      // set fetch mode
      $this->telemetryQuery->setFetchMode(PDO::FETCH_ASSOC);
    }

    return $this;
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
    // Make sure we are connected and ready to execute statements
    $this->connect()->prepare();

    // bind values
    $this->telemetryQuery->bindValue(':network', $network, PDO::PARAM_STR);
    $this->telemetryQuery->bindValue(':station', $station, PDO::PARAM_STR);

    try {
      $this->telemetryQuery->execute();
      $telemetrys = $this->telemetryQuery->fetchAll();
    } catch (Exception $ex) {
      // Don't throw the raw exception, instead, throw a handled one
      // so we get better output to the user
      $handled = new Exception('A database error occurred');
      $handled->httpStatus = 500;
      throw $handled;
    } finally {
      $this->telemetryQuery->closeCursor();
    }

    return $telemetrys;
  }

}
