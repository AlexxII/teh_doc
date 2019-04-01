<?php

use yii\db\Migration;

/**
 * Class m190310_084359_teh_special_works_tbl
 */
class m190310_084359_tehdoc_special_works_tbl extends Migration
{

  const TABLE_NAME = '{{%teh_special_works_tbl}}';

  public function up()
  {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
    }
    $this->createTable(self::TABLE_NAME, [
      'id' => $this->primaryKey(),
      'eq_id' => $this->integer()->notNull(),
      'sticker_number' => $this->string(255),
      'valid' => $this->boolean()->defaultValue(1),
    ], $tableOptions);
  }

  public function down()
  {
    $this->dropTable(self::TABLE_NAME);
    return false;
  }

}
