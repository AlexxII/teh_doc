<?php

namespace app\modules\scheduler\controllers;

use Yii;
use yii\web\Controller;

use app\modules\scheduler\models\Event;

class FullYearController extends Controller
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

  public function actionProductionCalendar()
  {
    return $this->render('production_calendar');
  }

  public function actionVacationCalendar()
  {
    return $this->render('vacation_calendar');
  }

  public function actionYearEvents()
  {
    $models = Event::find()
      ->all();
    $userId = Yii::$app->user->identity->id;
    foreach ($models as $key => $model) {
      $yearData[$key]['id'] = $model->id;
      $yearData[$key]['name'] = $model->title;
      $yearData[$key]['color'] = $model->color;
      $yearData[$key]['location'] = $model->description;
      $yearData[$key]['duration'] = '';
      $yearData[$key]['sYear'] = Date('Y', strtotime($model->start_date));
      $yearData[$key]['sMonth'] = Date('n', strtotime($model->start_date)) - 1;
      $yearData[$key]['sDay'] = Date('j', strtotime($model->start_date));
      $yearData[$key]['eYear'] = Date('Y', strtotime($model->end_date));
      $yearData[$key]['eMonth'] = Date('n', strtotime($model->end_date)) - 1;
      $yearData[$key]['eDay'] = Date('j', strtotime($model->end_date)) - 1;
      $yearData[$key]['req'] = 'sub-event';
    }
    return json_encode(array_values($yearData));
  }


  public function actionTest()
  {
    return $this->renderAjax('test');
  }

  public function actionSaveEvent()
  {
    if (isset($_POST['msg'])) {
      $userId = Yii::$app->user->identity->id;
      $msg = $_POST['msg'];
      $model = new Event();
      $day = 60*60*24;
      $model->start_date = date('Y-m-d', strtotime($msg['start']));
      $model->end_date = date('Y-m-d', strtotime($msg['end']) + $day);
      $model->title = $msg['title'];
      $model->description = $msg['desc'];
      $model->color = $msg['color'] ? $msg['color'] : null;
      $model->user_id = $userId;

      if ($model->save()) {
        return true;
      }
      return false;
    }
    return false;
  }

  public function actionUpdateEvent($id)
  {
    $model = Event::findOne($id);
    if ($model) {
      $day = 60*60*24;
      $model->start_date = date('d.m.Y', strtotime($model->start_date));
      $model->end_date = date('d.m.Y', strtotime($model->end_date) - $day);
      return $this->renderAjax('_create_form', [
        'model' => $model
      ]);
    }
    return false;
  }

  public function actionSaveUpdatedEvent()
  {
    if (isset($_POST['id'])) {
      $eId = $_POST['id'];
      $model = Event::findOne($eId);
      $msg = $_POST['msg'];
      $day = 60*60*24;
      $model->start_date = date('Y-m-d', strtotime($msg['start']));
      $model->end_date = date('Y-m-d', strtotime($msg['end']) + $day);
      $model->title = $msg['title'];
      $model->description = $msg['desc'];
      $model->color = $msg['color'] ? $msg['color'] : null;
      if ($model->save()) {
        return true;
      }
      return false;
    }
    return false;
  }

  public function actionDeleteEvent()
  {
    if (isset($_POST['event'])) {
      $eId = $_POST['event'];
      $model = Event::findOne($eId);
      if ($model->delete()) {
        return true;
      }
      return false;
    }
    return false;
  }

  public function actionSubEvent($id)
  {
    $sEventId = $id;
    $model = Event::findOne($sEventId);

    return $this->renderAjax('_sub_event_view', [
      'model' => $model, true
    ]);
  }

  public function actionImportantEvents()
  {
    $count = 0;
    $mc = 3600 * 24;
    $array = Event::find()
      ->asArray()
      ->all();
    $result = [];

    foreach ($array as $key => $item) {
//      for ($i = 0; $i < $item['duration']; $i++) {
      $result[$count] = strtotime($item['start_date']);
      $count++;
//      }
    }
    return json_encode($result);
  }


}