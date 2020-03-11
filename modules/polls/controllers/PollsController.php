<?php

namespace app\modules\polls\controllers;

use app\modules\polls\models\Answers;
use app\modules\polls\models\Questions;
use app\modules\polls\models\Towns;
use app\modules\polls\models\Xml;
use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use XMLReader;

use app\modules\polls\models\Polls;
use app\modules\polls\models\XmlFile;

class PollsController extends Controller
{

  const POLLS_TABLE = 'poll_main_tbl';
  const QUESTIONS_TABLE = 'poll_questions_tbl';
  const ANSWERS_TABLE = 'poll_answers_tbl';
  const RESPONDENTS_TABLE = 'poll_respondents_tbl';

  public function actionIndex()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $pollTable = self::POLLS_TABLE;
    $sql = "SELECT {$pollTable}.id, {$pollTable}.start_date, {$pollTable}.end_date, {$pollTable}.title, 
        {$pollTable}.code, {$pollTable}.sample, YEAR({$pollTable}.start_date) as year FROM {$pollTable}";
    $data["data"] = Polls::findBySql($sql)->asArray()->all();
    return $data;
  }

  public function actionTowns()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $data["towns"] = Towns::find()->select('id, name')->where(['selected' => 1])->orderBy('lft')->asArray()->all();
    return $result = [
      "data" => [
        "success" => true,
        "data" => $data,
        "message" => "Towns loaded",
      ],
      "code" => 1
    ];
  }

  public function actionAddNewPoll()
  {
    $model = new Polls();
    $xmlF = new XmlFile();
    $xmlM = new Xml();
    if ($model->load(Yii::$app->request->post())) {
      Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      $userId = Yii::$app->user->identity->id;
      $model->created_user = $userId;
      $currentTime = new \DateTime();
      $model->poll_record_create = date('Y-m-d H:i:s');
      $model->poll_record_update = $currentTime->format('Y-m-d H:i:s');

      $xmlF->xmlFile = UploadedFile::getInstances($xmlF, 'xmlFile');
      $name = $model->code . "_" . $model->id . ".xml";

      $transaction = Yii::$app->db->beginTransaction();
//        Polls::log($model->id, "info", "Добавил новый опрос.");
      if ($model->save()) {
        if ($xmlF->upload($name)) {
          $xmlM->poll_id = $model->id;                              // запись в БД
          $xmlM->title = $name;
          if ($xmlM->save()) {
            if ($xmlF->parseAndLoadToDb($model->id)) {              // XmlFile
//              return $xmlF->parseAndLoadLogic($model->id);
              if ($xmlF->parseAndLoadLogic($model->id)){
                $transaction->commit();
                $resData = [
                  "questions" => $xmlF->questionsCount,
                  "answers" => $xmlF->answersCount
                ];
                $result = [
                  "data" => [
                    "success" => true,
                    "data" => $resData,
                    "message" => "Seems to be FINE",
                  ],
                  "code" => 1
                ];
              } else {
                $transaction->rollback();
                $result = [
                  "data" => [
                    "success" => false,
                    "data" => $xmlF->error,
                    "message" => "Could`t save OR parse logic in config xml file",
                  ],
                  "code" => 0
                ];
              }
            } else {
              $transaction->rollback();
              $result = [
                "data" => [
                  "success" => false,
                  "data" => $xmlF->error,
                  "message" => "Could`t save OR parse config xml file",
                ],
                "code" => 0
              ];
            }
          } else {
            $transaction->rollback();
            $result = [
              "data" => [
                "success" => false,
                "data" => $xmlM->errors,
                "message" => "Could`t save config xml file in DB"
              ],
              "code" => 0
            ];
          }
        } else {
          $transaction->rollback();
          $result = [
            "data" => [
              "success" => false,
              "data" => $xmlF->error,
              "message" => "Could`t upload file",
            ],
            "code" => 0
          ];
        }
      } else {
        $transaction->rollback();
        $result = [
          "data" => [
            "success" => false,
            "data" => $model->errors,
            "message" => "Could`t save poll model",
          ],
          "code" => 0
        ];
      }
      return $result;
    }
    return $this->renderAjax("_form_create_poll", [
      "model" => $model,
      "xml" => $xmlF,
      "create" => true
    ]);
  }

  public function actionUpdatePoll($id)
  {
    $model = Polls::findOne(["id" => $id]);

    if ($model->load(Yii::$app->request->post())) {
      Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      $currentTime = new \DateTime();
      $model->poll_record_update = $currentTime->format("Y-m-d H:i:s");
      if ($model->save()) {
//        $this->logVks($model->id, "info","Обновил информацию о предстоящем сеансе ВКС");
        return [
          "data" => [
            "success" => true,
            "data" => "Model save",
            "message" => "Page load",
          ],
          "code" => 1,
        ];
      } else {
        return [
          "data" => [
            "success" => false,
            "data" => $model->errors,
            "message" => "Page load",
          ],
          "code" => 0,
        ];
      }
    }
    return $this->renderAjax("_form_create_poll", [
      "model" => $model,
      "create" => false
    ]);
  }

  public function actionTestXmlReader()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    if ($xml = new XmlFile()) {
      $name = "ROS19-47_1917164898.xml";
      return $xml->parseAndLoadToDb($name);
    }
    return [
      "data" => [
        "success" => false,
        "data" => "FUCK",
        "message" => "XML fault",
      ],
      "code" => 0,
    ];
  }


  public function actionViewPoll($id)
  {
//    $logs = VksLog::find()->where(["=", "session_id", $id])->orderBy("log_time")->all();
    return $this->renderAjax("view_poll", [
      "model" => Polls::findModel($id)
    ]);
  }

  public function actionDelete()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $pollId = $_POST["pollId"];
    $model = Polls::findOne($pollId);
    if ($model) {
      $transaction = Yii::$app->db->beginTransaction();
      $pollQuestions = Questions::find()->where(["poll_id" => $model->id])->all();
      foreach ($pollQuestions as $key => $pollQuestion) {
        if (!$pollQuestion->delete()) {
          $transaction->rollback();
          return [
            "data" => [
              "success" => false,
              "data" => "Question № " . $key . " id -" . $pollQuestion->id,
              "message" => "Failed to delete question of the poll",
            ],
            "code" => 0,
          ];
        }
      }
      $pollAnswers = Answers::find()->where(["poll_id" => $model->id])->all();
      foreach ($pollAnswers as $key => $pollAnswer) {
        if (!$pollAnswer->delete()) {
          $transaction->rollback();
          return [
            "data" => [
              "success" => false,
              "data" => "Answer id " . $pollAnswer->id,
              "message" => "Failed to delete answer of the poll",
            ],
            "code" => 0,
          ];
        }
      }
      $xmlFile = Xml::find()->where(["poll_id" => $model->id])->all();
      $fileName = Yii::$app->params["uploadXml"] . $xmlFile[0]->title;
      if (is_file($fileName)) {
        try {
          unlink($fileName);
          $xmlRes = "Yes";
        } catch (\Exception $e) {
          $xmlRes = "NO. Could`t delete file. " . "Error - " . $e;
        }
      } else {
        $xmlRes = "NO. Could`t find or not an common file";
      }
      if (!$xmlFile[0]->delete()) {
        $transaction->rollback();
        return [
          "data" => [
            "success" => false,
            "data" => "Could`t delete xml in DB",
            "message" => "Failed to delete Xml from DB",
          ],
          "code" => 0,
        ];
      }

      if ($model->delete()) {
        $transaction->commit();
        return [
          "data" => [
            "success" => true,
            "data" => "Xml file deleted - " . $xmlRes,
            "message" => "Seems to be FINE",
          ],
          "code" => 1,
        ];
      } else {
        $transaction->rollback();
        return [
          "data" => [
            "success" => true,
            "data" => $model->errors,
            "message" => "Failed. Could`t remove poll",
          ],
          "code" => 1,
        ];
      }

    } else {
      return [
        "data" => [
          "success" => false,
          "data" => "Failed. Maybe pollId wrong",
          "message" => "Model not found",
        ],
        "code" => 0,
      ];
    }
  }

  public function actionIndexEx()
  {
    $pollTable = self::POLLS_TABLE;
    $usersTable = self::ADMINS_TABLE;
    $toTable = self::TOTYPE_TABLE;
    $sql = "SELECT {$pollTable}.id, {$pollTable}.plan_date, {$pollTable}.schedule_id,
              GROUP_CONCAT(DISTINCT {$pollTable}.checkmark ORDER BY {$pollTable}.checkmark ASC SEPARATOR ', ') as checkmark,
              GROUP_CONCAT(DISTINCT t1.name ORDER BY t1.name ASC SEPARATOR ',<br> ') as admins,
              GROUP_CONCAT(DISTINCT t2.name ORDER BY t2.name ASC SEPARATOR ',<br> ') as auditors,
              GROUP_CONCAT(DISTINCT t3.name ORDER BY t3.name ASC SEPARATOR ',<br> ') as to_type
            from {$pollTable}
              LEFT JOIN {$usersTable} as t1 on {$pollTable}.admin_id = t1.id
              LEFT JOIN {$usersTable} as t2 on {$pollTable}.auditor_id = t2.id
              LEFT JOIN {$toTable} as t3 on {$pollTable}.to_type = t3.id
            GROUP BY schedule_id";

    return $this->render('index', [
      'tos' => ToSchedule::findBySql($sql)->asArray()->all(),
      'month' => 1
    ]);
  }

}