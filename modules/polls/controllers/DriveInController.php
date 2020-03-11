<?php

namespace app\modules\polls\controllers;

use app\modules\polls\models\Answers;
use app\modules\polls\models\PollLogic;
use app\modules\polls\models\Questions;
use app\modules\polls\models\Result;
use app\modules\polls\models\Xml;
use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use XMLReader;

use app\modules\polls\models\Polls;
use app\modules\polls\models\XmlFile;

class DriveInController extends Controller
{

  public $layout = 'ex_layout.php';

  const POLLS_TABLE = 'poll_main_tbl';
  const QUESTIONS_TABLE = 'poll_questions_tbl';
  const ANSWERS_TABLE = 'poll_answers_tbl';
  const RESPONDENTS_TABLE = 'poll_respondents_tbl';

  /*  public function actionIndex()
    {
      Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      $pollTable = self::POLLS_TABLE;
      $sql = "SELECT {$pollTable}.id, {$pollTable}.start_date, {$pollTable}.end_date, {$pollTable}.title,
          {$pollTable}.code, {$pollTable}.sample, YEAR({$pollTable}.start_date) as year FROM {$pollTable}";
      $data["data"] = Polls::findBySql($sql)->asArray()->all();
      return $data;
    }*/


  public function actionIndex()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    Yii::$app->view->params['title'] = 'Опрос';
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
    $userId = Yii::$app->user->identity->id;
    $data = Polls::find()
      ->select(["id", "title", "code"])
      ->where(["id" => $id])
      ->with(['visibleQuestions.visibleAnswers.logic'])
      ->asArray()
      ->all();
    $logic = Answers::find()
      ->where(["poll_id" => $id])
      ->with(['logic'])
      ->asArray()
      ->all();
    $data[0]["logic"] = $logic;
    $data[0]["user"] = $userId;
    return [
      'data' => [
        'success' => true,
        'data' => $data,
        'message' => 'Poll info - take it',
      ],
      'code' => 1,
    ];
  }

  public function actionTownForm()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    return $this->renderAjax('town_form');
  }

  public function actionSaveResult()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    if (!empty($_POST)) {
      $respId = $_POST["respId"];
      $pollId = $_POST["pollId"];
      $town = $_POST["townId"];
      $data = $_POST["data"];
      $userId = Yii::$app->user->identity->id;
      $time = date('Y-m-d H:i:s');
      $transaction = Yii::$app->db->beginTransaction();
      $dataSize = count($data);
      for ($i = 0; $i < $dataSize; $i++){
        $model = new Result();
        $model->town_id = $town;
        $model->respondent_id = $respId;
        $model->poll_id = $pollId;
        $model->user_id = $userId;
        $model->input_time = $time;
        $model->answer_id = $data[$i]["id"];
        $model->ex_answer = $data[$i]["exData"];
        $model->answer_code = $data[$i]["code"];
        $model->order = $i;
        if (!$model->save()) {
          $transaction->rollback();
          return [
            'data' => [
              'success' => false,
              'data' => $model->errors,
              'message' => 'Не удалось сохранить результат опроса',
            ],
            'code' => 0,
          ];
        }
      }
      $transaction->commit();
      return [
        'data' => [
          'success' => true,
          'data' => true,
          'message' => 'Результат сохранен',
        ],
        'code' => 1,
      ];
    }
  }

}