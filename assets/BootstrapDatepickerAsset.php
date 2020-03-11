<?php

namespace app\assets;

use yii\web\AssetBundle;

class BootstrapDatepickerAsset extends AssetBundle
{
  public $sourcePath = '@bower/bootstrap-datepicker';

  public $css = [
    'dist/css/bootstrap-datepicker3.min.css',
  ];

  public $js = [
    'dist/js/bootstrap-datepicker.min.js',
    'dist/locales/bootstrap-datepicker.ru.min.js'
  ];

}
