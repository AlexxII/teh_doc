<?php

namespace app\modules\vks\models;

use Yii;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\web\NotFoundHttpException;

use app\base\MHelper;
use app\base\NestedSetsTreeBehavior;

/**
 * This is the model class for table "vks_order_tbl".
 *
 * @property string $id
 * @property int $root
 * @property int $lft
 * @property int $rgt
 * @property int $lvl
 * @property string $name
 */
class VksOrders extends \yii\db\ActiveRecord
{

  public static function tableName()
  {
    return 'vks_orders_tbl';
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
    return new VksQuery(get_called_class());
  }

  public static function findModel($id)
  {
    if (($model = VksOrders::findOne($id)) !== null) {
      return $model;
    }
    throw new NotFoundHttpException('Запрашиваемая страница не существует.');
  }

}