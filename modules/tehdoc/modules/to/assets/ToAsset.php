<?php

namespace app\modules\tehdoc\modules\to\assets;

use yii\web\AssetBundle;

class ToAsset extends AssetBundle
{

  public $sourcePath = '@app/modules/tehdoc/modules/to/lib';

  public $css = [
    'css/bootstrap-datepicker.min.css',
  ];

  public $js = [
    'js/bootstrap-datepicker.min.js',
    'js/bootstrap-datepicker.ru.min.js',
  ];

}
