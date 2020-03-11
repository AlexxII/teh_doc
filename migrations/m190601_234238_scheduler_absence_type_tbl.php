<?php

use yii\db\Migration;
use app\base\MHelper;

/**
 * Class m190528_112418_people_absence_type_tbl
 */
class m190601_234238_scheduler_absence_type_tbl extends Migration
{
  const TABLE_NAME = '{{%scheduler_absence_type_tbl}}';

  public function up()
  {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
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
    ], $tableOptions);

    $this->addPrimaryKey('id', self::TABLE_NAME, 'id');

    $defaultId = MHelper::genDefaultId();
    $sql = 'INSERT INTO ' . self::TABLE_NAME . '(id, root, lft, rgt, lvl, name) 
                VALUES (' . $defaultId . ', ' . $defaultId . ', 1, 2, 0, "Причины отсутствия")';
    \Yii::$app->db->createCommand($sql)->execute();

  }

  public function down()
  {
    $this->dropTable(self::TABLE_NAME);
    return false;
  }

}
