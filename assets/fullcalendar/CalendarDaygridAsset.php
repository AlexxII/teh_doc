<?php

namespace app\assets\fullcalendar;

use yii\web\AssetBundle;

class CalendarDaygridAsset extends AssetBundle
{
  public $sourcePath = '@bower/fullcalendar/dist/daygrid';
//  public $sourcePath = '@bower/fullcalendar/packages/daygrid/src';

  public $css = [
    'main.css',
  ];

  public $js = [
    'main.min.js'
  ];

  public $depends = [
    'app\assets\fullcalendar\CalendarCoreAsset'
  ];
}
