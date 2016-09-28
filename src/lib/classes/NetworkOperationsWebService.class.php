<?php


class NetworkOperationsWebService {

  // the factory to use
  public $factory;

  public function __construct($factory = null) {
    if (!$factory) {
      throw new Exception('No Factory provided');
    }

    $this->factory = $factory;
  }

  /**
   * Requests telemetry data from the StationTelemetryFactory
   *
   * @param params {Object}
   *        query parameters
   *
   * @return [type]
   */
  public function run ($params = null) {
    $query = $this->parseQuery();
  }

  /**
   * Parses arguments to the run method
   *
   * @param params {Object}
   *        query parameters
   *
   * @return [type]
   */
  public function parseQuery($params = null) {
    $params = $_GET;

    return $params;
  }

}
