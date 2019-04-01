<?php

use yii\db\Migration;

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
      'id' => $this->primaryKey(),
      'ref' => $this->bigInteger(),
      'eq_id' => $this->integer()->notNull(),
      'root' => $this->integer(),
      'lft' => $this->integer()->notNull(),
      'rgt' => $this->integer()->notNull(),
      'lvl' => $this->smallInteger(5)->notNull(),
      'name' => $this->string(120)->notNull(),
      'eq_serial' => $this->string(255),
      'parent_id' => $this->bigInteger(),
      'valid' => $this->boolean()->defaultValue(1),
    ], $tableOptions);

    $rand = '1122334455';
    $sql = 'INSERT INTO' . self::TABLE_NAME . '(id, ref, eq_id, root, lft, rgt, lvl, name, parent_id) 
                VALUES (1, ' . $rand . ', 0, 1, 1, 2, 0, "Оборудование",' . $rand . ')';
    \Yii::$app->db->createCommand($sql)->execute();

  }

  public function down()
  {
    $this->dropTable(self::TABLE_NAME);
    return false;
  }

}
