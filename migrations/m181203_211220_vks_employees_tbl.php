<?php

use yii\db\Migration;
use app\base\MHelper;


class m181203_211220_vks_employees_tbl extends Migration
{
  const TABLE_NAME = '{{%vks_employees_tbl}}';

  public function up()
  {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
    }
    $this->createTable(self::TABLE_NAME, [
      'id' => $this->bigInteger()->notNull(),
      'root' => $this->bigInteger(),
      'lft' => $this->integer()->notNull(),
      'rgt' => $this->integer()->notNull(),
      'lvl' => $this->smallInteger(5)->notNull(),
      'name' => $this->string(120)->notNull(),
      'parent_id' => $this->bigInteger(),
      'valid' => $this->boolean()->defaultValue(1),
      'del_reason' => $this->string(255),
      'icon' => $this->string(50),
      'list' => $this->boolean()
    ], $tableOptions);

    $this->addPrimaryKey('id', self::TABLE_NAME, 'id');

    $defaultId_1 = MHelper::genDefaultId();
    $defaultId_2 = MHelper::genDefaultId();
    if ($defaultId_2 < $defaultId_1){
      list($defaultId_1, $defaultId_2) = array($defaultId_2, $defaultId_1);
    }
    $sql = 'INSERT INTO ' . self::TABLE_NAME . ' (id, root, lft, rgt, lvl, name, parent_id, list) 
                VALUES (' . $defaultId_1 . ', ' . $defaultId_1 . ', 1, 2, 0, "Сотрудники, обеспечивающие ВКС", ' . $defaultId_1 . ', 1), 
                (' . $defaultId_2 . ', ' . $defaultId_2 . ', 3, 4, 0, "Сотрудники, передающие сообщ-ия", ' . $defaultId_2 . ', 2)';
    \Yii::$app->db->createCommand($sql)->execute();
  }

  public function down()
  {
    $this->dropTable(self::TABLE_NAME);
    return false;
  }
}