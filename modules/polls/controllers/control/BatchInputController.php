<?php

namespace app\modules\polls\controllers\control;

use Yii;
use yii\web\Controller;
use app\modules\polls\models\Polls;

class BatchInputController extends Controller
{

  public $layout = 'ex_layout.php';

  public function actionIndex()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    Yii::$app->view->params['title'] = 'Пакетный ввод';
    return [
      'data' => [
        'success' => true,
        'data' => $this->render('index'),
        'message' => 'Rendering',
      ],
      'code' => 1,
    ];
  }

  public function actionGetPollInfo($id)
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $data = Polls::find()
      ->select(["id", "title", "code"])
      ->where(["id" => $id])
      ->with(['questions.answers.logic'])
      ->asArray()
      ->all();
    return [
      'data' => [
        'success' => true,
        'data' => $data,
        'message' => 'Poll info - take it',
      ],
      'code' => 1,
    ];
  }

}