<?php

use yii\db\Migration;

/**
 * Class m190606_121108_alter_user_tbl
 */
class m190606_121108_alter_user_tbl extends Migration
{

  const TABLE_NAME = '{{%user}}';

  public function Up()
  {
    $this->addColumn(self::TABLE_NAME, 'color_scheme', $this->string(64)->after('email'));
  }

}
