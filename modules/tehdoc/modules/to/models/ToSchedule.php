<?php

namespace app\modules\tehdoc\modules\to\models;


use app\modules\admin\models\User;
use yii\helpers\ArrayHelper;


class ToSchedule extends \yii\db\ActiveRecord
{

  const SCENARIO_CREATE = 'create';
  const SCENARIO_CONFIRM = 'confirm';

  public static function tableName()
  {
    return 'teh_to_schedule_tbl';
  }

  public function scenarios()
  {
    $scenarios = parent::scenarios();
    $scenarios[self::SCENARIO_CREATE] = [
      'eq_id', 'to_type', 'plan_date', 'to_month', 'admin_id', 'auditor_id'
    ];
    $scenarios[self::SCENARIO_CONFIRM] = [
      'eq_id', 'to_type', 'plan_date', 'fact_date', 'checkmark', 'date_in',
      'to_month', 'admin_id', 'auditor_id'
    ];
    return $scenarios;
  }

  public function rules()
  {
    return [
      [['eq_id', 'to_type', 'plan_date', 'admin_id', 'auditor_id'], 'required', 'on' => self::SCENARIO_CREATE],
      [[
        'eq_id', 'to_type', 'plan_date', 'fact_date', 'checkmark', 'admin_id', 'auditor_id'
      ], 'required', 'on' => self::SCENARIO_CREATE],
      [['plan_date', 'to_month', 'fact_date'], 'date', 'format' => 'yyyy-M-d'],
      [['date_in', 'to_month'], 'safe'],
    ];
  }

  public function getToType()
  {
    return $this->hasOne(ToType::class, ['ref' => 'to_type']);
  }

  public function getToList()
  {
    return ArrayHelper::map(ToType::find()->where(['!=', 'lvl', '0'])->asArray()->all(), 'ref', 'name');

  }

  public function getToEq()
  {
    return $this->hasOne(ToEquipment::class, ['ref' => 'eq_id']);
  }

  public function getAdmin()
  {
    return $this->hasOne(User::class, ['ref' => 'admin_id']);
  }

  public function getAuditor()
  {
    return $this->hasOne(User::class, ['ref' => 'auditor_id']);
  }

  public function getAdminList()
  {
    return [
      44491978 => 'Лесин С.Н.',
      47481824 => 'Игнатенко А.М.'
    ];
  }

  public function getAuditorList()
  {
    return [
      24992690 => 'Малышев В.Ю.',
      26823825 => 'Врачев Д.С.'
    ];
  }



}
