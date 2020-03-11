<?php

use yii\db\Migration;

/**
 * Class m190825_143541_calendar_users_settings_tbl
 */
class m190825_143541_calendar_users_settings_tbl extends Migration
{
  const TABLE_NAME = '{{%calendar_users_settings_tbl}}';

  public function up()
  {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
    }
    $this->createTable(self::TABLE_NAME, [
      'id' => $this->bigInteger()->notNull(),
      'user_id' => $this->bigInteger()->notNull(),
      'calendar' => $this->bigInteger(),
      'is_deleted' => $this->boolean()->defaultValue(0),
    ], $tableOptions);

    $this->addPrimaryKey('id', self::TABLE_NAME, 'id');

  }

  public function down()
  {
    $this->dropTable(self::TABLE_NAME);
    return false;
  }
}
