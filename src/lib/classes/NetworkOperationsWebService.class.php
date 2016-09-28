<?php

include_once 'NetworkOperationsQuery.class.php';

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
    $query = $this->parseQuery($params);
    // TODO, update getTelemetrys to accept a query object
    $results = $this->factory->getTelemetrys($query->network, $query->station);

    // print results
    header('Content-type: application/json');
    echo $this->safe_json_encode($results);
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
    $query = new NetworkOperationsQuery();

    // parse parameters
    foreach ($params as $name => $value) {
      if ($name == 'network') {
        $query->network = $value;
      } else if ($name == 'station') {
        $query->station = $value;
      } else {
        // throw exception for bad request
        throw new Exception('Bad Request: Unknown parameter "' . $name . '".');
      }
    }

    return $query;
  }

  /**
   * Safely json_encode values.
   *
   * Handles malformed UTF8 characters better than normal json_encode.
   * from http://stackoverflow.com/questions/10199017/how-to-solve-json-error-utf8-error-in-php-json-decode
   *
   * @param $value {Mixed}
   *        value to encode as json.
   * @return {String}
   *         json encoded value.
   * @throws Exception when unable to json encode.
   */
  public function safe_json_encode($value){
    $encoded = json_encode($value);
    $lastError = json_last_error();
    switch ($lastError) {
      case JSON_ERROR_NONE:
        return $encoded;
      case JSON_ERROR_UTF8:
        return safe_json_encode(utf8_encode_array($value));
      default:
        throw new Exception('json_encode error (' . $lastError . ')');
    }
  }


}
