<?php

namespace app\modules\maps\asset;

use yii\web\AssetBundle;

class LeafletAsset extends AssetBundle
{
  public $sourcePath = '@bower/leaflet';

  public $css = [
    'dist/leaflet.css',
  ];

  public $js = [
    'dist/leaflet.js'
  ];

  public $depends = [
  ];

}
