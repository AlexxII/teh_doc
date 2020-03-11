<?php

namespace app\modules\equipment\asset;

use yii\web\AssetBundle;

class EquipmentFormsAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/equipment/lib';
    public $css = [
        'css/jquery-ui.min.css'
    ];

    public $js = [
        'js/jquery.maskedinput.js',
    ];
}
