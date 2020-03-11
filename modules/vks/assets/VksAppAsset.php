<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\vks\assets;

use yii\web\AssetBundle;

class VksAppAsset extends AssetBundle
{
  public $sourcePath = '@app/modules/vks/lib';

  public $css = [
    'css/vks_main_layout.css',
  ];

  public $js = [
    'js/vks.js',
    'js/default_page.js',
    'js/archive_page.js',
    'js/analytics_page.js'
  ];
}
