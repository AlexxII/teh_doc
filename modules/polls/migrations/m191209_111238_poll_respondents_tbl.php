<?php

use yii\db\Migration;

/**
 * Class m191209_111238_poll_respondents_tbl
 */
class m191209_111238_poll_respondents_tbl extends Migration
{
  const TABLE_NAME = '{{%poll_respondents_tbl}}';

  public function safeUp()
  {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
    }
    $this->createTable(self::TABLE_NAME, [
      'id' => $this->bigInteger()->notNull(),
      'respondent_id' => $this->bigInteger()->notNull(),
      'poll_id' => $this->bigInteger()->notNull(),
      'answer_id' => $this->bigInteger()->notNull(),
      'answer_code' => $this->string(125),
      'user_id' => $this->bigInteger()->notNull(),
      'input_time' => $this->timestamp(),                                         // время занесения результата
      'ex_answer' => $this->text(),
      'order' => $this->integer(),
      'town_id' => $this->bigInteger()->notNull(),
      'isDeleted' => $this->boolean()->defaultValue(0)
    ], $tableOptions);

    $this->addPrimaryKey('id', self::TABLE_NAME, 'id');
  }

  public function safeDown()
  {
    echo "m191209_111238_poll_respondents_tbl cannot be reverted.\n";

    return false;
  }

}
