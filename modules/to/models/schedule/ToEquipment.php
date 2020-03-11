<?php

namespace app\modules\to\models\schedule;

use yii\web\NotFoundHttpException;

use app\base\MHelper;
use creocoder\nestedsets\NestedSetsBehavior;
use app\modules\to\base\NestedSetsTreeBehaviorExX;

class ToEquipment extends \yii\db\ActiveRecord
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
        'class' => NestedSetsTreeBehaviorExX::className(),
        'depthAttribute' => 'lvl'
      ]
    ];
  }

  public function __construct()
  {
    $this->id = MHelper::generateId();
  }

  public function getParents()
  {
    return $this->hasOne(ToEquipment::class, ['parent_id' => 'id'])->alias('parent');
  }

  public function getGroupName()
  {
    $parentCount = $this->parents()->count();
    if ($parentCount > 1){
      if ($this->parents(1)){
        return $this->parents(1);
      }
      return false;
    }
    return '';
  }

  public static function findModel($id)
  {
    if (($model = ToEquipment::findOne($id)) !== null) {
      return $model;
    }
    throw new NotFoundHttpException('Запрошенная страница не существует.');
  }

}