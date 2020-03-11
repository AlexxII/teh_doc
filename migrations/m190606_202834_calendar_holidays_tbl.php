<?php

use yii\db\Migration;

/**
 * Class m190606_202834_scheduler_holidays_tbl
 */
class m190606_202834_calendar_holidays_tbl extends Migration
{
  const TABLE_NAME = '{{%calendar_holidays_tbl}}';

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
      'startDay' => $this->integer(),
      'startMon' => $this->integer(),
      'startYear' => $this->integer(),
      'endDay' => $this->integer(),
      'endMon' => $this->integer(),
      'endYear' => $this->integer(),
      'duration' => $this->integer()->notNull(),
      'title' =>$this->string(255),
      'stDateStr' => $this->string(64),
      'eDateStr' => $this->string(64),
      'description' => $this->string(255),
      'holiday_type' => $this->smallInteger(),
      'approval_year' => $this->integer(),
      'year_repeat' => $this->smallInteger(),
      'created_user' => $this->bigInteger()->notNull(),
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
