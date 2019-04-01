<?php

namespace app\modules\tehdoc\modules\equipment\models;

use Yii;
use yii\helpers\ArrayHelper;

class Special extends \yii\db\ActiveRecord
{
  // Описывает Спец работы
  public static function tableName()
  {
    return 'teh_special_works_tbl';
  }

}