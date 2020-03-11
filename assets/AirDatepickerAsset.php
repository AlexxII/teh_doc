<?php

namespace app\assets;

use yii\web\AssetBundle;

class AirDatepickerAsset extends AssetBundle
{
  public $sourcePath = '@bower/air-datepicker';

  public $css = [
    'dist/css/datepicker.min.css',
  ];

  public $js = [
    'dist/js/datepicker.min.js',
  ];

}
