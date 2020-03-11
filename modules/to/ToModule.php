<?php

namespace app\modules\to;

use yii\filters\AccessControl;

class ToModule extends \yii\base\Module
{

  public $layout = '@app/views/layouts/main.php';

  public function init()
  {
    \Yii::$app->view->title = 'ТО';
    parent::init();
  }

}