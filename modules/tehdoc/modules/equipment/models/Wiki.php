<?php

namespace app\modules\tehdoc\modules\equipment\models;

use Yii;
use yii\helpers\ArrayHelper;

class Wiki extends \yii\db\ActiveRecord
{
  const PLACEMENT_TABLE = '{{%teh_placement_tbl}}';
  const CATEGORY_TABLE = '{{%teh_category_tbl}}';

  public static function tableName()
  {
    return 'teh_wiki_tbl';
  }

  public function rules()
  {
    return [
      [['wiki_title'], 'required'],
      [['id', 'eq_ref', 'wiki_created_user', 'valid'], 'integer'],
      [['wiki_text', 'wiki_record_create', 'wiki_record_update'], 'safe'],
      [['wiki_title'], 'string', 'max' => 255],
    ];
  }
}