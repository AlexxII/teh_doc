<?php

namespace app\modules\maps;

class MapsModule extends \yii\base\Module
{
  public $layout = '@app/views/layouts/main.php';

  public function init()
  {
    \Yii::$app->view->title = 'Карты';
    parent::init();
  }

}