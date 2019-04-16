<?php

namespace app\modules\tehdoc\models;

use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\web\NotFoundHttpException;

use app\base\NestedSetsTreeBehavior;
use app\base\MHelper;

class Placement extends ActiveRecord
{

  public static function tableName()
  {
    return 'teh_placement_tbl';
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
        'class' => NestedSetsTreeBehavior::className(),
        'depthAttribute' => 'lvl'
      ]
    ];
  }

  public function transactions()
  {
    return [
      self::SCENARIO_DEFAULT => self::OP_ALL,
    ];
  }

  public function __construct()
  {
    $this->id = MHelper::generateId();
  }

  public static function find()
  {
    return new CategoryQuery(get_called_class());
  }

  public static function findModel($id)
  {
    if (($model = Placement::findOne($id)) !== null) {
      return $model;
    }
    throw new NotFoundHttpException('Запрошенная страница не существует.');
  }

}