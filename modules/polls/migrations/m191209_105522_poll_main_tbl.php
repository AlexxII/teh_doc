<?php

use yii\db\Migration;

/**
 * Class m191209_105522_poll_main_tbl
 */
class m191209_105522_poll_main_tbl extends Migration
{

  const TABLE_NAME = '{{%poll_main_tbl}}';

  public function safeUp()
  {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
    }
    $this->createTable(self::TABLE_NAME, [
      'id' => $this->bigInteger()->notNull(),
      'title' => $this->string(500),
      'start_date' => $this->date(),
      'end_date' => $this->date(),
      'code' => $this->string(125),
      'sample' => $this->integer(),                                       // выборка
      'elections' => $this->boolean()->defaultValue(0),                   // выборный
      'created_user' => $this->bigInteger(),                              // создатель
      'poll_comments' => $this->text(),
      'poll_record_create' => $this->dateTime(),
      'poll_record_update' => $this->dateTime(),
      'visible' => $this->boolean()->defaultValue(1),
      'isDeleted' => $this->boolean()->defaultValue(0)
    ], $tableOptions);

    $this->addPrimaryKey('id', self::TABLE_NAME, 'id');

  }

  public function safeDown()
  {
    echo "m191209_105522_poll_main_tbl cannot be reverted.\n";

    return false;
  }
}
