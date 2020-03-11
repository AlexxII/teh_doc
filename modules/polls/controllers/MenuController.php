<?php

namespace app\modules\polls\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class MenuController extends Controller
{
  public function actionLeftSide()
  {
    return $this->renderAjax('left_menu');
  }

  public function actionAppConfig()
  {
    return $this->renderAjax('app_config_menu');
  }
}