<?php

use app\modules\maps\asset\LeafletAsset;

LeafletAsset::register($this);

?>
<style>
  body {
    padding: 0;
    margin: 0;
  }

  html, body, #map {
    height: 100%;
    width: 100%;
  }

  /*#map {*/
  /*width: 100%;*/
  /*height: 820px;*/
  /*}*/
</style>

<div id="map">

</div>

<script>
  $(document).ready(function () {

    var map = L.map('map').setView([68.959, 33.061], 12);

    L.tileLayer('http://182.11.57.17/osm_tiles/{z}/{x}/{y}.png', {
      attribution: '&copy; ' + 'СпецСвязь ФСО России',
      maxZoom: 18
    }).addTo(map);

  });
</script>