<?php

use yii\db\Migration;

/**
 * Class m190306_043308_tehdoc_settings_tbl
 */
class m190306_043308_tehdoc_settings_tbl extends Migration
{
  const TABLE_NAME = '{{%teh_settings_tbl}}';

  public function up()
  {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
    }
    $this->createTable(self::TABLE_NAME, [
      'id' => $this->bigInteger()->notNull(),
      'eq_id' => $this->bigInteger()->notNull(),
      'eq_task' => $this->boolean()->defaultValue(0),
      'eq_general' => $this->boolean()->defaultValue(0),
      'eq_oth' => $this->boolean()->defaultValue(0),
      'eq_special' => $this->boolean()->defaultValue(0),
      'eq_complex' => $this->boolean()->defaultValue(0),
      'eq_wrap' => $this->boolean()->defaultValue(0),
      'eq_to' => $this->boolean()->defaultValue(0),
      'valid' => $this->boolean()->defaultValue(1)
    ], $tableOptions);

    $this->addPrimaryKey('id', self::TABLE_NAME, 'id');

//    $sql = 'insert into teh_settings_tbl(eq_id)
//            select ref from teh_equipment_tbl where ref != 1122334455 AND ref != 5544332211';
//    \Yii::$app->db->createCommand($sql)->execute();

  }

  public function down()
  {
    $this->dropTable(self::TABLE_NAME);
    return false;
  }


}
