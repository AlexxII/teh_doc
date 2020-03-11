<?php

namespace app\modules\scheduler;

class SchedulerModule extends \yii\base\Module
{

  public $layout = '@app/views/layouts/main.php';

  public function init()
  {
    \Yii::$app->view->title = 'Календарь';
    parent::init();
  }

}