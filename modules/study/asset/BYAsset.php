<?php

namespace app\modules\study\asset;

use yii\web\AssetBundle;

class BYAsset extends AssetBundle
{
  public $sourcePath = '@app/modules/study/lib';

  public $css = [
    'css/by-calendar.css',
  ];

  public $js = [
    'js/by-calendar.js',
    'js/languages/by-calendar.ru.js'
  ];

  public $depends = [
    'app\assets\AppAsset'
  ];

}
