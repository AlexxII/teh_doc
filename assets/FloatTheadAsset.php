<?php

namespace app\assets;

use yii\web\AssetBundle;

class FloatTheadAsset extends AssetBundle
{
  public $sourcePath = '@bower/floatthead';

  public $js = [
    'dist/jquery.floatThead.min.js',
  ];

}
