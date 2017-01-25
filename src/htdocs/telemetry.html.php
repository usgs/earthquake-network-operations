<?php
if (!isset($TEMPLATE)) {
  $TITLE = 'Telemetry Documentation';

  $NAVIGATION = true;

  $HEAD = '
    <link rel="stylesheet" href="css/telemetry.css"/>
  ';

  $FOOT = '
    <script src="js/telemetry.js"></script>
  ';

  include_once 'template.inc.php';
}
?>

<h2>Request Parameters</h2>
<dl>
  <dt><code>network</code></dt>
  <dd class="parameter-type">String</dd>
  <dd class="parameter-description">
    The network code for the station
  </dd>

  <dt><code>station</code></dt>
  <dd class="parameter-type">String</dd>
  <dd class="parameter-description">
    The station code for the station
  </dd>
</dl>

<p class="alert info">
  Note that neither <code>network</code> nor <code>station</code> are strictly
  required in order to receive results. If neither are provided, then results
  are returned for all stations in all networks. If only <code>network</code>
  is provided, then results are returned for all stations within that network.
</p>


<h2>Output</h2>
<p>
  Results are formatted as a <a
    href="http://geojson.org/geojson-spec.html#feature-collection-objects">
  GeoJSON FeatureCollection</a>. Additionally a metadata property is provided
  to inspect the status of the request. An error message is provided in the
  metadata if one occurred. If an error occurs, the <code>features</code>
  array will be <code>null</code>.
</p>

<pre><code>{
  "type": "FeatureCollection",
  "metadata": {
    "status": <i>&lt;String&gt; - Either &ldquo;success&rdquo; or &ldquo;error&rdquo;</i>
    "error": <i>&lt;String&gt; - A desciption of the error if one occurred</i>
  }
  "features": [
    {
      "type": "Feature",
      "id": <i>&lt;String&gt; - The ID for this station</i>
      "geometry": null,
      "properties": {
        "network_code": <i>&lt;String&gt; - The network code for this station</i>
        "station_code": <i>&lt;String&gt; - The station code for this station</i>
        "telemetry": <i>&lt;Integer&gt; - The telemetry status for this station</i>
        "updated": <i>&lt;String&gt; - ISO8601 timestamp when telemetry was last updated</i>
      }
    }
    ...
  ]
}</code></pre>

<h2>Example</h2>

<h3>Request</h3>
<pre><code><a class="example-request"
    href="./telemetry.json?network=AK&amp;station=BMR"
  >telemetry.json?network=AK&amp;station=BMR</a></code></pre>

<h3>Response</h3>
<pre><code>{
  "type": "FeatureCollection",
  "metadata": {
    "status": "success"
  },
  "features": [
    {
      "type": "Feature",
      "id": "AK_BMR",
      "geometry": null,
      "properties": {
        "network_code": "AK",
        "station_code": "BMR",
        "telemetry": 2,
        "updated": "2016-09-12T22:04:43+00:00"
      }
    }
  ]
}</code></pre>
