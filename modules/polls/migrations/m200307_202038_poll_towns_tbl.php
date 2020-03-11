<?php

use yii\db\Migration;
use app\base\MHelper;

/**
 * Class m200307_202038_poll_towns_tbl
 */
class m200307_202038_poll_towns_tbl extends Migration
{

  const TABLE_NAME = '{{%poll_towns_tbl}}';

  public function safeUp()
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
      'name' => $this->string(250)->notNull(),
      'parent_id' => $this->bigInteger(),
      'selected' =>$this->boolean(),
      'valid' => $this->boolean()->defaultValue(1),
    ], $tableOptions);

    $this->addPrimaryKey('id', self::TABLE_NAME, 'id');

    $defaultId = MHelper::genDefaultId();
    $sql = 'INSERT INTO ' . self::TABLE_NAME . '(id, root, lft, rgt, lvl, name, parent_id) 
                VALUES (' . $defaultId . ', ' . $defaultId . ', 1, 2, 0, "Места проведения опросов",' . $defaultId . ')';
    \Yii::$app->db->createCommand($sql)->execute();


  }

  public function safeDown()
  {
    echo "m200307_202038_poll_towns_tbl cannot be reverted.\n";

    return false;
  }

}
