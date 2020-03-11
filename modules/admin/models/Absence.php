<?php

namespace app\modules\admin\models;


class Absence extends \yii\db\ActiveRecord
{

  public static function tableName()
  {
    return 'people_absence_tbl';
  }

  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'date' => 'Часть отпуска:'
    ];
  }
//  public function rules()
//  {
//
//  }

}