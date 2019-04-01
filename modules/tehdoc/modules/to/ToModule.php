<?php

namespace app\modules\tehdoc\modules\to;

use yii\filters\AccessControl;

class ToModule extends \yii\base\Module
{
  public $layout = 'to_layout.php';
  public $defaultRoute = '/default';

  public function init()
  {
    parent::init();
  }

}