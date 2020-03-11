<?php

namespace app\assets;

use yii\web\AssetBundle;

class MomentJSAsset extends AssetBundle
{
  public $sourcePath = '@bower/moment';

  public $js = [
    'min/moment-with-locales.min.js',
  ];

}
