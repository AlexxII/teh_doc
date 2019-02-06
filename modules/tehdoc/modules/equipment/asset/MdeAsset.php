<?php

namespace app\modules\tehdoc\modules\equipment\asset;

use yii\web\AssetBundle;

class MdeAsset extends AssetBundle
{
  public $sourcePath = '@bower/simplemde';

  public $css = [
    'dist/simplemde.min.css',
  ];

  public $js = [
    'dist/simplemde.min.js',
  ];

  public $depends = [
    'yii\web\YiiAsset',
  ];
}
