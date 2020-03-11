<?php

use yii\db\Migration;

/**
 * Class m190602_163717_scheduler_events_tbl
 */
class m190602_163717_calendar_events_tbl extends Migration
{

  const TABLE_NAME = '{{%calendar_events_tbl}}';

  public function up()
  {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
    }
    $this->createTable(self::TABLE_NAME, [
      'id' => $this->bigInteger()->notNull(),
      'title' => $this->string(255),
      'description' => $this->string(255),
      'calendar_id' => $this->bigInteger()->notNull(),
      'user_id' => $this->bigInteger()->notNull(),
      'start_date' => $this->date()->notNull(),
      'end_date' => $this->date()->notNull(),
      'start_time' => $this->time(),
      'end_time' => $this->time(),

      'important' => $this->boolean()->defaultValue(0),
      'created_at' => $this->timestamp(),
      'updated_at' => $this->timestamp(),
      'done' => $this->boolean()->defaultValue(0),
      'is_deleted' => $this->boolean()->defaultValue(0)
    ], $tableOptions);

    $this->addPrimaryKey('id', self::TABLE_NAME, 'id');

  }

  public function down()
  {
    $this->dropTable(self::TABLE_NAME);
    return false;
  }

}