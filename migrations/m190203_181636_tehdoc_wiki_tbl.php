<?php

use yii\db\Migration;

/**
 * Class m190203_181636_tehdoc_wiki_tbl
 */
class m190203_181636_tehdoc_wiki_tbl extends Migration
{
  const TABLE_NAME = '{{%teh_wiki_tbl}}';

  public function safeUp()
  {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
    }
    $this->createTable(self::TABLE_NAME, [
      'id' => $this->bigInteger()->notNull(),
      'eq_id' => $this->bigInteger()->notNull(),
      'wiki_title' => $this->string(255),
      'wiki_text' => $this->text(),
      'wiki_record_create' => $this->dateTime(),
      'wiki_record_update' => $this->dateTime(),
      'wiki_created_user' => $this->bigInteger(),
      'valid' => $this->boolean()->defaultValue(1)
    ], $tableOptions);

    $this->addPrimaryKey('id', self::TABLE_NAME, 'id');

  }

  public function safeDown()
  {
    $this->dropTable(self::TABLE_NAME);
    return false;
  }
}
