<?php

use yii\db\Migration;

/**
 * Class m181225_083558_tehdoc_equipment_tbl
 */
class m181225_083558_tehdoc_equipment_tbl extends Migration
{
  const TABLE_NAME = '{{%teh_equipment_tbl}}';

  public function safeUp()
  {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
    }
    $this->createTable(self::TABLE_NAME, [
      'id' => $this->primaryKey(),
      'ref' => $this->bigInteger(),
      'root' => $this->integer(),
      'lft' => $this->integer()->notNull(),
      'rgt' => $this->integer()->notNull(),
      'lvl' => $this->smallInteger(5)->notNull(),
      'name' => $this->string(120)->notNull(),
      'parent_id' => $this->bigInteger(),
      'category_id' => $this->integer(),
      'place_id' => $this->integer(),
      'eq_title' => $this->string(255),
      'eq_manufact' => $this->string(255),
      'eq_model' => $this->string(255),
      'eq_serial' => $this->string(255),
      'eq_factdate' => $this->date(),
      'quantity' => $this->smallInteger()->notNull()->defaultValue(1),
      'eq_comments' => $this->text(),
      'valid' => $this->boolean()->defaultValue(1),
      'eq_task' => $this->boolean()
    ], $tableOptions);

    $rand = '1122334455';
    $ranD = '5544332211';
    $sql = 'INSERT INTO' . self::TABLE_NAME . '(id, ref, root, lft, rgt, lvl, name, parent_id, quantity, eq_comments, valid) 
                VALUES (1, ' . $rand . ', 1, 1, 2, 0, "Оборудование",' . $rand . ', 1, null, 1), 
                (2, ' . $ranD . ', 2, 3, 4, 0, "Необработанное",' . $ranD . ', 1, null, 1)';
    \Yii::$app->db->createCommand($sql)->execute();
  }

  public function safeDown()
  {
    $this->dropTable(self::TABLE_NAME);
    return false;
  }
}
