<?php

namespace app\modules\scheduler\models;

use yii\web\NotFoundHttpException;

use app\base\MHelper;
use creocoder\nestedsets\NestedSetsBehavior;
use app\modules\tehdoc\modules\to\base\NestedSetsTreeBehavior;

class AbsenceType extends \yii\db\ActiveRecord
{

  public static function tableName()
  {
    return 'scheduler_absence_type_tbl';
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
        'class' => NestedSetsTreeBehavior::className(),
        'depthAttribute' => 'lvl'
      ]
    ];
  }

  public static function findModel($id)
  {
    if (($model = AbsenceType::findOne($id)) !== null) {
      return $model;
    }
    throw new NotFoundHttpException('Запрашиваемая страница не существует.');
  }


}
