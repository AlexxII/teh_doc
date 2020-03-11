<?php

namespace app\assets;

use yii\web\AssetBundle;

class OkaynavAsset extends AssetBundle
{
  public $sourcePath = 'lib/okaynav';

  public $css = [
    'css/okayNav.min.css',
  ];

  public $js = [
    'js/okayNav-min.js',
  ];

}
