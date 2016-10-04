<?php

include_once 'NetworkOperationsQuery.class.php';

class NetworkOperationsWebService {

  // the factory to use
  public $factory;

  // Maybe not complete, but good enough for now
  protected static $HTTP_CODES = array(
      '200' => 'HTTP/1.0 200 OK',
      '400' => 'HTTP/1.0 400 Bad Request',
      '403' => 'HTTP/1.0 403 Not Authorized',
      '404' => 'HTTP/1.0 404 Not Found',
      '500' => 'HTTP/1.0 500 Internal Server Error'
    );

  protected static $CACHE_MAXAGE = 300; // seconds
  protected static $DATE_FORMAT = 'D, d M Y H:i:s \G\M\T';

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
    try {
      header('Content-type: application/json');

      $query = $this->parseQuery($params);
      $results = $this->factory->getTelemetrys(
          $query->network, $query->station);
      $output = array();

      for ($i = 0; $i < count($results); $i++) {
        array_push($output, $this->format_station_geojson($results[$i]));
      }

      $json = array(
        'type' => 'FeatureCollection',
        'metadata' => array(
          'status' => 'success'
        ),
        'features' => $output
      );

      // caching headers
      $now = time();
      header('Cache-Control: public, max-age=' . self::$CACHE_MAXAGE);
      header('Expires: ' .
          gmdate(self::$DATE_FORMAT, $now + self::$CACHE_MAXAGE));
      header('Last-Modified: ' . gmdate(self::$DATE_FORMAT, $now));

      // print results
      echo $this->safe_json_encode($json);
    } catch (Exception $ex) {
      if (isset($ex->httpStatus) &&
          isset(self::$HTTP_CODES[$ex->httpStatus])) {

        header(self::$HTTP_CODES[$ex->httpStatus]);
        $message = $ex->getMessage();
      } else {
        // Unclear what happened, use generic error.
        header(self::$HTTP_CODES['500']);

        // Not a purposefully thrown error, could be DB connection issues
        // etc... and do not want to leak connection info in an error string,
        // so instead, just provide a generic message
        $message = 'An unexpected error occurred';
      }

      echo $this->safe_json_encode(array(
        'type' => 'FeatureCollection',
        'metadata' => array(
          'status' => 'error',
          'error' => $message
        ),
        'features' => null
      ));
    }
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
        $ex = new Exception('Unknown parameter: ' . $name);
        $ex->httpStatus = 400;
        throw $ex;
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
        $ex = new Exception('An error occurred encoding the result');
        $ex->httpStatus = 500;
        throw $ex;
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
    if (!$result) {
      return null;
    }

    $response = array(
      'type' => 'Feature',
      'id' => $result['network_code'] . "_" . $result['station_code'],
      'geometry' => null,
      'properties' => array(
        'network_code' => $result['network_code'],
        'station_code' => $result['station_code'],
        'telemetry' => (integer) $result['telemetry'],
        'updated' => date('c', (integer) $result['updated'])
      )
    );

    return $response;
  }

}
