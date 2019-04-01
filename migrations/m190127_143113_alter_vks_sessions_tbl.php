<?php

use yii\db\Migration;

/**
 * Class m190127_143113_alter_vks_sessions_tbl
 */
class m190127_143113_alter_vks_sessions_tbl extends Migration
{
  const TABLE_NAME = '{{%vks_sessions_tbl}}';

  public function safeUp()
  {
    $this->addColumn(self::TABLE_NAME, 'combined', $this->boolean()->defaultValue(false));
    $this->alterColumn(self::TABLE_NAME, 'vks_cancel', $this->boolean()->defaultValue(false));
  }
}
