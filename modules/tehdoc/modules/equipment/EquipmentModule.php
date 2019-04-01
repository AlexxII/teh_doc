<?php

namespace app\modules\tehdoc\modules\equipment;

use yii\filters\AccessControl;

class EquipmentModule extends \yii\base\Module
{
    public $layout = 'equipment_layout.php';
    public $defaultRoute = '/default';

    public function init()
    {
        parent::init();
    }

}