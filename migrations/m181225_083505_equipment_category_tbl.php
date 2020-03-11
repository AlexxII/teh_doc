<?php

use yii\db\Migration;
use app\base\MHelper;

/**
 * Class m181225_083505_tehdoc_category_tbl
 */
class m181225_083505_equipment_category_tbl extends Migration
{
  const TABLE_NAME = '{{%equipment_category_tbl}}';

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
      'icon' => $this->string(50)
    ], $tableOptions);

    $this->addPrimaryKey('id', self::TABLE_NAME, 'id');

    $defaultId = MHelper::genDefaultId();
    $icon = 'fa fa-list-alt';
    $sql = 'INSERT INTO ' . self::TABLE_NAME . ' (id, root, lft, rgt, lvl, name, parent_id, icon) 
                VALUES (' . $defaultId . ', ' . $defaultId . ', 1, 2, 0, "Категории", ' . $defaultId . ', "' . $icon . '")';
    \Yii::$app->db->createCommand($sql)->execute();
  }

  public function down()
  {
    $this->dropTable(self::TABLE_NAME);
    return false;
  }

}
