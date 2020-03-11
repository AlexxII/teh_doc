<?php

namespace app\modules\maps\models;

use yii\db\ActiveQuery;
use creocoder\nestedsets\NestedSetsQueryBehavior;

class RegionsQuery extends ActiveQuery
{
  public function behaviors() {
    return [
      NestedSetsQueryBehavior::class,
    ];
  }
}