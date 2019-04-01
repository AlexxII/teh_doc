<?php

namespace app\modules\tehdoc;

use yii\filters\AccessControl;

class TehdocModule extends \yii\base\Module
{

  public $layout = 'tehdoc_layout.php';
//  public $defaultRoute = '/tools';

  
  public function init()
  {
    parent::init();
    // подключение дочернего модуля
    $this->modules = [
        'equipment' => [
            'class' => 'app\modules\tehdoc\modules\equipment\EquipmentModule',
        ],
        'to' => [
            'class' => 'app\modules\tehdoc\modules\to\ToModule',
        ],
    ];
  }

}