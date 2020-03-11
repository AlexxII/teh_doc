<?php

namespace app\modules\to\models\schedule;

use yii\web\NotFoundHttpException;

use app\base\MHelper;
use creocoder\nestedsets\NestedSetsBehavior;
use app\modules\to\base\NestedSetsTreeBehaviorTrash;

class ToTrashEquipment extends \yii\db\ActiveRecord
{

  public static function tableName()
  {
    return 'to_equipment_tbl';
  }

  public function behaviors()
  {
    return [
      'tree' => [
        'class' => NestedSetsBehavior::className(),
        'treeAttribute' => 'root',
        'leftAttribute' => 'lft',
        'rightAttribute' => 'rgt',
        'depthAttribute' => 'lvl',
      ],
      'htmlTree' => [
        'class' => NestedSetsTreeBehaviorTrash::className(),
        'depthAttribute' => 'lvl'
      ]
    ];
  }

  public function __construct()
  {
    $this->id = MHelper::generateId();
  }

  public static function findModel($id)
  {
    if (($model = ToTrashEquipment::findOne($id)) !== null) {
      return $model;
    }
    throw new NotFoundHttpException('Запрошенная страница не существует.');
  }

}