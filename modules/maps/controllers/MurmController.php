<?php

namespace app\modules\maps\controllers;

use Yii;
use yii\web\Controller;

class MurmController extends Controller
{

  public $layout = '@app/views/layouts/test.php';

  /*  public function actionIndex()
    {
  //    \Yii::$app->view->title = 'Карты';
      return $this->render('index');
    }*/

  public function actionIndex()
  {
    $tailIp = Yii::$app->params['tailIp'];
    return $this->render('_index', [
      'tailIp' => $tailIp
    ]);
  }

}