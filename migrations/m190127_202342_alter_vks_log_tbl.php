<?php

use yii\db\Migration;

/**
 * Class m190127_202342_alter_vks_log_tbl
 */
class m190127_202342_alter_vks_log_tbl extends Migration
{
  const TABLE_NAME = '{{%vks_log_tbl}}';

  public function safeUp()
  {
    $this->addColumn(self::TABLE_NAME, 'status', $this->string(50));
  }
}
