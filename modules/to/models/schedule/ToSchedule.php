<?php

namespace app\modules\to\models\schedule;

use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

use app\modules\admin\models\User;
use app\base\MHelper;

class ToSchedule extends \yii\db\ActiveRecord
{

  const SCENARIO_CREATE = 'create';
  const SCENARIO_CONFIRM = 'confirm';

  public static function tableName()
  {
    return 'to_schedule_tbl';
  }

  public function   __construct()
  {
    parent::__construct();
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
      ], 'required', 'on' => self::SCENARIO_CONFIRM],
      [['plan_date', 'to_month', 'fact_date'], 'date', 'format' => 'yyyy-M-d'],
      [['date_in', 'to_month'], 'safe'],
    ];
  }

  public function getToType()
  {
    return $this->hasOne(ToType::class, ['id' => 'to_type']);
  }


  public function getToList()
  {
    return ArrayHelper::map(ToType::find()->where(['!=', 'lvl', '0'])->orderBy('lft')->asArray()->all(), 'id', 'name');
  }

  public function getToListShort()
  {
    return ArrayHelper::map(ToType::find()->where(['!=', 'lvl', '0'])->orderBy('lft')->asArray()->all(), 'id', 'name');
  }

  public function getToEq()
  {
    return $this->hasOne(ToEquipment::class, ['id' => 'eq_id']);
  }

  public function getAdmin()
  {
    return $this->hasOne(ToAdmins::class, ['id' => 'admin_id']);
  }

  public function getAuditor()
  {
    return $this->hasOne(ToAdmins::class, ['id' => 'auditor_id']);
  }

  public function getAdminList()
  {
    return ArrayHelper::map(ToAdmins::find()->where(['admin' => 1])->orderBy('lft')->asArray()->all(), 'id', 'name');
  }

  public function getAuditorList()
  {
    return ArrayHelper::map(ToAdmins::find()->where(['admin' => 0])->orderBy('lft')->asArray()->all(), 'id', 'name');
  }

  public static function findModel($id)
  {
    if (($model = ToSchedule::find()->where(['schedule_id' => $id])) !== null) {
      return $model;
    }
    throw new NotFoundHttpException('Запрошенная страница не существует.');
  }

}
