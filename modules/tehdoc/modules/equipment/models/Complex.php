<?php

namespace app\modules\tehdoc\modules\equipment\models;

use app\modules\admin\models\Category;
use Yii;
use yii\helpers\ArrayHelper;

class Complex extends \yii\db\ActiveRecord
{

  const PLACEMENT_TABLE = '{{%teh_placement_tbl}}';
  const CATEGORY_TABLE = '{{%teh_category_tbl}}';

  public static function tableName()
  {
    return 'teh_complex_tbl';
  }

  public function rules()
  {
    return [
//      [['category_id', 'complex_title', 'place_id'], 'required'],
      [['category_id', 'place_id', 'quantity', 'valid'], 'integer'],
      [['complex_factdate', 'complex_comments'], 'safe'],
      [['complex_title', 'complex_serial', 'complex_manufact', 'complex_model'], 'string', 'max' => 255],
    ];
  }

  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'category_id' => 'Категория:',
      'complex_title' => 'Наименование:',
      'complex_manufact' => 'Производитель:',
      'complex_model' => 'Модель:',
      'complex_serial' => 's/n:',
      'complex_factdate' => 'Дата производства:',
      'place_id' => 'Место нахождения:',
      'quantity' => 'Количество:',
      'valid' => 'Valid',
      'complex_comments' => 'Примечание:'
    ];
  }

  public function getTools()
  {
    return $this->hasMany(Tools::class, ['parent_id' => 'id_complex']);
  }

  public function getCategory()
  {
    return $this->hasOne(Category::class, ['ref' => 'category_id']);
  }

  public function getCategoryTitle()
  {
    if ($title = $this->category) {
      $subCat = Category::findOne(['name' => $title]);
      $parent = $subCat->parents(1)->one();
      return $parent->name;
    } else {
      return '-';
    }
  }

  public function getToolPlacesList()
  {
    $sql = "SELECT C1.ref, C1.name, C2.name as gr from " . self::PLACEMENT_TABLE . " C1 LEFT JOIN "
      . self::PLACEMENT_TABLE . " C2 on C1.parent_id = C2.ref WHERE C1.lvl > 1 ORDER BY C1.lft";
    return ArrayHelper::map($this->findBySql($sql)->asArray()->all(), 'ref', 'name', 'gr');
  }

  public function getToolCategoryList()
  {
    $sql = "SELECT C1.ref, C1.name, C2.name as gr from " . self::CATEGORY_TABLE . " C1 LEFT JOIN "
      . self::CATEGORY_TABLE . " C2 on C1.parent_id = C2.ref WHERE C1.lvl > 1 AND C1.root = 2 ORDER BY C1.lft";
    return ArrayHelper::map($this->findBySql($sql)->asArray()->all(), 'ref', 'name', 'gr');
  }

}
