<?php

use yii\db\Migration;

/**
 * Class m200217_200538_poll_logic_tbl
 */
class m200217_200538_poll_logic_tbl extends Migration
{

  const TABLE_NAME = '{{%poll_logic_tbl}}';

  public function safeUp()
  {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
    }
    $this->createTable(self::TABLE_NAME, [
      'id' => $this->bigInteger()->notNull(),
      'poll_id' => $this->bigInteger()->notNull(),
      'answer_id' => $this->bigInteger()->notNull(),
      'restrict_id' => $this->bigInteger()->notNull(),
      'restrict_type' => $this->boolean()->notNull(),
      'isDeleted' => $this->boolean()->defaultValue(0)
    ], $tableOptions);

    $this->addPrimaryKey('id', self::TABLE_NAME, 'id');

  }

  public function safeDown()
  {
    echo "m200217_200538_poll_logic_tbl cannot be reverted.\n";

    return false;
  }

}
