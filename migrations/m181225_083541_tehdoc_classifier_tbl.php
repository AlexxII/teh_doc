<?php

use yii\db\Migration;

/**
 * Class m181225_083541_tehdoc_classifier_tbl
 */
class m181225_083541_tehdoc_classifier_tbl extends Migration
{
  const TABLE_NAME = '{{%teh_classifier_tbl}}';

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
      'parent_id' => $this->integer(),
      'clsf_table_name' => $this->string(255),
      'clsf_input_labels' => $this->string(255),
      'clsf_names' => $this->string(255),
      'clsf_types' => $this->string(255),
      'clsf_table_scheme' => $this->json(),
    ], $tableOptions);

    $this->addPrimaryKey('id', self::TABLE_NAME, 'id');

  }


  public function down()
  {
    $this->dropTable(self::TABLE_NAME);
    return false;
  }
}
