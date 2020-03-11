<?php

namespace app\assets;

use yii\web\AssetBundle;

class TetherAsset extends AssetBundle
{
  public $sourcePath = '@bower/tether';

  public $css = [
    'dist/css/tether.min.css',
  ];

  public $js = [
    'dist/js/tether.min.js',
  ];

}
