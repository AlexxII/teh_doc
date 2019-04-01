<?php

namespace app\modules\tehdoc\modules\equipment\models;

use Yii;
use yii\helpers\ArrayHelper;

class Oth extends \yii\db\ActiveRecord
{
  public $docFiles;

  public static function tableName()
  {
    return 'teh_oth_tbl';
  }

}