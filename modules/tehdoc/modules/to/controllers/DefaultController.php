<?php

namespace app\modules\tehdoc\modules\to\controllers;

use app\modules\tehdoc\modules\equipment\models\SSPEx;
use app\modules\tehdoc\modules\equipment\models\ToolSettings;
use Yii;
use yii\web\Controller;
use app\modules\tehdoc\modules\equipment\models\Tools;
use app\modules\tehdoc\modules\equipment\models\SSP;
use app\modules\tehdoc\modules\equipment\models\Images;
use yii\web\UploadedFile;

class DefaultController extends Controller
{

  public function actionIndex()
  {
    return $this->render('index');
  }

}