<?php

namespace app\modules\to\models\schedule;

use app\modules\admin\models\User;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;

use app\base\MHelper;
use creocoder\nestedsets\NestedSetsBehavior;
use app\modules\to\base\NestedSetsTreeBehaviorEx;

class ToAdmins extends \yii\db\ActiveRecord
{

  public static function tableName()
  {
    return 'to_admins_tbl';
  }

  public function __construct()
  {
    $this->id = MHelper::generateId();
    parent::__construct();
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
        'class' => NestedSetsTreeBehaviorEx::className(),
        'depthAttribute' => 'lvl'
      ]
    ];
  }

  public static function findModel($id)
  {
    if (($model = ToAdmins::findOne($id)) !== null) {
      return $model;
    }
    throw new NotFoundHttpException('Запрашиваемая страница не существует.');
  }

  public function getUsersList()
  {
    return ArrayHelper::map(User::find()->where(['!=', 'login', 'sAdmin'])->asArray()->all(), 'id', 'username');
  }

  public function getRolesList()
  {
    return [
      0 => 'Ответствтенные за контроль',
      1 => 'Ответственные за проведение ТО'
    ];
  }


}
