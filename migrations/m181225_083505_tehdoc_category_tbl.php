<?php

use yii\db\Migration;

/**
 * Class m181225_083505_tehdoc_category_tbl
 */
class m181225_083505_tehdoc_category_tbl extends Migration
{
  const TABLE_NAME = '{{%teh_category_tbl}}';

  public function up()
  {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
    }
    $this->createTable(self::TABLE_NAME, [
      'id' => $this->bigPrimaryKey(),
      'ref' => $this->integer(),
      'root' => $this->integer(),
      'lft' => $this->integer()->notNull(),
      'rgt' => $this->integer()->notNull(),
      'lvl' => $this->smallInteger(5)->notNull(),
      'name' => $this->string(120)->notNull(),
      'parent_id' => $this->integer(),
      'valid' => $this->boolean()->defaultValue(1),
      'del_reason' => $this->string(255)
    ], $tableOptions);

    $rand = mt_rand();
    $sql = 'INSERT INTO' . self::TABLE_NAME . '(id, ref, root, lft, rgt, lvl, name, parent_id) 
                VALUES (1, ' . $rand . ', 1, 1, 2, 0, "Категории", ' . $rand . ')';
    \Yii::$app->db->createCommand($sql)->execute();
  }

  public function down()
  {
    $this->dropTable(self::TABLE_NAME);
    return false;
  }

}
