<?php

namespace app\modules\polls\models;

use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

use app\base\MHelper;

class Xml extends \yii\db\ActiveRecord
{

  public static function tableName()
  {
    return "poll_xmlfiles_tbl";
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

  /*
    public function getAdminList()
    {
      return ArrayHelper::map(ToAdmins::find()->where(["admin" => 1])->orderBy("lft")->asArray()->all(), "id", "name");
    }
  */

  public static function findModel($id)
  {
    if (($model = Xml::findOne($id)) !== null) {
      return $model;
    }
    throw new NotFoundHttpException("Запрошенная страница не существует.");
  }
  
}
