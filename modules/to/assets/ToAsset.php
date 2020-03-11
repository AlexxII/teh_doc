<?php

namespace app\modules\to\assets;

use yii\web\AssetBundle;

class ToAsset extends AssetBundle
{

  public $sourcePath = '@app/modules/to/lib';

  public $css = [
    'css/to_style.css'
  ];

  public $js = [
    'js/to.js',
    'js/default_page.js'
  ];

}
