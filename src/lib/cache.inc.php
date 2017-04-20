<?php
/**
 * Handles caching headers.
 * $MODIFIED - when the page was last modified, default is time().
 * $CACHE_MAXAGE - maximum age in seconds, default is 900 (15 minutes).
 */

if (!defined('RFC_DATE')) {
  define('RFC_DATE', 'D, d M Y H:i:s \G\M\T');
}

// set defaults
if (!isset($MODIFIED)) {
  $MODIFIED = time();
}

if (!isset($CACHE_MAXAGE)) {
  $CACHE_MAXAGE = 900;
}

if ($CACHE_MAXAGE >= 0) {
  header('Cache-Control: public, max-age=' . $CACHE_MAXAGE);
  header('Expires: ' . gmdate(RFC_DATE, time() + $CACHE_MAXAGE));
}

header('Last-Modified: ' . gmdate(RFC_DATE, $MODIFIED));
?>
