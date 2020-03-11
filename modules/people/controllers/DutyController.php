<?php

namespace app\modules\scheduler\controllers;

use app\modules\scheduler\models\Duty;
use Yii;
use yii\web\Controller;

use app\modules\vks\models\VksSessions;
use app\modules\tehdoc\modules\to\models\ToSchedule;
use app\modules\scheduler\models\Holiday;
use app\modules\admin\models\User;
use app\modules\scheduler\models\Event;

class DutyController extends Controller
{

  public $layout = 'scheduler_layout_exx.php';

  public function actionIndex()
  {
    $models = User::find()
      ->where(['!=', 'login', 'sAdmin'])
      ->orderBy('username')
      ->all();

    return $this->render('index', [
      'models' => $models
    ]);

    return $this->render('index');
  }

  public function actionDutyData()
  {
    if (isset($_POST['year']) && isset($_POST['users'])) {
      $year = $_POST['year'];
      $users = $_POST['users'];
      $sDate = $year . '-01-01';
      $eDate = $year . '-12-31';
      $models = Duty::find()
        ->where(['>=', 'start_date', $sDate])
        ->andWhere(['<=', 'end_date', $eDate])
        ->with('user')
        ->all();
      $yearData = [];
      foreach ($models as $key => $model) {
        if (in_array($model->userId, $users)) {
          $yearData[$key]['id'] = $model->id;
          $yearData[$key]['name'] = $model->username;
          $yearData[$key]['color'] = $model->color;
          $yearData[$key]['location'] = $model->dutyType;
          $yearData[$key]['duration'] = $model->duration . ' сут.';
          $yearData[$key]['sYear'] = Date('Y', strtotime($model->start_date));
          $yearData[$key]['sMonth'] = Date('n', strtotime($model->start_date)) - 1;
          $yearData[$key]['sDay'] = Date('j', strtotime($model->start_date));
          $yearData[$key]['eYear'] = Date('Y', strtotime($model->end_date));
          $yearData[$key]['eMonth'] = Date('n', strtotime($model->end_date)) - 1;
          $yearData[$key]['eDay'] = Date('j', strtotime($model->end_date));
        }
        continue;
      }
      return json_encode(array_values($yearData));
    }
    return false;
  }

  public function actionMonthsDutyData()
  {
    return true;
  }

  public function actionMonthDutyData()
  {
    if (isset($_POST['firstDay'])) {
      $firstDay = $_POST['firstDay'];
      $lastDay = $_POST['lastDay'];
      $models = Duty::find()
        ->where(['>=', 'start_date', $firstDay])
        ->andWhere(['<=', 'end_date', $lastDay])
        ->with('user')
        ->all();
      $yearData = [];
      foreach ($models as $key => $model) {
        $yearData[$key]['id'] = $model->id;
        $yearData[$key]['name'] = $model->username;
        $yearData[$key]['user'] = $model->user_id;
        $yearData[$key]['color'] = $model->color;
        $yearData[$key]['location'] = $model->dutyType;
        $yearData[$key]['duration'] = $model->duration . ' сут.';
        $yearData[$key]['sYear'] = Date('Y', strtotime($model->start_date));
        $yearData[$key]['sMonth'] = Date('n', strtotime($model->start_date)) - 1;
        $yearData[$key]['sDay'] = Date('j', strtotime($model->start_date));
        $yearData[$key]['eYear'] = Date('Y', strtotime($model->end_date));
        $yearData[$key]['eMonth'] = Date('n', strtotime($model->end_date)) - 1;
        $yearData[$key]['eDay'] = Date('j', strtotime($model->end_date));
      }
      return json_encode(array_values($yearData));
    }
    return false;
  }

  public function actionForm($startDate, $endDate, $diff)
  {
    $model = new Duty();
    $userId = Yii::$app->user->identity->id;
    $sDate = date('d.m.Y', strtotime($startDate));
    $eDate = date('d.m.Y', strtotime($endDate));
    $model->start_date = $sDate;
    $model->end_date = $eDate;
    $model->duration = $diff;
    $model->user_id = $userId;
    return $this->renderAjax('_form', [
      'model' => $model
    ]);
  }

  public function actionSave()
  {
    if (isset($_POST['msg'])) {
      $msg = $_POST['msg'];
      $model = new Duty();
      $model->start_date = date('Y-m-d', strtotime($msg['start']));
      $model->end_date = date('Y-m-d', strtotime($msg['end']));
      $model->user_id = $msg['user'];
      $model->duty_type = $msg['dutyType'];
      $model->duration = $msg['duration'];
      if ($model->save()) {
        return true;
      }
      return false;
    }
    return false;
  }

  public function actionUpdateForm($id)
  {
    $model = Duty::findOne($id);
    $model->start_date = date('d.m.Y', strtotime($model->start_date));
    $model->end_date = date('d.m.Y', strtotime($model->end_date));
    if ($model) {
      return $this->renderAjax('_form', [
        'model' => $model
      ]);
    }
    return false;
  }

  public function actionUpdateDuty()
  {
    if (isset($_POST['msg'])) {
      $msg = $_POST['msg'];
      $model = Duty::findOne($msg['id']);
      $model->start_date = date('Y-m-d', strtotime($msg['start']));
      $model->end_date = date('Y-m-d', strtotime($msg['end']));
      $model->user_id = $msg['user'];
      $model->duty_type = $msg['dutyType'];
      $model->duration = $msg['duration'];
      if ($model->save()) {
        return true;
      }
      return false;
    }
    return false;

  }

  public function actionDeleteDuty()
  {
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
      $model = Duty::findOne($id);
      if ($model->delete()) {
        return true;
      }
      return false;
    }
    return false;
  }


}