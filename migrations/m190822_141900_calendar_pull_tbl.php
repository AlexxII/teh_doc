<?php

use yii\db\Migration;

/**
 * Class m190822_141900_calendars_pull_create
 */
class m190822_141900_calendar_pull_tbl extends Migration
{
  const TABLE_NAME = '{{%calendar_pull_tbl}}';

  public function safeUp()
  {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
    }
    $this->createTable(self::TABLE_NAME, [
      'id' => $this->bigInteger()->notNull(),
      'title' => $this->string(255),
      'description' => $this->string(255),
      'public' => $this->boolean()->defaultValue(0),
      'created_user' => $this->bigInteger(),
      'color' => $this->string('50'),
      'important' => $this->boolean()->defaultValue(0),
      'created_at' => $this->timestamp(),
      'updated_at' => $this->timestamp(),
      'is_deleted' => $this->boolean()->defaultValue(0)
    ], $tableOptions);

    $this->addPrimaryKey('id', self::TABLE_NAME, 'id');

  }

  public function safeDown()
  {
    echo "m190822_141900_calendars_pull_create cannot be reverted.\n";

    return false;
  }

}
