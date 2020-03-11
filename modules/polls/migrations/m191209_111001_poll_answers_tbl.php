<?php

use yii\db\Migration;

class m191209_111001_poll_answers_tbl extends Migration
{

  const TABLE_NAME = '{{%poll_answers_tbl}}';

  public function safeUp()
  {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
    }
    $this->createTable(self::TABLE_NAME, [
      'id' => $this->bigInteger()->notNull(),
      'poll_id' => $this->bigInteger()->notNull(),
      'question_id' => $this->bigInteger()->notNull(),
      'title' => $this->string(500),
      'title_ex' => $this->string(255),                                  // коротко
      'code' => $this->string(125),
      'order' => $this->integer(),                                       // порядок
      'input_type' => $this->integer(),                                  // тип ответа
      'jump' => $this->bigInteger()->defaultValue(0),                    // переход
      'unique' => $this->boolean()->defaultValue(0),                     // уникальный для вопроса
      'visible' => $this->boolean()->defaultValue(1),
      'isDeleted' => $this->boolean()->defaultValue(0)
    ], $tableOptions);

    $this->addPrimaryKey('id', self::TABLE_NAME, 'id');

  }

  public function safeDown()
  {
    echo "m191209_111001_poll_answers_tbl cannot be reverted.\n";

    return false;
  }

}
