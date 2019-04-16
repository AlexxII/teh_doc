<?php

use yii\db\Migration;

/**
 * Class m190222_144636_tehdoc_docs_tbl
 */
class m190222_144636_tehdoc_docs_tbl extends Migration
{
  const TABLE_NAME = '{{%teh_docs_tbl}}';

  public function up()
  {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
    }
    $this->createTable(self::TABLE_NAME, [
      'id' => $this->bigInteger()->notNull(),
      'eq_id' => $this->bigInteger()->notNull(),
      'doc_title' => $this->string(255),
      'doc_path' => $this->string(255)->notNull()->unique(),
      'doc_extention' => $this->string(),
      'doc_date' => $this->datetime(),
      'year' => $this->integer(),
      'upload_time' => $this->datetime(),
      'upload_user' => $this->bigInteger(),
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
