<?php

namespace app\modules\maps\asset;

use yii\web\AssetBundle;

class LeafletClusterAsset extends AssetBundle
{
  public $sourcePath = '@bower/leaflet.markercluster';

  public $css = [
    'dist/MarkerCluster.css',
    'dist/MarkerCluster.Default.css',
  ];

  public $js = [
    'dist/leaflet.markercluster-src.js'
  ];

  public $depends = [
  ];

}
