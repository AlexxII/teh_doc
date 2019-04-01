<?php

namespace app\assets;

class PhotoswipeAsset extends \yii\web\AssetBundle
{
  public $sourcePath = '@bower/photoswipe';
  public $skin = 'dist/default-skin/default-skin';                       // скин для дерева

  public $depends = [
    'yii\web\JqueryAsset',
    'yii\jui\JuiAsset'
  ];

  protected function setupAssets($type, $files = [])
  {
    $srcFiles = [];
    $minFiles = [];
    foreach ($files as $file) {
      $srcFiles[] = "{$file}.{$type}";
      $minFiles[] = "{$file}.min.{$type}";
    }
    if (empty($this->$type)) {
      $this->$type = YII_DEBUG ? $srcFiles : $minFiles;
    }
  }

  public function init()
  {
    $this->setupAssets('css', [$this->skin]);
    $this->setupAssets('js', ['dist/photoswipe', 'dist/photoswipe-ui-default']);
    parent::init();
  }
}
