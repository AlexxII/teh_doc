<?php

use yii\db\Migration;

/**
 * Class m190316_201504_teh_to_schedule_tbl
 */
class m190316_201504_teh_to_schedule_tbl extends Migration
{
  const TABLE_NAME = '{{%teh_to_schedule_tbl}}';

  public function up()
  {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
    }
    $this->createTable(self::TABLE_NAME, [
      'id' => $this->bigInteger()->notNull(),
      'schedule_id' => $this->bigInteger()->notNull(),
      'eq_id' => $this->bigInteger()->notNull(),
      'to_type' => $this->bigInteger()->notNull(),
      'plan_date' => $this->date()->notNull(),
      'fact_date' => $this->date(),
      'checkmark' => $this->boolean()->defaultValue(0),
      'date_in' => $this->dateTime(),
      'to_month' => $this->date(),
      'admin_id' => $this->bigInteger()->notNull(),
      'auditor_id' => $this->bigInteger()->notNull(),
      'valid' => $this->boolean()->defaultValue(1),
    ], $tableOptions);

    $this->addPrimaryKey('id', self::TABLE_NAME, 'id');

  }

  public function down()
  {
    $this->dropTable(self::TABLE_NAME);
    return false;
  }
}
