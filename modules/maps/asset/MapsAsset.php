<?php

namespace app\modules\maps\asset;

use yii\web\AssetBundle;

class MapsAsset extends AssetBundle
{
  public $sourcePath = '@app/modules/maps/lib';

  public $css = [
    'css/maps_style.css',
  ];

  public $js = [
    'js/maps.js'
  ];

  public $depends = [
  ];

}
