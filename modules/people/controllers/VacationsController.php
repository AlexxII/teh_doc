<?php

namespace app\modules\scheduler\controllers;

use app\modules\admin\models\User;
use Yii;
use yii\web\Controller;
use app\modules\scheduler\models\Vacation;
use app\modules\scheduler\models\Holiday;

class VacationsController extends Controller
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
  }

  public function actionVacationsData()
  {
    if (isset($_POST['year']) && isset($_POST['users'])) {
      $year = $_POST['year'];
      $users = $_POST['users'];
      $sDate = $year . '-01-01';
      $eDate = $year . '-12-31';
      $models = Vacation::find()
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
          $yearData[$key]['location'] = 'Часть отпуска';
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

  public function actionMonthVacationsData()
  {
    if (isset($_POST['firstDay'])) {
      $firstDay = $_POST['firstDay'];
      $lastDay = $_POST['lastDay'];
      $models = Vacation::find()
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
        $yearData[$key]['location'] = 'Часть отпуска';
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
    $model = new Vacation();
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

  public function actionSaveVacation()
  {
    if (isset($_POST['msg'])) {
      $msg = $_POST['msg'];
      $model = new Vacation();
      $model->start_date = date('Y-m-d', strtotime($msg['start']));
      $model->end_date = date('Y-m-d', strtotime($msg['end']));
      $model->user_id = $msg['user'];
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
    $model = Vacation::findOne($id);
    $model->start_date = date('d.m.Y', strtotime($model->start_date));
    $model->end_date = date('d.m.Y', strtotime($model->end_date));
    if ($model) {
      return $this->renderAjax('_form', [
        'model' => $model
      ]);
    }
    return false;
  }

  public function actionUpdateVacation()
  {
    if (isset($_POST['msg'])) {
      $msg = $_POST['msg'];
      $model = Vacation::findOne($msg['id']);
      $model->start_date = date('Y-m-d', strtotime($msg['start']));
      $model->end_date = date('Y-m-d', strtotime($msg['end']));
      $model->user_id = $msg['user'];
      $model->duration = $msg['duration'];
      if ($model->save()) {
        return true;
      }
      return false;
    }
    return false;

  }

  public function actionDeleteVacation()
  {
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
      $model = Vacation::findOne($id);
      if ($model->delete()) {
        return true;
      }
      return false;
    }
    return false;
  }

}