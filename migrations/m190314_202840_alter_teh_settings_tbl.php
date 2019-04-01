<?php

use yii\db\Migration;

/**
 * Class m190314_202840_alter_teh_settings_tbl
 */
class m190314_202840_alter_teh_settings_tbl extends Migration
{

  const TABLE_NAME = '{{%teh_settings_tbl}}';

  public function safeUp()
  {
    $this->addColumn(self::TABLE_NAME, 'eq_to', $this->boolean()->defaultValue(0));
  }

}
