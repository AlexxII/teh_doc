<?php

namespace app\assets\fullcalendar;

use yii\web\AssetBundle;

class CalendarInteractionAsset extends AssetBundle
{
  public $sourcePath = '@bower/fullcalendar/dist/interaction';
//  public $sourcePath = '@bower/fullcalendar/packages/interaction/src';


  public $css = [
    'main.css',
  ];

  public $js = [
    'main.js'
  ];

  public $depends = [
    'app\assets\fullcalendar\CalendarCoreAsset'
  ];
}
