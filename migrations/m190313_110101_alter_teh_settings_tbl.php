<?php

use yii\db\Migration;

/**
 * Class m190313_110101_alter_teh_settings_tbl
 */
class m190313_110101_alter_teh_settings_tbl extends Migration
{
  const TABLE_NAME = '{{%teh_settings_tbl}}';

  public function safeUp()
  {
    $this->addColumn(self::TABLE_NAME, 'eq_wrap', $this->boolean()->defaultValue(0));
  }

}
