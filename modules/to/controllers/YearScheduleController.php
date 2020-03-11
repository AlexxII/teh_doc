<?php

namespace app\modules\to\controllers;

use Yii;
use yii\web\Controller;

use app\base\Model;
use app\modules\to\models\schedule\ToEquipment;
use app\modules\to\models\schedule\ToType;
use app\modules\to\models\schedule\ToYearSchedule;

class YearScheduleController extends Controller
{

  public function actionIndex()
  {
    $this->layout = '@app/views/layouts/main_ex.php';

    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    Yii::$app->view->params['title'] = 'Годовые планы ТО';
    Yii::$app->view->params['bUrl'] = $_GET['back-url'];
    $toTypes = ToType::find()->where(['!=', 'lvl', '0'])->orderBy('lft')->asArray()->all();
    $toTypeArray = array();
    foreach ($toTypes as $toType) {
      $toTypeArray[$toType['id']] = $toType['name'];
    }
    return [
      'data' => [
        'success' => true,
        'data' => $this->render('index', [
          'list' => $toTypeArray

        ]),
        'message' => 'Page load.',
      ],
      'code' => 1,
    ];
  }

  public function actionCreateYearSchedule()
  {
    $year = $_POST['year'];
    $result = array();
    $yearModel = ToYearSchedule::findAll(['schedule_year' => $year]);
    $toEq = ToEquipment::find()
      ->where(['valid' => 1])
      ->andWhere(['!=', 'eq_id', '0'])->orderby(['lft' => SORT_ASC])->asArray()->all();
    if (empty($toEq)) {
      $result['status'] = false;
      $result['data'] = '';
      return json_encode($result);
    }
    if (count($yearModel) == count($toEq)) {
      $t = array();
      foreach ($yearModel as $model) {
        $temp = array();
        for ($i = 0; $i < 12; $i++) {
          $string = 'm' . $i;
          $temp[$i] = $model[$string];
        }
        $t[$model['eq_id']] = $temp;
      }
      $result['status'] = 'old';
      $result['data'] = &$t;
      return json_encode($result);
    } else {
      foreach ($toEq as $i => $eq) {
        $toss[] = new ToYearSchedule();
        $toss[$i]->eq_id = $eq['id'];
        $toss[$i]->schedule_year = $year;
        $toss[$i]->save();
      }
      $yearModel = ToYearSchedule::findAll(['schedule_year' => $year]);
      $t = array();
      foreach ($yearModel as $model) {
        $temp = array();
        for ($i = 0; $i < 12; $i++) {
          $string = 'm' . $i;
          $temp[$i] = $model[$string];
        }
        $t[$model['eq_id']] = $temp;
      }
      $result['status'] = 'new';
      $result['data'] = &$t;
      return json_encode($result);
    }
  }

  public function actionSaveTypes()
  {
    $array = $_POST['id'];
    $year = $_POST['year'];
    $result = false;
    foreach ($array as $ar) {
      $eqId = $ar['eqId'];
      $types = $ar['types'];
      $model = ToYearSchedule::find()->where(['eq_id' => $eqId])->andWhere(['schedule_year' => $year])->one();
      for ($i = 0; $i < 12; $i++) {
        $month = 'm' . $i;
        $model->$month = $types[$i];
      }
      if ($model->save(false)) {
        $result = true;
        continue;
      }
      return false;
    }
    return true;
  }

}
