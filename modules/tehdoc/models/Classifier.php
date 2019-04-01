<?php

namespace app\modules\tehdoc\models;

use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;
use app\base\NestedSetsTreeBehavior;

class Classifier extends ActiveRecord
{

  public function behaviors()
  {
    return [
      'tree' => [
        'class' => NestedSetsBehavior::class,
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
    return 'teh_classifier_tbl';
  }


}