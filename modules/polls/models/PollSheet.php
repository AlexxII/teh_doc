<?php

namespace app\modules\polls\models;

use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

use app\base\MHelper;

class PollSheet extends \yii\db\ActiveRecord
{

  public static function tableName()
  {
    return "poll_pollsheet_tbl";
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
      "title" => "Наименование файла:",
      "with_errors" => "Содержит ошибки",
    ];
  }

  public function rules()
  {
    return [
      [["id", "poll_id", "title"], "safe"]
    ];
  }


  public static function findModel($id)
  {
    if (($model = PollSheet::findOne($id)) !== null) {
      return $model;
    }
    throw new NotFoundHttpException("Запрошенная страница не существует.");
  }
  
}
