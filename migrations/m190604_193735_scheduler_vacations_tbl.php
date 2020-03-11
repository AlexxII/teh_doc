<?php

use yii\db\Migration;

/**
 * Class m190604_193735_scheduler_vacations_tbl
 */
class m190604_193735_scheduler_vacations_tbl extends Migration
{
  const TABLE_NAME = '{{%scheduler_vacations_tbl}}';

  public function up()
  {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
    }
    $this->createTable(self::TABLE_NAME, [
      'id' => $this->bigInteger()->notNull(),
      'start_date' => $this->date()->notNull(),
      'end_date' => $this->date()->notNull(),
      'duration' => $this->integer()->notNull(),
      'user_id' => $this->bigInteger()->notNull(),
      'created_at' => $this->timestamp(),
      'updated_at' => $this->timestamp(),
      'valid' => $this->boolean()->defaultValue(1)
    ], $tableOptions);

    $this->addPrimaryKey('id', self::TABLE_NAME, 'id');

  }

  public function down()
  {
    $this->dropTable(self::TABLE_NAME);
    return false;
  }

}
