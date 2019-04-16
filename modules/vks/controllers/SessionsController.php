<?php

namespace app\modules\vks\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

use app\modules\vks\models\VksLog;
use app\modules\vks\models\SSP;
use app\modules\vks\models\VksPlaces;
use app\modules\vks\models\VksSessions;
use app\modules\vks\models\VksSubscribes;
use app\modules\vks\models\VksTypes;


class SessionsController extends Controller
{
  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::class,
        'rules' => [
          [
            'allow' => true,
            'actions' => ['sessions-ex', 'archive-ex', 'delete-completely', 'delete-single-completely'],
            'roles' => ['superAdmin']      // доступ только с ролью superAdmin
          ],[
            'allow' => false,
            'actions' => ['sessions-ex', 'archive-ex', 'delete-completely', 'delete-single-completely'],
            'roles' => ['@', "?"]
          ],
          [
            'allow' => true,
            'roles' => ['@']
          ]
        ],
      ],
    ];
  }

  public function actionIndex()
  {
    return $this->render('index');
  }

  public function actionServerSide($index)
  {
    $table = 'vks_sessions_tbl';
    $primaryKey = 'id';
    $columns = array(
      array('db' => 'id', 'dt' => 0),
      array(
        'db' => 'vks_date',
        'dt' => 1,
        'formatter' => function ($d, $row) { //TODO разобраться с форматом отображения даты
          if ($d != null) {
            return date('d.m.Y', strtotime($d));
          } else {
            return '-';
          }
        }
      ),
      array(
        'db' => 'vks_date',
        'dt' => 2,
        'formatter' => function ($d, $row) { //TODO разобраться с форматом отображения даты
          if ($d != null) {
            $month = date('m', strtotime($d));
            $year = date('Y г.', strtotime($d));
            $dates = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
            return $dates[$month - 1] . ' ' . $year;
          } else {
            return '-';
          }
        }
      ),
      array(
        'db' => 'vks_teh_time_start',
        'dt' => 3
      ),
      array(
        'db' => 'vks_work_time_start',
        'dt' => 4
      ),
      array('db' => 'vks_type_text', 'dt' => 5),
      array('db' => 'vks_place_text', 'dt' => 6),
      array('db' => 'vks_subscriber_office_text', 'dt' => 7),
      array('db' => 'vks_subscriber_name', 'dt' => 8),
      array('db' => 'vks_order_text', 'dt' => 9),
      array('db' => 'important', 'dt' => 10)
    );
    $sql_details = \Yii::$app->params['sql_details'];
    if (empty($index)){
      $index = 0;
    }

    if (!empty($_GET['stDate'])){
      $startDate = $_GET['stDate'];
    } else {
      $startDate = "1970-01-01";
    }
    if (!empty($_GET['eDate'])){
      $endDate = $_GET['eDate'];
    } else {
      $endDate = "2099-12-31";
    }


//    $where = 'vks_upcoming_session = 1 AND vks_cancel = ' . $index;
    $where = ' ' . $table . '.vks_upcoming_session = 1 AND Date(vks_date) >= "' . $startDate . '" AND Date(vks_date) <= "' . $endDate . '" AND vks_cancel = ' . $index;

    return json_encode(
      SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, NULL, $where)
    );
  }

  public function actionServerSideEx($index)
  {
    $table = 'vks_sessions_tbl';
    $primaryKey = 'id';
    $columns = array(
      array('db' => 'id', 'dt' => 0),
      array(
        'db' => 'vks_date',
        'dt' => 1,
        'formatter' => function ($d, $row) { //TODO разобраться с форматом отображения даты
          if ($d != null) {
            return date('d.m.Y', strtotime($d));
          } else {
            return '-';
          }
        }
      ),
      array(
        'db' => 'vks_date',
        'dt' => 2,
        'formatter' => function ($d, $row) { //TODO разобраться с форматом отображения даты
          if ($d != null) {
            $month = date('m', strtotime($d));
            $year = date('Y г.', strtotime($d));
            $dates = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
            return $dates[$month - 1] . ' ' . $year;
          } else {
            return '-';
          }
        }
      ),
      array('db' => 'vks_type_text', 'dt' => 4),
      array('db' => 'vks_place_text', 'dt' => 5),
      array('db' => 'vks_subscriber_office_text', 'dt' => 6),
      array('db' => 'vks_order_text', 'dt' => 7),

      array('db' => 'vks_subscriber_name', 'dt' => 14),
      array('db' => 'vks_teh_time_start', 'dt' => 15),
      array('db' => 'vks_teh_time_end', 'dt' => 16),
      array('db' => 'vks_work_time_start', 'dt' => 17),
      array('db' => 'vks_work_time_end', 'dt' => 18),
      array('db' => 'vks_duration_teh', 'dt' => 19),
      array('db' => 'vks_duration_work', 'dt' => 20)
    );
    $sql_details = \Yii::$app->params['sql_details'];

    if (empty($index)){
      $index = 0;
    }

    $where = 'vks_upcoming_session = 0 AND vks_cancel = '. $index;

    return json_encode(
      SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, NULL, $where)
    );
  }

  public function actionCreateUpSession()
  {
    $model = new VksSessions(['scenario' => VksSessions::SCENARIO_CREATE]);
    if ($model->load(Yii::$app->request->post())) {
      $date = date('Y-m-d H:i:s');
      $result = false;
      $model->vks_record_create = $date;
      $model->vks_record_update = $date;
      $model->vks_upcoming_session = 1;
      $result = $model->save();                                                             // TODO нет проверки на ошибки!
      $this->logVks($model->id, "info", "Добавил запись о предстоящем сеансе ВКС");
      if (!empty($_POST['test-type'])) {
        foreach ($_POST['test-type'] as $key => $item) {
          $newModel = new VksSessions(['scenario' => VksSessions::SCENARIO_CREATE]);
          $newModel->load(Yii::$app->request->post());
          $newModel->vks_type = $item;
          $typeModel = VksTypes::findModel($item);
          $newModel->vks_type_text = $typeModel->name;
          if (!empty($_POST['test-place'][$key])) {
            $placeId = $_POST['test-place'][$key];
            $newModel->vks_place = $placeId;
            $placeModel = VksPlaces::findModel($placeId);
            $newModel->vks_place_text = $placeModel->name;
          }
          $newModel->vks_record_create = $date;
          $newModel->vks_record_update = $date;
          $newModel->vks_upcoming_session = 1;
          $newModel->important = 1;
          $result = $newModel->save();
          $this->logVks($newModel->id, "info","Добавил запись о предстоящем сеансе ВКС");
        }
        $model->important = 1;
        $result = $model->save();
      }
      if ($result) {
        Yii::$app->session->setFlash('success', 'Предстоящий сеанс видеосвязи добавлен!');
        return $this->redirect('index');
      } else {
        Yii::$app->session->setFlash('error', 'Что-то не так.');
      }
    }
    return $this->render('create', [
      'model' => $model
    ]);
  }

  public function actionUpdateUpSession($id)
  {
    $model = VksSessions::findOne(['id' => $id]);
    $model->scenario = VksSessions::SCENARIO_CREATE;

    if ($model->load(Yii::$app->request->post())) {
      $currentTime = new \DateTime();
      $model->vks_record_update = $currentTime->format('Y-m-d H:i:s');
      if ($model->save()) {
        $this->logVks($model->id, "info","Обновил информацию о предстоящем сеансе ВКС");
        Yii::$app->session->setFlash('success', 'Запись успешно обновлена!');
        return $this->redirect('index');
      } else {
        Yii::$app->session->setFlash('error', 'Что-то не так.');
      }
    }
    return $this->render('update', [
      'model' => $model
    ]);
  }

  public function actionConfirm($id = null)
  {
    $model = VksSessions::findOne(['id' => $id]);
    $model->scenario = VksSessions::SCENARIO_CONFIRM;
    if ($model->load(Yii::$app->request->post())) {
      $currentTime = new \DateTime();
      $model->vks_record_update = $currentTime->format('Y-m-d H:i:s');
      $model->vks_upcoming_session = 0;
      if ($model->save()) {
        $this->logVks($model->id, "info", "Подтвердил прошедший сеанс ВКС.");
        Yii::$app->session->setFlash('success', 'Запись успешно сохранена и добавлена в архив сеансов ВКС.');
        return $this->redirect('archive');
      } else {
        Yii::$app->session->setFlash('error', 'Что-то не так.');
      }
    }
    return $this->render('confirm', [
      'model' => $model
    ]);
  }

  public function actionCreateSession()
  {
    $model = new VksSessions(['scenario' => VksSessions::SCENARIO_CONFIRM]);
    if ($model->load(Yii::$app->request->post())) {
      $currentTime = new \DateTime();
      $model->vks_record_create = date('Y-m-d H:i:s');
      $model->vks_record_update = $currentTime->format('Y-m-d H:i:s');
      $model->vks_upcoming_session = 0;
      if ($model->save()) {
        $this->logVks($model->id, "info", "Добавил запись о прошедшем сеансе ВКС.");
        Yii::$app->session->setFlash('success', 'Запись успешно сохранена и добавлена в архив сеансов ВКС.');
        return $this->redirect('archive');
      } else {
        Yii::$app->session->setFlash('error', 'Что-то не так');
      }
    }
    return $this->render('create_session', [
      'model' => $model
    ]);
  }

  public function actionUpdateSession($id)
  {
    $model = VksSessions::findOne(['id' => $id]);
    $model->scenario = VksSessions::SCENARIO_CONFIRM;
    if ($model->load(Yii::$app->request->post())) {
      $currentTime = new \DateTime();
      $model->vks_record_update = $currentTime->format('Y-m-d H:i:s');
      $model->vks_upcoming_session = 0;
      if ($model->save()) {
        $this->logVks($model->id, "info","Обновил запись о прошедшем сеансе ВКС.");
        Yii::$app->session->setFlash('success', 'Запись успешно сохранена и добавлена в архив сеансов ВКС.');
        return $this->redirect('archive');
      } else {
        Yii::$app->session->setFlash('error', 'Что-то не так.');
      }
    }
    return $this->render('update_session', [
      'model' => $model
    ]);
  }

  public function actionSubscribersMsk()
  {
    $sql = "SELECT id as value, surnames as label 
              FROM vks_subscribes_tbl where surnames IS NOT NULL and surnames != '' AND list = 1";
    $arrayOfNames = VksSubscribes::findBySql($sql)->asArray()->all();
    $newArrayOfNames = [];
    $tempArrayOfNames = [];
    $i = 0;
    foreach ($arrayOfNames as $fkey => $name) {
      $i = $i + $fkey;
      $tempArrayOfNames = explode('; ', $name['label']);
      foreach ($tempArrayOfNames as $key => $temp) {
        $i = $i + $key;
        $newArrayOfNames[$i]['value'] = $name['value'];
        $newArrayOfNames[$i]['label'] = $temp;
      }
    }
    return json_encode(array_splice($newArrayOfNames, 0));
  }

  public function actionSubscribersRegion()
  {
    $sql = "SELECT id as value, surnames as label 
              FROM vks_subscribes_tbl where surnames IS NOT NULL and surnames != '' AND list = 2";
    $arrayOfNames = VksSubscribes::findBySql($sql)->asArray()->all();
    $newArrayOfNames = [];
    $tempArrayOfNames = [];
    $i = 0;
    foreach ($arrayOfNames as $fkey => $name) {
      $i = $i + $fkey;
      $tempArrayOfNames = explode('; ', $name['label']);
      foreach ($tempArrayOfNames as $key => $temp) {
        $i = $i + $key;
        $newArrayOfNames[$i]['value'] = $name['value'];
        $newArrayOfNames[$i]['label'] = $temp;
      }
    }
    return json_encode(array_splice($newArrayOfNames, 0));
  }

  public function actionViewUpSession($id)
  {
    $logs = VksLog::find()->where(['=', 'session_id', $id])->orderBy('log_time')->all();
    return $this->render('view_up_session', [
      'model' => $this->findModel($id),
      'logs' => $logs
    ]);
  }

  public function actionViewSession($id)
  {
    $logs = VksLog::find()->where(['=', 'session_id', $id])->orderBy('log_time')->all();
    return $this->render('view_session', [
      'model' => $this->findModel($id),
      'logs' => $logs
    ]);
  }

  // на удалении выставляется флаг vks_cancel
  public function actionDelete()
  {
    $report = true;
    foreach ($_POST['jsonData'] as $d) {
      $model = $this->findModel($d);
      $this->logVks($model->id, "danger", " Удалил запись о сеансе ВКС.");
      $model->vks_cancel = 1;
      $report = $model->save();
    }
    if ($report) {
      return true;
    }
    return false;
  }

  public function actionDeleteSingle()
  {
    if (!empty($_POST)) {
      $id = $_POST['id'];
      $model = $this->findModel($id);
      $model->vks_cancel = 1;
      if ($model->save()) {
        $this->logVks($model->id, "danger", " Удалил запись о сеансе ВКС.");
        return true;
      }
      return false;
    }
    return false;
  }

  public function actionArchive()
  {
    return $this->render('archive');
  }

  public function actionAnalysis()
  {
    return $this->render('analysis');
  }

  protected function findModel($id)
  {
    $model = VksSessions::findOne(['id' => $id]);
    if (!empty($model)) {
      return $model;
    }
    throw new NotFoundHttpException('The requested page does not exist.');
  }

  protected function logVks($sessionId, $status, $text)
  {
    $userId = Yii::$app->user->identity->id;
    $log = new VksLog();
    $log->status = $status;
    $log->session_id = $sessionId;
    $log->log_text = $text;
    $log->user_id = $userId;
    $log->log_time = date("Y-m-d H:i:s", time());;
    $log->save();
  }

}