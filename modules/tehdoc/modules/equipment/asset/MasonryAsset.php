<?php

namespace app\modules\tehdoc\modules\equipment\asset;

use yii\web\AssetBundle;

class MasonryAsset extends AssetBundle
{
  public $sourcePath = '@app/modules/tehdoc/modules/equipment/lib';

  public $css = [
    'css/driveway.blog.min.css',
    'css/driveway.min.css',
  ];
}
