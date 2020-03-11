<?php

namespace app\modules\vks\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{
  public function actionIndex()
  {
    return $this->render('default');
  }
}