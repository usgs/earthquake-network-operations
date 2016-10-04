'use strict';


var prefix;

prefix = window.location.toString().replace('telemetry.html', '');


Array.prototype.forEach.call(document.querySelectorAll('.example-request'),
  function (el) {
    el.innerHTML = prefix + el.innerHTML;
  }
);
