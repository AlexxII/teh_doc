<?php

namespace app\modules\polls\controllers\control;

use app\modules\admin\models\User;
use app\modules\polls\models\Result;
use Yii;
use yii\web\Controller;
use app\modules\polls\models\Polls;


class ResultsController extends Controller
{

  public $layout = 'ex_layout.php';

  public function actionIndex()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    Yii::$app->view->params['bUrl'] = 'polls';
    Yii::$app->view->params['title'] = 'Аналитика';
    return [
      'data' => [
        'success' => true,
        'data' => $this->render('index'),
        'message' => 'Page load',
      ],
      'code' => 1,
    ];
  }

  public function actionGetPollData($id)
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $data = Polls::find()
      ->select(["id", "title", "code"])
      ->where(["id" => $id])
      ->with(['questions.answers'])
      ->with(['results'])
      ->asArray()
      ->all();
    $respondents = Result::find()->select('respondent_id')->where(['poll_id' => $id])->distinct()->asArray()->all();
    $operators = Result::find()->select('user_id')->where(['poll_id' => $id])->distinct()->asArray()->all();
    $towns = Result::find()->select('town_id')->where(['poll_id' => $id])->distinct()->asArray()->all();
    $users = User::find()->asArray()->all();
    $data[0]["operators"] = $respondents;
    $data[0]["respondent"] = $respondents;
    $data[0]["towns"] = $towns;
    $data[0]["users"] = $users;
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