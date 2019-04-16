<?php

namespace app\modules\tehdoc\modules\equipment\models;

use Yii;
use yii\helpers\ArrayHelper;

use app\base\MHelper;

class Wiki extends \yii\db\ActiveRecord
{
  const PLACEMENT_TABLE = '{{%teh_placement_tbl}}';
  const CATEGORY_TABLE = '{{%teh_category_tbl}}';

  public static function tableName()
  {
    return 'teh_wiki_tbl';
  }

  public function __construct()
  {
    $this->id = MHelper::generateId();
  }

  public function rules()
  {
    return [
      [['wiki_title'], 'required'],
      [['id', 'eq_id', 'wiki_created_user', 'valid'], 'integer'],
      [['wiki_text', 'wiki_record_create', 'wiki_record_update'], 'safe'],
      [['wiki_title'], 'string', 'max' => 255],
    ];
  }

  public function attributeLabels()
  {
    return [
      'wiki_title' => 'Название страницы',
    ];
  }

  public static function findModel($id)
  {
    if (($model = Wiki::findOne($id)) !== null) {
      return $model;
    }
    throw new NotFoundHttpException('Запрашиваемая страница не найдена.');
  }


}