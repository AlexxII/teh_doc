<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;

class StudyController extends Controller
{
  public function actionJava()
  {
    return $this->render('study');
  }

}
