<?php
if (!isset($TEMPLATE)) {

  $TITLE = 'Network Operations Web Service';
  $HEAD = '
    <style>
      em {
        font-weight: normal;
        color: #999;
        font-style: italic;
      }
    </style>
  ';

  // If you want to include section navigation.
  // The nearest _navigation.inc.php file will be used by default
  $NAVIGATION = true;

  include 'template.inc.php';
}
?>

<p>This is an example page for the latency web service</p>

<h2>Query String Parameters</h2>
<p>Use the following query string parameters to test the webservice</p>
<dl>
  <dt>network <em>(optional)</em></dt>
  <dd>two character network code</dd>
  <dd><i>example: "US"</i></dd>
  <dt>station <em>(optional)</em></dt>
  <dd>four character station code</dd>
  <dd><i>example: "PKME"</i></dd>
</dl>

<h2>Example Requests</h2>
<ul>
  <li>
    <a href="service.php">All Stations</a>
  </li>
  <li>
    <a href="service.php?network=AT">Stations in Network "AT"</a>
  </li>
  <li>
    <a href="service.php?station=PKME">Station "PKME"</a>
  </li>
</ul>
