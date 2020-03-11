<?php

namespace app\modules\polls\models;

use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

use app\base\MHelper;

class Questions extends \yii\db\ActiveRecord
{

  public static function tableName()
  {
    return 'poll_questions_tbl';
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
      "title" => "Наименование вопроса:",
      "code" => "Код:",
      "limit" => "Ограничения:",
      "input_type" => "Тип ответов:",
      "order" => "Последовательность",
    ];
  }

  public function rules()
  {
    return [
      [["id", "poll_id", "title", "code", "limit", "input_type", "order"], "safe"]
    ];
  }

  public function getAnswers()
  {
    return $this->hasMany(Answers::class, ['question_id' => 'id'])->orderBy('order');
  }

  public function getVisibleAnswers()
  {
    return $this->hasMany(Answers::class, ["question_id" => "id"])->where(["visible" => "1"])->orderBy("order");
  }


  /*
    public function getAdminList()
    {
      return ArrayHelper::map(ToAdmins::find()->where(['admin' => 1])->orderBy('lft')->asArray()->all(), 'id', 'name');
    }
  */

  public static function findModel($id)
  {
    if (($model = Questions::findOne($id)) !== null) {
      return $model;
    }
    throw new NotFoundHttpException('Запрошенная страница не существует.');
  }

}
