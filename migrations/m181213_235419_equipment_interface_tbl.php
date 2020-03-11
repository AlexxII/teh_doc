<?php

use yii\db\Migration;
use app\base\MHelper;

class m181213_235419_equipment_interface_tbl extends Migration
{
  const TABLE_NAME = '{{%equipment_interface_tbl}}';

  public function up()
  {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
    }
    $this->createTable(self::TABLE_NAME, [
      'id' => $this->bigInteger()->notNull(),
      'name' => $this->string(120)->notNull(),
      'text' => $this->text()
    ], $tableOptions);

    $this->addPrimaryKey('id', self::TABLE_NAME, 'id');

    $defaultId_1 = MHelper::genDefaultId();
    $defaultId_2 = MHelper::genDefaultId();
    $sql = 'INSERT INTO ' . self::TABLE_NAME . '(id, name, text) 
                VALUES (' . $defaultId_1 . ', "Производители", NULL), (' . $defaultId_2 . ', "Модели", NULL)';
    \Yii::$app->db->createCommand($sql)->execute();
  }

  public function down()
  {
    $this->dropTable(self::TABLE_NAME);
    return false;
  }
}
