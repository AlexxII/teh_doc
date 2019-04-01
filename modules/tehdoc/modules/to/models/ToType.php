<?php

namespace app\modules\tehdoc\modules\to\models;

use creocoder\nestedsets\NestedSetsBehavior;
use app\modules\tehdoc\modules\to\base\NestedSetsTreeBehavior;

class ToType extends \yii\db\ActiveRecord
{

  public static function tableName()
  {
    return 'teh_to_type_tbl';
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

}
