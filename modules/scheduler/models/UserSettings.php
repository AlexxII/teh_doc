<?php

namespace app\modules\scheduler\models;

use app\base\MHelper;
use yii\helpers\ArrayHelper;

class UserSettings extends \yii\db\ActiveRecord
{
  const CALENDARPULL_TABLE = '{{%calendar_pull_tbl}}';
  const USERSETTINGS_TABLE = '{{%calendar_users_settings_tbl}}';

  public static function tableName()
  {
    return 'calendar_users_settings_tbl';
  }

  public function __construct()
  {
    $this->id = MHelper::generateId();
  }

/*
public function attributeLabels()
  {
    return [
      'calendar' => 'Пользователь',
    ];
  }
*/

  public function rules()
  {
    return [
      [['user_id', 'calendar'], 'required']
    ];
  }

  public function getNonsubscribeList()
  {
    $sql = "SELECT C1.id, C1.title from " . self::CALENDARPULL_TABLE . " C1 LEFT JOIN "
      . self::USERSETTINGS_TABLE . " C2 on C1.id = C2.calendar WHERE C2.user_id != '6458347'";
    return ArrayHelper::map($this->findBySql($sql)->asArray()->all(), 'id', 'title');
  }


}