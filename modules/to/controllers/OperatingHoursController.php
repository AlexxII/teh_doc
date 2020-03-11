<?php

namespace app\modules\to\controllers;

use Yii;
use yii\web\Controller;

use app\modules\to\models\ToEquipment;
use app\modules\to\models\ToSchedule;


class OperatingHoursController extends Controller
{
  const TO_TABLE = 'to_schedule_tbl';
  const TO_YEAR_TABLE = 'to_year_schedule_tbl';
  const ADMINS_TABLE = 'to_admins_tbl';
  const TOTYPE_TABLE = 'to_type_tbl';

  public function actionAllTools()
  {
    $id = ToEquipment::find()->select('id')->all();
    if (!$id) {
      $data = array();
      $data = [['title' => 'База данных пуста', 'key' => -999]];
      return json_encode($data);
    }
    $roots = ToEquipment::findModel($id)->tree();
    return json_encode($roots);
  }

  public function actionIndex()
  {
    $this->layout = '@app/views/layouts/main_ex.php';

    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    Yii::$app->view->params['title'] = 'Учет наработки';
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


}