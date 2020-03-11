<?php

namespace app\modules\vks\controllers\admin;

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
  public $layout = '@app/views/layouts/main_ex.php';

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

  public function actionServerSide()
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
      array('db' => 'important', 'dt' => 10),
      array('db' => 'vks_upcoming_session', 'dt' => 15)
    );
    $sql_details = \Yii::$app->params['sql_details'];

    $where = 'vks_cancel = 1';

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

    $where = 'vks_upcoming_session = 0 AND vks_cancel = 1';

    return json_encode(
      SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, NULL, $where)
    );
  }

  public function actionViewSession($id)
  {
    $logs = VksLog::find()->where(['=', 'session_id', $id])->orderBy('log_time')->all();
    return $this->renderAjax('view_session', [
      'model' => $this->findModel($id),
      'logs' => $logs
    ]);
  }

  // на удалении выставляется флаг vks_cancel
  public function actionIndex()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    Yii::$app->view->params['title'] = 'Корзина';
    Yii::$app->view->params['bUrl'] = $_GET['back-url'];
    return [
      'data' => [
        'success' => true,
        'data' => $this->render('index'),
        'message' => 'Page load.',
      ],
      'code' => 1,
    ];
  }

  public function actionRestore()
  {
    $report = true;
    foreach ($_POST['jsonData'] as $d) {
      $model = $this->findModel($d);
      $this->logVks($model->id, "info","Восстановил сеансе ВКС из перечня удаленных.");
      $model->vks_cancel = 0;
      $report = $model->save();
    }
    if ($report) {
      return true;
    }
    return false;
  }


  public function actionDeleteCompletely()
  {
    $report = true;
    foreach ($_POST['jsonData'] as $d) {
      $model = $this->findModel($d);
      $this->logVks($model->id, "danger", "Удалил запись о сеансе ВКС.");
      $report = $model->delete();
    }
    if ($report) {
      return true;
    }
    return false;
  }

  public function actionDeleteSingleCompletely()
  {
    if (!empty($_POST['id'])) {
      $id =  $_POST['id'];
      $model = $this->findModel($id);
      if ($model->delete()) {
        $this->logVks($model->id, "danger", "Удалил запись о сеансе ВКС.");
        return true;
      }
      return false;
    }
    return false;
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