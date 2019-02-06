<?php

use yii\db\Migration;

/**
 * Class m190203_115948_tehdoc_c_test_tbl
 */
class m190203_115948_tehdoc_c_test_tbl extends Migration
{
  const TABLE_NAME = '{{%teh_c_test_tbl}}';

  public function safeUp()
  {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
    }
    $this->createTable(self::TABLE_NAME, [
      'id' => $this->primaryKey(),
      'ref' => $this->integer(),
      'key' => $this->integer(),
      'root' => $this->integer(),
      'lft' => $this->integer()->notNull(),
      'rgt' => $this->integer()->notNull(),
      'lvl' => $this->smallInteger(5)->notNull(),
      'name' => $this->string(120)->notNull(),
      'parent_id' => $this->integer(),
      'category_id' => $this->integer(),
      'place_id' => $this->integer(),
      'complex_title' => $this->string(255),
      'complex_serial' => $this->string(255),
      'complex_manufact' => $this->string(255),
      'complex_model' => $this->string(255),
      'complex_factdate' => $this->date(),
      'quantity' => $this->smallInteger()->notNull()->defaultValue(1),
      'complex_comments' => $this->text(),
      'valid' => $this->boolean()->defaultValue(1)
    ], $tableOptions);

    $rand = '1122334455';
    $sql = 'INSERT INTO' . self::TABLE_NAME . '(id, ref, key, root, lft, rgt, lvl, name, parent_id, quantity, complex_comments, valid) 
                VALUES (1, ' . $rand . ', ' . $rand . ', 1, 1, 2, 0, "Оборудование",' . $rand . ', 1, null, 1)';
    \Yii::$app->db->createCommand($sql)->execute();
  }

  public function safeDown()
  {
    $this->dropTable(self::TABLE_NAME);
    return false;
  }
}
