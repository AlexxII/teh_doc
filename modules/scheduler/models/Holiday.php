<?php

namespace app\modules\scheduler\models;

use app\base\MHelper;

class Holiday extends \yii\db\ActiveRecord
{
  public static function tableName()
  {
    return 'calendar_holidays_tbl';
  }

  public function __construct()
  {
    $this->id = MHelper::generateId();
  }

  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'start_date' => 'Дата начала:',
      'end_date' => 'Дата окончания:',
      'title' => 'Наименование:',
      'description' => 'Подробности:',
      'duration' => 'Продолжительность:',
      'year_repeat' => 'Ежегодный',
      'holiday_type' => 'Тип:',
      'approval_year' => 'Год утверждения:'
    ];
  }

  public function rules()
  {
    return [
      [['start_date', 'end_date'], 'required'],
      [['description'], 'string'],
    ];
  }

  public function getHolidayType()
  {
    return [
      '0' => 'Рабочий день',
      '1' => 'Предпраздничный',
      '2' => 'Выходной',
      '3' => 'Праздник',
    ];
  }

}