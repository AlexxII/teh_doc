<?php

namespace app\modules\tehdoc\modules\equipment\models;

use Yii;
use yii\helpers\ArrayHelper;

class ToolSettings extends \yii\db\ActiveRecord
{
  public static function tableName()
  {
    return 'teh_settings_tbl';
  }

}