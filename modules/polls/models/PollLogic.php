<?php

namespace app\modules\polls\models;

use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

use app\base\MHelper;

class PollLogic extends \yii\db\ActiveRecord
{

  public static function tableName()
  {
    return 'poll_logic_tbl';
  }

  public function __construct()
  {
    $this->id = MHelper::generateId();
    parent::__construct();
  }

  public function rules()
  {
    return [
      [["id", "poll_id", "answer_id", "restrict_id", "restrict_type"], "safe"]
    ];
  }

  public static function findModel($id)
  {
    if (($model = Answers::findOne($id)) !== null) {
      return $model;
    }
    throw new NotFoundHttpException('Запрошенная страница не существует.');
  }
  
}
