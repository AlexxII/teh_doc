<?php

use yii\db\Migration;
use app\base\MHelper;

class m190917_095519_maps_regions_tbl extends Migration
{
  const TABLE_NAME = '{{%maps_regions_tbl}}';

  public function safeUp()
  {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
    }
    $this->createTable(self::TABLE_NAME, [
      'id' => $this->bigInteger()->notNull(),
      'root' => $this->bigInteger(),
      'lft' => $this->integer()->notNull(),
      'rgt' => $this->integer()->notNull(),
      'lvl' => $this->smallInteger(5)->notNull(),
      'name' => $this->string(250)->notNull(),
      'parent_id' => $this->bigInteger(),
      'region_number' => $this->integer(),
      'region_center' => $this->string(255),
      'region_area' => $this->bigInteger(),
      'region_area_place' => $this->integer(),
      'region_population' => $this->bigInteger(),
      'region_population_place' => $this->integer(),
      'region_temp' => $this->float(),
      'icon' => $this->string(50),
      'valid' => $this->boolean()->defaultValue(1),
    ], $tableOptions);

    $this->addPrimaryKey('id', self::TABLE_NAME, 'id');

    $defaultId_1 = 30976400480;
    $defaultId_2 = 78898343747;
    $defaultId_3 = 205321542553;
    $defaultId_4 = 291077377916;
    $defaultId_5 = 545147568136;
    $defaultId_6 = 794270165344;
    $defaultId_7 = 954752120491;
    $defaultId_8 = 1086763915908;

    $sql = 'INSERT INTO ' . self::TABLE_NAME . ' (id, root, lft, rgt, lvl, name, parent_id) 
                VALUES (' . $defaultId_1 . ', ' . $defaultId_1 . ', 1, 2, 0, "Ценральный федеральный округ", ' . $defaultId_1 . '), 
                (' . $defaultId_2 . ', ' . $defaultId_2 . ', 3, 4, 0, "Северо-Западный федеральный округ", ' . $defaultId_2 . '),
                (' . $defaultId_3 . ', ' . $defaultId_3 . ', 5, 6, 0, "Южный федеральный округ", ' . $defaultId_3 . '),
                (' . $defaultId_4 . ', ' . $defaultId_4 . ', 7, 8, 0, "Северо-Кавказский федеральный округ", ' . $defaultId_4 . '),
                (' . $defaultId_5 . ', ' . $defaultId_5 . ', 9, 10, 0, "Приволжский федеральный округ", ' . $defaultId_5 . '),
                (' . $defaultId_6 . ', ' . $defaultId_6 . ', 11, 12, 0, "Уральский федеральный округ", ' . $defaultId_6 . '),
                (' . $defaultId_7 . ', ' . $defaultId_7 . ', 13, 14, 0, "Сибирский федеральный округ", ' . $defaultId_7 . '),
                (' . $defaultId_8 . ', ' . $defaultId_8 . ', 15, 16, 0, "Дельневосточный федеральный округ", ' . $defaultId_8 . ')'
    ;
    \Yii::$app->db->createCommand($sql)->execute();
  }

  public function safeDown()
  {
    echo "m190917_095519_maps_regions_tbl cannot be reverted.\n";

    return false;
  }

}
