<?php

use yii\db\Migration;

/**
 * Class m190317_162413_teh_to_type_tbl
 */
class m190317_162413_teh_to_type_tbl extends Migration
{

  const TABLE_NAME = '{{%teh_to_type_tbl}}';

  public function up()
  {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
    }
    $this->createTable(self::TABLE_NAME, [
      'id' => $this->primaryKey(),
      'ref' => $this->bigInteger()->notNull(),
      'root' => $this->integer(),
      'lft' => $this->integer()->notNull(),
      'rgt' => $this->integer()->notNull(),
      'lvl' => $this->smallInteger(5)->notNull(),
      'name' => $this->string(120)->notNull(),
      'valid' => $this->boolean()->defaultValue(1),
    ], $tableOptions);

    $rand = '1122334455';
    $sql = 'INSERT INTO' . self::TABLE_NAME . '(id, ref, root, lft, rgt, lvl, name) 
                VALUES (1, ' . $rand . ', 1, 1, 2, 0, "Виды ТО")';
    \Yii::$app->db->createCommand($sql)->execute();

  }

  public function down()
  {
    $this->dropTable(self::TABLE_NAME);
    return false;
  }

}
