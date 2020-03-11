<?php

namespace app\assets;

use yii\web\AssetBundle;

class SlidebarsAsset extends AssetBundle
{
  public $sourcePath = '@bower/slidebars';

  public $css = [
    'dist/slidebars.min.css',
  ];

  public $js = [
    'dist/slidebars.min.js',
  ];

}
