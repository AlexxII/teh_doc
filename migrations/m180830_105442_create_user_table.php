<?php

use yii\db\Migration;

class m180830_105442_create_user_table extends Migration
{
  const TABLE_NAME = '{{%user}}';

  public function up()
  {
    $tableOptions = null;

    if ($this->db->driverName === 'mysql') {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
    }

    $this->createTable(self::TABLE_NAME, [
      'id' => $this->bigInteger()->notNull(),
      'username' => $this->string()->notNull()->unique(),
      'login' => $this->string(64),
      'auth_key' => $this->string(32)->notNull(),
      'password_hash' => $this->string()->notNull(),
      'password_reset_token' => $this->string()->unique(),
      'email' => $this->string()->notNull()->unique(),
      'status' => $this->smallInteger()->notNull()->defaultValue(10),
      'created_at' => $this->integer()->notNull(),
      'updated_at' => $this->integer()->notNull(),
    ], $tableOptions);

    $this->addPrimaryKey('id', self::TABLE_NAME, 'id');

  }

  public function down()
  {
    $this->dropTable(self::TABLE_NAME);
    return false;
  }
}
