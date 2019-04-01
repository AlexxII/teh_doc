<?php

namespace app\modules\tehdoc\models;

use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;
use app\base\NestedSetsTreeBehavior;

class Placement extends ActiveRecord
{

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

  public static function tableName()
  {
    return 'teh_placement_tbl';
  }

  public static function find()
  {
    return new CategoryQuery(get_called_class());
  }


}