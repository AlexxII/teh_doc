<?php

namespace app\modules\maps\controllers;

use Yii;
use yii\web\Controller;

class DefaultController extends Controller
{

  public function actionIndex()
  {
//    \Yii::$app->view->title = 'Карты';
    return $this->render('default');
  }

}