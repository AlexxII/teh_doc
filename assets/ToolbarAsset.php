<?php

namespace app\assets;

use yii\web\AssetBundle;

class ToolbarAsset extends AssetBundle
{
  public $sourcePath = '@bower/toolbar';

  public $css = [
    'jquery.toolbars.css',
  ];

  public $js = [
    'jquery.toolbar.min.js',
  ];

}
