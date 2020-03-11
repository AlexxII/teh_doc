<?php

namespace app\modules\polls\models;

use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

use app\base\MHelper;

class Answers extends \yii\db\ActiveRecord
{

  public static function tableName()
  {
    return 'poll_answers_tbl';
  }

  public function __construct()
  {
    $this->id = MHelper::generateId();
    parent::__construct();
  }

  public function attributeLabels()
  {
    return [
      "id" => "ID",
      "title" => "Наименование ответа:",
      "code" => "Код:",
      "order" => "Последовательность",
    ];
  }

  public function rules()
  {
    return [
      [["id", "poll_id", "question_id", "title", "code", "order", "input_type"], "safe"]
    ];
  }

  public function getQuestion()
  {
    return $this->hasOne(Questions::class, ['id' => 'question_id'])->orderBy('order');
  }

  public function getLogic()
  {
    return $this->hasMany(PollLogic::class, ['answer_id' => 'id']);
  }

  public static function findModel($id)
  {
    if (($model = Answers::findOne($id)) !== null) {
      return $model;
    }
    throw new NotFoundHttpException('Запрошенная страница не существует.');
  }
  
}
