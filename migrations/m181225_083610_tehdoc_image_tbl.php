<?php

use yii\db\Migration;

/**
 * Class m181225_083610_tehdoc_image_tbl
 */
class m181225_083610_tehdoc_image_tbl extends Migration
{
  const TABLE_NAME = '{{%teh_image_tbl}}';

  public function up()
  {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
    }
    $this->createTable(self::TABLE_NAME, [
      'id' => $this->primaryKey(),
      'eq_id' => $this->integer()->notNull(),
      'image_path' => $this->string(255)->notNull()->unique(),
      'image_extention' => $this->string(),
      'upload_time' => $this->datetime(),
      'upload_user' => $this->integer(),
      'valid' => $this->boolean()->notNull()->defaultValue(1),
    ], $tableOptions);
  }

  public function down()
  {
    $this->dropTable(self::TABLE_NAME);
    return false;
  }
}
