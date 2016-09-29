<?php

include_once 'NetworkOperationsQuery.class.php';

class NetworkOperationsWebService {

  // the factory to use
  public $factory;

  public function __construct ($factory = null) {
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
    $output = array();

    for($i = 0; $i < count($results); $i++) {
      array_push($output, $this->format_station_geojson($results[$i]));
    }

    // print results
    header('Content-type: application/json');
    echo $this->safe_json_encode($output);
  }

  /**
   * Parses arguments to the run method
   *
   * @param params {Object}
   *        query parameters
   *
   * @return [type]
   */
  public function parseQuery ($params = null) {
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
  public function safe_json_encode ($value){
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

  /**
   * Formats each StationTelemetryFactory result into a geojson like object
   *
   * @param result {Array}
   *        A station row from StationTelemetryFactory->getTelemetrys()
   * @return {Array}
   *        A geojson like object with station data
   */
  public function format_station_geojson ($result) {
    if (!value) {
      return;
    }

    $response = array(
      'type' => 'Feature',
      'id' => $result['network_code'] . "_" . $result['station_code'],
      'geometry' => array(
        'type' => 'Point',
        'coordinates' => array(
          $result['latitude'],
          $result['longitude'],
          $result['elevation']
        )
      ),
      'properties' => array(
        'accelerometer' => $result['accelerometer'],
        'broadband' => $result['broadband'],
        'datalogger' => $result['datalogger'],
        'host' => $result['host'],
        'name' => $result['name'],
        'network_code' => $result['network_code'],
        'station_code' => $result['station_code'],
        'virtual_networks' => array(
          'ANSS',
          'GSN'
        )
      )
    );

    return $response;
  }

}
