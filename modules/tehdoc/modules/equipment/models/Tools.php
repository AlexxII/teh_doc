<?php

namespace app\modules\tehdoc\modules\equipment\models;

use app\modules\admin\models\Classifier;
use app\modules\admin\models\Placement;
use app\modules\admin\models\Category;
use app\modules\tehdoc\modules\equipment\models\Images;
use app\modules\tehdoc\modules\to\models\ToEquipment;
use yii\helpers\ArrayHelper;
use app\base\NestedSetsTreeBehaviorEx;
use creocoder\nestedsets\NestedSetsBehavior;


/**
 * This is the model class for table "equipment_tbl".
 *
 * @property int $ide
 * @property int $id_eq
 * @property int $category_id
 * @property string $eq_title
 * @property string $eq_manufact
 * @property string $eq_model
 * @property string $eq_serial
 * @property string $eq_factdate
 * @property int $place_id
 * @property int $quantity
 * @property int $valid Оборудование по умолчанию будет отображаться
 */
class Tools extends \yii\db\ActiveRecord
{
  const PLACEMENT_TABLE = '{{%teh_placement_tbl}}';
  const CATEGORY_TABLE = '{{%teh_category_tbl}}';

  const SCENARIO_CREATE = 'create';
  const SCENARIO_UPDATE = 'update';

  public $eq_operating_time;
  public $invent_number;
  public $tempId;

