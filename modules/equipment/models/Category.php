<?php

namespace app\modules\equipment\models;

use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\web\NotFoundHttpException;


use app\base\NestedSetsTreeBehavior;
use app\base\MHelper;

class Category extends ActiveRecord
{

  public static function tableName()
  {
    return 'equipment_category_tbl';
  }

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

  public function __construct()
  {
    $this->id = MHelper::generateId();
  }


  public function getId()
  {
    return $this->id;
  }

  public static function findModel($id)
  {
    if (($model = Category::findOne($id)) !== null) {
      return $model;
    }
    throw new NotFoundHttpException('Запрашиваемая страница не существует.');
  }

  public static function find()
  {
    return new CategoryQuery(get_called_class());
  }

  public static function testTree()
  {
    $models = Category::find()->select('id, name, lvl')->orderBy('lft')->where(['>', 'lvl', 0])->asArray()->all();
    if (!$models) {
      return ['1' => 'Добавьте категории в панели администрирования'];
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