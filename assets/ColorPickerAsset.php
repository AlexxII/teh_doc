<?php

namespace app\assets;

use yii\web\AssetBundle;

class ColorPickerAsset extends AssetBundle
{
  public $sourcePath = '@bower/jquery-simplecolorpicker';

  public $css = [
    'jquery.simplecolorpicker.css',
    'jquery.simplecolorpicker-glyphicons.css',
  ];

  public $js = [
    'jquery.simplecolorpicker.js',
  ];

}
