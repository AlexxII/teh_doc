<?php

namespace app\modules\tehdoc\models;

use creocoder\nestedsets\NestedSetsQueryBehavior;
use yii\db\ActiveQuery;

class CategoryQuery extends ActiveQuery
{

  public function behaviors() {
    return [
        NestedSetsQueryBehavior::class,
    ];
  }

}