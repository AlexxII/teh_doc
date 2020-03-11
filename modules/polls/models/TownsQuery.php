<?php

namespace app\modules\polls\models;

use yii\db\ActiveQuery;
use creocoder\nestedsets\NestedSetsQueryBehavior;

class TownsQuery extends ActiveQuery
{
  public function behaviors() {
    return [
      NestedSetsQueryBehavior::class,
    ];
  }
}