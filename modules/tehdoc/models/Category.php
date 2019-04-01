<?php

namespace app\modules\tehdoc\models;

use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;
use app\base\NestedSetsTreeBehavior;

class Category extends ActiveRecord
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
    return 'teh_category_tbl';
  }

  public static function find()
  {
    return new CategoryQuery(get_called_class());
  }

  public static function testTree()
  {
    $models = Category::find()->select('id, name, lvl')->orderBy('lft')->where(['>', 'lvl', 0])->asArray()->all();
    if (!$models) {
      return ['1.php' => 'Добавьте категории в панели администрирования'];
    }
    $array = array();
    foreach ($models as $model) {
      $prefix = '';
      for ($i = 1; $i < $model['lvl']; $i++) {
        $prefix .= ' - ';
      }
      $array[$model['id']] = $prefix . $model['name'];
    }
    return $array;
  }

}