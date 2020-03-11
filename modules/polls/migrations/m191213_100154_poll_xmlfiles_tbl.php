<?php

use yii\db\Migration;


class m191213_100154_poll_xmlfiles_tbl extends Migration
{

  const TABLE_NAME = '{{%poll_xmlfiles_tbl}}';

  public function safeUp()
  {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
    }
    $this->createTable(self::TABLE_NAME, [
      'id' => $this->bigInteger()->notNull(),
      'poll_id' => $this->bigInteger()->notNull(),
      'title' => $this->string(255),
      'with_errors' => $this->boolean()->defaultValue(0)
    ], $tableOptions);

    $this->addPrimaryKey('id', self::TABLE_NAME, 'id');

  }


  public function safeDown()
  {
    echo "m191213_100154_poll_xmlfiles_tbl cannot be reverted.\n";

    return false;
  }
}
