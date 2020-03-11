<?php

namespace app\assets;

use yii\web\AssetBundle;

class FotoramaAsset extends AssetBundle
{
  public $sourcePath = '@bower/fotorama';

  public $css = [
    'fotorama.css',
  ];

  public $js = [
    'fotorama.js',
  ];

}
