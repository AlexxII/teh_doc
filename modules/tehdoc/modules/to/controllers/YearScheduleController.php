<?php

namespace app\modules\tehdoc\modules\to\controllers;

use Yii;
use yii\web\Controller;

use app\base\Model;
use app\modules\tehdoc\modules\to\models\ToEquipment;
use app\modules\tehdoc\modules\to\models\ToSchedule;


class YearScheduleController extends Controller
{

  public function actionCreate()
  {
    $toEq = ToEquipment::find()
      ->where(['valid' => 1])
      ->andWhere(['!=', 'eq_id', '0'])->orderby(['lft' => SORT_ASC])->all();
    if (empty($toEq)) {
      Yii::$app->session->setFlash('error', "Не добавлено ни одного оборудования в график ТО.");
      return $this->render('create', [
        'tos' => $toEq,
      ]);
    }
    $scheduleRand = rand();
    foreach ($toEq as $i => $eq) {
      $toss[] = new ToSchedule();
      $toss[$i]->scenario = ToSchedule::SCENARIO_CREATE;
      $toss[$i]->eq_id = $eq->id;
      $toss[$i]->schedule_id = $scheduleRand;
    }
    if (ToSchedule::loadMultiple($toss, Yii::$app->request->post())) {
      if (!$to_month = Yii::$app->request->post('month')) {
        Yii::$app->session->setFlash('error', "Введите месяц проведения ТО");
        return $this->render('create', ['tos' => $toss]);
      }
      if (ToSchedule::validateMultiple($toss)) {
        foreach ($toss as $t) {
          $t->to_month = $to_month;
          $t->save();
        }
      } else {
        Yii::$app->session->setFlash('error', "Ошибка валидации данных");
        return $this->render('create', ['tos' => $toss]);
      }
      Yii::$app->session->setFlash('success', "Новый график ТО создан успешно");
      return $this->redirect('archive'); // redirect to your next desired page
    } else {
      return $this->render('create', [
        'tos' => $toss,
      ]);
    }
  }

  public function actionSelect()
  {
    $toEq = ToEquipment::find()
      ->where(['valid' => 1])
      ->andWhere(['!=', 'eq_id', '0'])->orderby(['lft' => SORT_ASC])->all();
    if (empty($toEq)) {
      Yii::$app->session->setFlash('error', "Не добавлено ни одного оборудования в график ТО.");
      return $this->render('create', [
        'tos' => $toEq,
      ]);
    }
    $scheduleRand = rand();
    foreach ($toEq as $i => $eq) {
      $toss[] = new ToSchedule();
      $toss[$i]->scenario = ToSchedule::SCENARIO_CREATE;
      $toss[$i]->eq_id = $eq->id;
      $toss[$i]->schedule_id = $scheduleRand;
    }

  }

}