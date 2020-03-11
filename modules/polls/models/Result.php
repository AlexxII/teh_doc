<?php

namespace app\modules\polls\models;

use app\modules\admin\models\User;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

use app\base\MHelper;

class Result extends \yii\db\ActiveRecord
{

  public static function tableName()
  {
    return 'poll_respondents_tbl';
  }

  public function __construct()
  {
    $this->id = MHelper::generateId();
    parent::__construct();
  }

  public function rules()
  {
    return [
      [["id", "respondent_id", "poll_id", "answer_id", "user_id", "input_time", "ex_answer", "town_id", "isDeleted"], "safe"]
    ];
  }

  public function getOperators()
  {
    return $this->hasOne(User::class, ['id' => 'user_id']);
  }

  public static function findModel($id)
  {
    if (($model = Result::findOne($id)) !== null) {
      return $model;
    }
    throw new NotFoundHttpException('Запрошенная страница не существует.');
  }
  
}
