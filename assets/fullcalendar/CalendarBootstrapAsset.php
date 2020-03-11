<?php

namespace app\assets\fullcalendar;

use yii\web\AssetBundle;

class CalendarBootstrapAsset extends AssetBundle
{
  public $sourcePath = '@bower/fullcalendar/dist/bootstrap';

  public $css = [
    'main.scc',
  ];

  public $js = [
    'main.min.js'
  ];

  public $depends = [
    'app\assets\fullcalendar\CalendarCoreAsset'
  ];
}
