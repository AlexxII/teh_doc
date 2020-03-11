<?php

namespace app\modules\scheduler\controllers;

use Yii;
use yii\web\Controller;

class ProductionController extends Controller
{

  public $layout = '@app/views/layouts/main_ex.php';

  public function actionIndex(){
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    return [
      'data' => [
        'success' => true,
        'data' => $this->renderAjax('index'),
        'message' => 'Page load.',
      ],
      'code' => 1,
    ];
  }


}