<?php

namespace app\modules\vks\models;

use creocoder\nestedsets\NestedSetsBehavior;
use yii\web\NotFoundHttpException;

use app\base\MHelper;
use app\base\NestedSetsTreeBehavior;

/**
 * This is the model class for table "vks_type_tbl".
 *
 * @property string $id_type
 * @property int $root
 * @property int $lft
 * @property int $rgt
 * @property int $lvl
 * @property string $name
 */
class VksTypes extends \yii\db\ActiveRecord
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

  public function __construct()
  {
    $this->id = MHelper::generateId();
  }

  public function transactions()
  {
    return [
      self::SCENARIO_DEFAULT => self::OP_ALL,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function tableName()
  {
    return 'vks_types_tbl';
  }

  /**
   * {@inheritdoc}
   */

  public static function find()
  {
    return new VksQuery(get_called_class());
  }

  public static function findModel($id)
  {
    if (($model = VksTypes::findOne($id)) !== null) {
      return $model;
    }
    throw new NotFoundHttpException('Запрашиваемая страница не существует.');
  }


}