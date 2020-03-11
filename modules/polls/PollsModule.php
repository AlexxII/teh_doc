<?php

namespace app\modules\polls;

class PollsModule extends \yii\base\Module
{
  public $layout = '@app/views/layouts/main.php';

  public function init()
  {
    \Yii::$app->view->title = 'Опросы';
    parent::init();
  }

}