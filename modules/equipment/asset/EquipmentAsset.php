<?php

namespace app\modules\equipment\asset;

use yii\web\AssetBundle;

class EquipmentAsset extends AssetBundle
{
  public $sourcePath = '@app/modules/equipment/lib';

  public $css = [
    'css/equipment.css',
  ];

  public $js = [
    'js/equipment.js'
  ];
}
