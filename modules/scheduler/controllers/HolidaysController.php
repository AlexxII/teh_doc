<?php

namespace app\modules\scheduler\controllers;

use Yii;
use yii\web\Controller;

use app\modules\scheduler\models\Holiday;

class HolidaysController extends Controller
{
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

  public function actionHolidays($year)
  {
    if (isset($year)) {
      $models = Holiday::find()
        ->where(['>=', 'startYear', $year])
        ->andWhere(['<=', 'endYear', $year])
        ->andWhere(['year_repeat' => 0])
        ->all();
      $yearData = [];
      $count = 0;
      foreach ($models as $key => $model) {
        $yearData[$key]['id'] = $model->id;
        $yearData[$key]['name'] = $model->title;
        $yearData[$key]['location'] = $model->description;
        $type = $model->holiday_type;
        if ($type == 0) {
          $yearData[$key]['duration'] = 'Рабочий день';
          $yearData[$key]['color'] = 'grey';
        } else if ($type == 1) {
          $yearData[$key]['duration'] = 'Предпраздничный';
          $yearData[$key]['color'] = '#ff8f8f';
        } else if ($type == 2) {
          $yearData[$key]['duration'] = 'Выходной';
          $yearData[$key]['color'] = 'red';
        } else {
          $yearData[$key]['duration'] = 'Праздник';
          $yearData[$key]['color'] = 'red';
        }
        $yearData[$key]['sYear'] = $model->startYear;
        $yearData[$key]['sMonth'] = $model->startMon - 1;
        $yearData[$key]['sDay'] = $model->startDay;
        $yearData[$key]['eYear'] = $model->endYear;
        $yearData[$key]['eMonth'] = $model->endMon - 1;
        $yearData[$key]['eDay'] = $model->endDay;
        $yearData[$key]['hType'] = $model->holiday_type;
        $count++;
      }

      $models = Holiday::find()
        ->where(['year_repeat' => 1])
        ->all();
      foreach ($models as $key => $model) {
        $yearData[$key + $count]['id'] = $model->id;
        $yearData[$key + $count]['name'] = $model->title;
        $yearData[$key + $count]['location'] = $model->description;
        $type = $model->holiday_type;
        if ($type == 0) {
          $yearData[$key + $count]['duration'] = 'Рабочий день';
          $yearData[$key + $count]['color'] = 'grey';
        } else if ($type == 1) {
          $yearData[$key + $count]['duration'] = 'Предпраздничный';
          $yearData[$key + $count]['color'] = '#ff8f8f';
        } else if ($type == 2) {
          $yearData[$key + $count]['duration'] = 'Выходной';
          $yearData[$key + $count]['color'] = 'red';
        } else {
          $yearData[$key + $count]['duration'] = 'Праздник';
          $yearData[$key + $count]['color'] = 'red';
        }
        $yearData[$key + $count]['sYear'] = $year;
        $yearData[$key + $count]['sMonth'] = $model->startMon - 1;
        $yearData[$key + $count]['sDay'] = $model->startDay;
        $yearData[$key + $count]['eYear'] = $year;
        $yearData[$key + $count]['eMonth'] = $model->endMon - 1;
        $yearData[$key + $count]['eDay'] = $model->endDay;
        $yearData[$key + $count]['hType'] = $model->holiday_type;
      }
      return json_encode($yearData);
    }
    return false;

  }

  public function actionForm($startDate, $endDate, $diff)
  {
    $model = new Holiday();
    $sDate = date('d.m.Y', strtotime($startDate));
    $eDate = date('d.m.Y', strtotime($endDate));
    $model->start_date = $sDate;
    $model->end_date = $eDate;
    $model->duration = $diff;
    return $this->renderAjax('_form', [
      'model' => $model
    ]);
  }

