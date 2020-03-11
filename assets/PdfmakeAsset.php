<?php

namespace app\assets;

use yii\web\AssetBundle;

class PdfmakeAsset extends AssetBundle
{
  public $sourcePath = '@bower/pdfmake/build';

  public $js = [
    'pdfmake.min.js',
    'vfs_fonts.js'
  ];

}