  public static function tableName()
  {
    return 'teh_equipment_tbl';
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

  public function scenarios()
  {
    $scenarios = parent::scenarios();
    $scenarios[self::SCENARIO_UPDATE] = [
      'category_id', 'eq_title', 'place_id', 'quantity', 'eq_manufact', 'eq_model', 'eq_serial', 'eq_factdate'
    ];
    $scenarios[self::SCENARIO_CREATE] = [
      'category_id', 'eq_title', 'place_id', 'quantity', 'eq_manufact', 'eq_model', 'eq_serial', 'eq_factdate'
    ];
    return $scenarios;
  }

  /**
   * @inheritdoc
   */
  public function rules()
  {
    return [
      [['category_id', 'eq_title', 'place_id', 'quantity'], 'required', 'on' => self::SCENARIO_UPDATE],
      [['category_id', 'eq_title', 'place_id', 'quantity'], 'required', 'on' => self::SCENARIO_CREATE],
      [['category_id', 'place_id', 'quantity'], 'integer'],
      [['eq_factdate', 'eq_comments'], 'safe'],
      [['eq_title', 'eq_manufact', 'eq_model', 'eq_serial'], 'string', 'max' => 250],
    ];
  }

  /**
   * @inheritdoc
   */
  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'category_id' => 'Категория оборудования:',
      'eq_title' => 'Наименование:',
      'eq_manufact' => 'Производитель:',
      'eq_model' => 'Модель:',
      'eq_serial' => 's/n:',
      'eq_factdate' => 'Дата производства:',
      'place_id' => 'Место нахождения:',
      'quantity' => 'Количество:',
      'eq_comments' => 'Примечание:',
      'eq_class' => 'Класс оборудования:',
      'invent_number' => 'Инвентарный номер:',
      'eq_operating_time' => 'Наработка'
//        'valid' => 'Valid',
    ];
  }

  public function getCategory()
  {
    return $this->hasOne(Category::class, ['ref' => 'category_id']);
  }

  public function getCategoryTitle()
  {
    // TODO: Возможно необходимо сделать переменную $depth настраиваемой
    $depth = 1; // сколько уровней
    if ($this->category) {
      $full = $this->category;
      $parentCount = $full->parents()->count();
      $parent = $full->parents($parentCount - $depth)->all();
      $fullname = '';
      foreach ($parent as $p) {
        $fullname .= $p->name . ' ->';
      }
      return $fullname . ' ' . $this->category->name;
    } else {
      return '-';
    }
  }

  public function getPlacement()
  {
    return $this->hasOne(Placement::class, ['ref' => 'place_id']);
  }

  public function getPlacementTitle($depth = 1)
  {
    // TODO: Возможно необходимо сделать переменную $depth настраиваемой
    if ($this->placement) {
      $full = $this->placement;
      $parentCount = $full->parents()->count();
      $parent = $full->parents($parentCount - $depth)->all();
      $fullname = '';
      foreach ($parent as $p) {
        $fullname .= $p->name . ' ->';
      }
      return $fullname . ' ' . $this->placement->name;
    } else {
      return '-';
    }
  }

  public function toolParents($depth = 1)
  {
    $parentCount = $this->parents()->count();
    $parent = $this->parents($parentCount - $depth)->all();
    $fullname = '';
    foreach ($parent as $p) {
      $fullname .= $p->name . ' / ';
    }
    return $fullname;
  }


  // Tool Settings
  public function getSettings()
  {
    return $this->hasOne(ToolSettings::class, ['eq_id' => 'ref']);
  }

  //Tool generalTable
  public function getGeneralTable()
  {
    if ($this->settings) {
      return $this->settings->eq_general;
    }
    return 0;
  }

  //Tool complex
  public function getComplex()
  {
    if ($this->settings) {
      return $this->settings->eq_complex;
    }
    return 0;
  }

  //Tool wrap
  public function getWrap()
  {
    if ($this->settings) {
      return $this->settings->eq_wrap;
    }
    return 0;
  }

  //Tool TO
  public function getTo()
  {
    return $this->hasOne(ToEquipment::class, ['eq_id' => 'ref']);
  }

  public function getToStatus()
  {
    if ($this->to) {
      return $this->to->valid;
    }
    return false;
  }

  //Tool task
  public function getTask()
  {
    if ($this->settings) {
      return $this->settings->eq_task;
    }
    return 0;
  }

  //Tool spec
  public function getSpecial()
  {
    return $this->hasOne(Special::class, ['eq_id' => 'ref']);
  }

  public function getSpecialStatus()
  {
    if ($this->special) {
      return $this->special->valid;
    }
    return false;
  }

  public function getSpecialStickerNumber()
  {
    if ($this->special) {
      return $this->special->sticker_number;
    }
    return false;
  }

  // Tool OTH
  public function getOth()
  {
    return $this->hasOne(Oth::class, ['eq_id' => 'ref']);
  }

  public function getOthStatus()
  {
    if ($this->oth) {
      return $this->oth->valid;
    }
    return false;
  }

  public function getOthTitleCheck()
  {
    if ($this->oth) {
      return $this->oth->eq_oth_title_on;
    }
    return false;
  }

  public function getOthTitle()
  {
    if ($this->oth) {
      return $this->oth->eq_oth_title;
    }
    return '';
  }

  // Wiki
  public function getWiki()
  {
    return $this->hasMany(Wiki::class, ['eq_id' => 'ref']);
  }

  public function getCountWikiPages()
  {
    return $this->hasMany(Wiki::class, ['eq_id' => 'ref'])->count();
  }

  // Документы
  public function getDocs()
  {
    return $this->hasMany(Docs::class, ['eq_id' => 'ref'])->orderBy(['doc_date' => SORT_ASC]);
  }

  public function getCountDocs()
  {
    return $this->hasMany(Docs::class, ['eq_id' => 'ref'])->count();
  }

  public function getDocsOrder()
  {
    return $this->getDocs()
      ->orderBy(['year' => SORT_ASC])
      ->all();
  }

  public function DocsYearFilter($year)
  {
    return $this->getDocs()
      ->where(['year' => $year])
      ->all();
  }

  public function getYearArrayDocs()
  {
    $years = $this->getDocs()
      ->select([
        'DATE_FORMAT(doc_date, "%Y") as year'
      ])
      ->orderBy('year Asc')
      ->distinct()
      ->asArray()
      ->all();
    $result = array();
    foreach ($years as $year){
      $result[] = $year['year'];
    }
    return $result;
  }

  public function getMonthsArrayDocs()
  {
    $monthArray = ['Январь', 'Февраль', 'Март', 'Апрель',
      'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
    $months = $this->getDocs()
      ->select([
        'DATE_FORMAT(doc_date, "%m") as month'
      ])
      ->orderBy('month Asc')
      ->asArray()
      ->all();
    $result = array();
    foreach ($months as $month){
      $str = ltrim($month['month'], '0');
      $result[] = $monthArray[$str];
      $str = ltrim($month['month']-1, '0');
      $result[] = $monthArray[$str];
    }
    return $result;
  }


  // Изображения
  public function getImages()
  {
    return $this->hasMany(Images::class, ['eq_id' => 'ref']);
  }

  public function getCountImages()
  {
    return $this->hasMany(Images::class, ['eq_id' => 'ref'])->count();
  }




  // Доступ к свойствам объекта
  public function getId()
  {
    return $this->id;
  }

  public function getEqTitle()
  {
    return $this->eq_title;
  }

  public function getEqManufact()
  {
    return $this->eq_manufact;
  }

  public function getEqModel()
  {
    return $this->eq_model;
  }

  public function getEqSerial()
  {
    return $this->eq_serial;
  }

  public function getFactDate()
  {
    if ($this->eq_factdate) {
      return strftime("%b %G", strtotime($this->eq_factdate)) . ' год';
    } else {
      return '-';
    }
  }

  public function getQuantity()
  {
    $ar = array();
    $length = 50;
    for ($i = 1; $i <= $length; $i++) {
      $ar[$i] = $i;
    }
    return $ar;
  }
//======================================================================================================================

  // DropDown lists
  public function getToolPlacesList()
  {
    $sql = "SELECT C1.ref, C1.name, C2.name as gr from " . self::PLACEMENT_TABLE . " C1 LEFT JOIN "
      . self::PLACEMENT_TABLE . " C2 on C1.parent_id = C2.ref WHERE C1.lvl > 1 ORDER BY C1.lft";
    return ArrayHelper::map($this->findBySql($sql)->asArray()->all(), 'ref', 'name', 'gr');
  }

  public function getToolCategoryList()
  {
    $sql = "SELECT C1.ref, C1.name, C2.name as gr from " . self::CATEGORY_TABLE . " C1 LEFT JOIN "
      . self::CATEGORY_TABLE . " C2 on C1.parent_id = C2.ref WHERE C1.lvl > 1 AND C1.root = 1 ORDER BY C1.lft";
    return ArrayHelper::map($this->findBySql($sql)->asArray()->all(), 'ref', 'name', 'gr');
  }

//======================================================================================================================
  /*  public function beforeSave($insert)
    {
      if (parent::beforeSave($insert)) {
        if ($this->eq_factdate) {
          $this->eq_factdate = strftime("%Y-%m-%d", strtotime($this->eq_factdate));
        }
        return parent::beforeSave($insert);
      } else {
        return false;
      }
    }*/

}
