<?php

namespace app\modules\scheduler\models;

use app\base\MHelper;
use yii\helpers\ArrayHelper;

use app\modules\admin\models\User;
use app\modules\scheduler\models\DutyType;

class Duty extends \yii\db\ActiveRecord
{
  public static function tableName()
  {
    return 'scheduler_duty_tbl';
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
      'duration' => 'Продолжительность:',
      'duty_type' => 'Тип дежурства:',
      'user_id' => 'Пользователь:'
    ];
  }

  public function rules()
  {
    return [
      [['start_date', 'end_date', 'user_id'], 'required'],
    ];
  }

  public function getUserList()
  {
    return ArrayHelper::map(User::find()->where(['!=', 'login', 'sAdmin'])->asArray()->all(), 'id', 'username');
  }

  public function getDutyList()
  {
    return ArrayHelper::map(DutyType::find()->where(['!=', 'lvl', 0])->asArray()->all(), 'id', 'name');
  }

  public function getUser()
  {
    return $this->hasOne(User::class, ['id' => 'user_id']);
  }

  public function getDuty()
  {
    return $this->hasOne(DutyType::class, ['id' => 'duty_type']);
  }

  public function getDutyType()
  {
    if ($this->duty) {
      return $this->duty->name;
    } else {
      return false;
    }
  }

  public function getUserId()
  {
    if ($this->user) {
      return $this->user->id;
    } else {
      return false;
    }
  }

  public function getUsername()
  {
    if ($this->user) {
      return $this->user->username;
    } else {
      return '-';
    }
  }

  public function getColor()
  {
    if ($this->user) {
      return $this->user->color_scheme;
    } else {
      return null;
    }
  }

}