<?php

use yii\db\Migration;

/**
 * Class m190306_142653_alter_tehdoc_settings_tbl
 */
class m190306_142653_alter_tehdoc_settings_tbl extends Migration
{

  const TABLE_NAME = '{{%teh_settings_tbl}}';

  public function safeUp()
  {
    $this->addColumn(self::TABLE_NAME, 'eq_complex', $this->boolean());
  }

}
