<?php

use yii\db\Migration;

/**
 * Class m190502_065623_teh_to_year_schedule_tbl
 */
class m190502_065623_to_year_schedule_tbl extends Migration
{
  const TABLE_NAME = '{{%to_year_schedule_tbl}}';

  public function up()
  {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
    }
    $this->createTable(self::TABLE_NAME, [
      'id' => $this->bigPrimaryKey(),
      'eq_id' => $this->bigInteger(),
      'schedule_year' => $this->integer(),
      'm0' => $this->bigInteger(),
      'm1' => $this->bigInteger(),
      'm2' => $this->bigInteger(),
      'm3' => $this->bigInteger(),
      'm4' => $this->bigInteger(),
      'm5' => $this->bigInteger(),
      'm6' => $this->bigInteger(),
      'm7' => $this->bigInteger(),
      'm8' => $this->bigInteger(),
      'm9' => $this->bigInteger(),
      'm10' => $this->bigInteger(),
      'm11' => $this->bigInteger(),
      'valid' => $this->boolean()->defaultValue(1)
    ], $tableOptions);

  }

  public function down()
  {
    $this->dropTable(self::TABLE_NAME);
    return false;
  }
}