  public function actionSaveHoliday()
  {
    if (isset($_POST['msg'])) {
      $msg = $_POST['msg'];
      $model = new Holiday();
      $model->title = $msg['title'];
//      $pattern = '/([0-9]{4})-([0-9]{2})-([0-9]{2})/';    // для формата UNIX
      $pattern = '/([0-9]{2}).([0-9]{2}).([0-9]{4})/';
      preg_match($pattern, $msg['start'], $stMatches);
      preg_match($pattern, $msg['end'], $endMatches);

      $model->start_date = $stMatches[3] . '-' . $stMatches[2] . '-' . $stMatches[1];
      $model->end_date = $endMatches[3] . '-' . $endMatches[2] . '-' . $endMatches[1];
      $model->stDateStr = $stMatches[2] . '-' . $stMatches[1];
      $model->eDateStr = $endMatches[2] . '-' . $endMatches[1];
      $model->startDay = $stMatches[1];
      $model->startMon = $stMatches[2];
      $model->startYear = $stMatches[3];
      $model->endDay = $endMatches[1];
      $model->endMon = $endMatches[2];
      $model->endYear = $endMatches[3];
      $model->created_user = Yii::$app->user->identity->id;
      $model->duration = $msg['duration'];
      $model->description = $msg['description'];
      $model->approval_year = $msg['approvalYear'];
      $model->holiday_type = $msg['hType'];
      $model->year_repeat = $msg['repeat'];
      if ($model->save()) {
        return true;
      }
      return false;
    }
    return false;

  }

  public function actionUpdateForm($id, $year)
  {
    $model = Holiday::findOne($id);
    $model->start_date = date('d.m.', strtotime($model->start_date)) . $year;
    $model->end_date = date('d.m.', strtotime($model->end_date)) . $year;
    if ($model) {
      return $this->renderAjax('_form', [
        'model' => $model
      ]);
    }
    return false;
  }

  public function actionUpdateHoliday()
  {
    if (isset($_POST['msg'])) {
      $msg = $_POST['msg'];
      $model = Holiday::findOne($msg['id']);
      $model->title = $msg['title'];

      $pattern = '/([0-9]{2}).([0-9]{2}).([0-9]{4})/';
      preg_match($pattern, $msg['start'], $stMatches);
      preg_match($pattern, $msg['end'], $endMatches);

      $model->start_date = $stMatches[3] . '-' . $stMatches[2] . '-' . $stMatches[1];
      $model->end_date = $endMatches[3] . '-' . $endMatches[2] . '-' . $endMatches[1];
      $model->stDateStr = $stMatches[2] . '-' . $stMatches[1];
      $model->eDateStr = $endMatches[2] . '-' . $endMatches[1];
      $model->startDay = $stMatches[1];
      $model->startMon = $stMatches[2];
      $model->startYear = $stMatches[3];
      $model->endDay = $endMatches[1];
      $model->endMon = $endMatches[2];
      $model->endYear = $endMatches[3];

      $model->created_user = Yii::$app->user->identity->id;
      $model->duration = $msg['duration'];
      $model->description = $msg['description'];
      $model->approval_year = $msg['approvalYear'];
      $model->holiday_type = $msg['hType'];
      $model->year_repeat = $msg['repeat'];
      if ($model->save()) {
        return true;
      }
      return false;
    }
    return false;
  }

  public function actionDeleteHoliday()
  {
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
      $model = Holiday::findOne($id);
      if ($model->delete()) {
        return true;
      }
      return false;
    }
    return false;
  }

  // From vacation calendar
  public function actionHolidaysArray($year)
  {
//    sleep(2);
    $ar1 = Holiday::find()
      ->select('start_date, duration')
      ->where(['>=', 'startYear', $year])
      ->andWhere(['<=', 'endYear', $year])
      ->andWhere(['year_repeat' => 0])
      ->andWhere(['>=', 'holiday_type', '2'])
      ->asArray()
      ->all();

    $ar3 = [];
    $ar2 = Holiday::find()
      ->where(['year_repeat' => 1])
      ->andWhere(['>=', 'holiday_type', '2'])
      ->asArray()
      ->all();
    foreach ($ar2 as $key => $a) {
      $ar3[$key]['start_date'] = $year . '-' . $a['stDateStr'];
      $ar3[$key]['duration'] = $a['duration'];
    }
    $arrays = array_merge($ar1, $ar3);
    $result = [];
    $count = 0;
    $mc = 3600 * 24;
    foreach ($arrays as $key => $ar) {
      for ($i = 0; $i < $ar['duration']; $i++) {
        $result[$count] = strtotime($ar['start_date']) + ($mc * $i);
        $count++;
      }
    }
    return json_encode($result);
  }

  public function actionHolidaysData()
  {
    $arrays = Holiday::find()
      ->asArray()
      ->orderBy('start_date')
      ->where('year_repeat')
      ->all();
    foreach ($arrays as $key => $ar) {

    }
  }

}