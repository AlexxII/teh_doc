<?php

namespace app\assets;

use yii\web\AssetBundle;

class JConfirmAsset extends AssetBundle
{
  public $sourcePath = '@bower/jquery-confirm2';

  public $css = [
    'dist/jquery-confirm.min.css',
  ];

  public $js = [
    'dist/jquery-confirm.min.js',
  ];

}
