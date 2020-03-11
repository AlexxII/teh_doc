<?php

namespace app\modules\to\models\schedule;

use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

use app\modules\admin\models\User;
use app\modules\to\models\ToAdmins;
use app\modules\to\models\ToType;
use app\base\MHelper;

class ToYearSchedule extends \yii\db\ActiveRecord
{

  public static function tableName()
  {
    return 'to_year_schedule_tbl';
  }

  public function __construct()
  {
    parent::__construct();
  }

/*  public function rules()
  {
    [['eq_id', 'schedule_year', 'm1', 'm2', 'm3', 'm4', 'm5', 'm6', 'm7', 'm8', 'm9', 'm10', 'm11', 'm12'], 'safe'];
  }*/

  public function getAdminList()
  {
    return ArrayHelper::map(ToAdmins::find()->where(['admin' => 1])->orderBy('lft')->asArray()->all(), 'id', 'name');
  }

  public function getToList()
  {
    $toTypes = ToType::find()->where(['!=', 'lvl', '0'])->orderBy('lft')->asArray()->all();
    $toTypeArray = array();
    foreach ($toTypes as $toType) {
      $toTypeArray[$toType['id']] = mb_substr($toType['name'], 0, 1);
    }
    return $toTypeArray;
    return ArrayHelper::map(ToType::find()->where(['!=', 'lvl', '0'])->orderBy('lft')->asArray()->all(), 'id', 'name');
  }


  public static function findModel($year)
  {
    if (($model = Polls::find()->where(['schedule_year' => $year])) !== null) {
      return $model;
    }
    throw new NotFoundHttpException('Запрошенная страница не существует.');
  }

}
