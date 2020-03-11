<?php

namespace app\modules\scheduler\controllers\control;

use app\modules\scheduler\models\Calendars;
use Yii;
use yii\web\Controller;
use app\modules\admin\models\User;
use app\modules\scheduler\models\UserSettings;

class SettingsController extends Controller
{

  public $layout = '@app/views/layouts/main_ex.php';

  public function actionIndex()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    Yii::$app->view->params['bUrl'] = $_GET['back-url'];
    Yii::$app->view->params['title'] = 'Настройки';
    return [
      'data' => [
        'success' => true,
        'data' => $this->render('index'),
        'message' => 'Page load',
      ],
      'code' => 1,
    ];
  }

  public function actionUserCalendars()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $userId = Yii::$app->user->identity->id;
    $user = User::findOne($userId);
    $userSettings = $user->calendarsSettings;
    $calendars = [];
    foreach ($userSettings as $key => $setting) {
      $calendarId = $setting->calendar;
      $calendar = Calendars::findOne($calendarId);
      $calendars[$calendar->id] = $calendar->title;
    }
    return [
      'data' => [
        'success' => true,
        'data' => $calendars,
        'message' => 'Calendars list load'
      ],
      'code' => 1,
    ];
  }

  public function actionCreateCalendar()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $model = new Calendars();
    return [
      'data' => [
        'success' => true,
        'data' => $this->renderAjax('_calendar_form', [
          'model' => $model
        ]),
        'message' => 'Page load',
      ],
      'code' => 1,
    ];
  }

  public function actionCalendarsForSubscribe()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $model = new UserSettings();
//    $model = Yii::$app->user->identity->id;
//    $model = new Calendars();
    return [
      'data' => [
        'success' => true,
        'data' => $this->renderAjax('_subscribe_form', [
          'model' => $model
        ]),
        'message' => 'Page load',
      ],
      'code' => 1,
    ];
  }

  public function actionSubscribeCalendar()
  {
    $model = new UserSettings();
    if ($model->load(Yii::$app->request->post())) {
      Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }
    return [
      'data' => [
        'success' => false,
        'data' => $model->errors,
        'message' => '',
      ],
      'code' => 0,
    ];
  }

  public function actionSaveCalendar()
  {
    $model = new Calendars();
    if ($model->load(Yii::$app->request->post())) {
      Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      $date = date('Y-m-d H:i:s');
      $userId = Yii::$app->user->identity->id;
      $userSettings = new UserSettings();
      $model->created_user = $userId;
      $model->created_at = $date;
      $model->updated_at = $date;
      if ($model->save()) {
        $userSettings->calendar = $model->id;
        $userSettings->user_id = $userId;
        if ($userSettings->save()) {                                  // TODO добавить еще обработчик!!!??????
          $data[$model->id] = $model->title;
          return [
            'data' => [
              'success' => true,
              'data' => $data,
              'message' => 'Calendar created and add to settings',
            ],
            'code' => 1,
          ];
        } else {
          return [
            'data' => [
              'success' => false,
              'data' => $model->errors,
              'message' => 'Creation failed',
            ],
            'code' => 0,
          ];
        }
      }
    }
    return [
      'data' => [
        'success' => false,
        'data' => $model->errors,
        'message' => 'Creation failed',
      ],
      'code' => 0,
    ];
  }

  public function actionCalendarSettings($id)
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $model = new Calendars();
    $model = Calendars::findOne($id);
    return [
      'data' => [
        'success' => true,
        'data' => $this->renderAjax('_calendar_settings', [
          'model' => $model
        ]),
        'message' => 'Page load',
      ],
      'code' => 1,
    ];
  }

  public function actionCalendarColor()
  {

  }

}