<?php

use yii\db\Migration;
use app\base\MHelper;

/**
 * Class m190314_190632_tehdoc_to_equipment_tbl
 */
class m190314_190632_tehdoc_to_equipment_tbl extends Migration
{

  const TABLE_NAME = '{{%teh_to_equipment_tbl}}';

  public function up()
  {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
    }
    $this->createTable(self::TABLE_NAME, [
      'id' => $this->bigInteger()->notNull(),
      'eq_id' => $this->bigInteger()->notNull(),
      'root' => $this->integer(),
      'lft' => $this->integer()->notNull(),
      'rgt' => $this->integer()->notNull(),
      'lvl' => $this->smallInteger(5)->notNull(),
      'name' => $this->string(120)->notNull(),
      'eq_serial' => $this->string(255),
      'parent_id' => $this->bigInteger(),
      'valid' => $this->boolean()->defaultValue(1),
    ], $tableOptions);

    $this->addPrimaryKey('id', self::TABLE_NAME, 'id');

    $defaultId = MHelper::genDefaultId();
    $sql = 'INSERT INTO ' . self::TABLE_NAME . '(id, eq_id, root, lft, rgt, lvl, name, parent_id) 
                VALUES (' . $defaultId . ',  0, ' . $defaultId . ', 1, 2, 0, "Оборудование",' . $defaultId . ')';
    \Yii::$app->db->createCommand($sql)->execute();

  }

  public function down()
  {
    $this->dropTable(self::TABLE_NAME);
    return false;
  }

}
