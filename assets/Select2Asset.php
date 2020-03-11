<?php

namespace app\assets;

use yii\web\AssetBundle;

class Select2Asset extends AssetBundle
{
  public $sourcePath = '@bower/select2';

  public $css = [
    'dist/css/select2.min.css',
  ];

  public $js = [
    'dist/js/select2.min.js',
  ];

}
