<?php

namespace app\assets\fullcalendar;

use yii\web\AssetBundle;

class CalendarTimegridAsset extends AssetBundle
{
  public $sourcePath = '@bower/fullcalendar/dist/timegrid';
//  public $sourcePath = '@bower/fullcalendar/packages/timegrid/src';

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
