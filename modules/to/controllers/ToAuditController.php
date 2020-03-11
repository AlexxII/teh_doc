<?php

namespace app\modules\to\controllers;

use Yii;
use yii\web\Controller;

use app\modules\to\models\schedule\ToEquipment;
use app\modules\to\models\schedule\ToSchedule;
use app\modules\to\models\schedule\ToAdmins;

class ToAuditController extends Controller
{

  public function actionIndex()
  {
    return $this->render('index');
  }

  public function actionAuditors()
  {
    $id = ToAdmins::find()->all();
    if (!$id) {
      $data = array();
      $data = [['title' => 'База данных пуста', 'key' => -999]];
      return json_encode($data);
    }
    $roots = ToAdmins::findOne($id)->tree();
    $admins = $roots[0]['children'];
    $result = array();
    foreach ($admins as $admin) {
      if (!$admin['admin']) {
        $result[] = $admin;
      }
    }
    return json_encode($result);
  }

  public function actionGetDays()
  {
    $auId = $_POST['auditorId'];
    $query = new yii\db\Query();
    $data = $query->select(['plan_date', 'eq_id'])
      ->from('to_schedule_tbl')
      ->where('auditor_id=:auditor', [':auditor' => $auId])
      ->groupBy(['plan_date'])
      ->all();
//    return var_dump($data);
    $parents = array();
    foreach ($data as $key => $d) {
      $eqId = $d['eq_id'];
      $sql = "SELECT parent.name FROM to_equipment_tbl as parent
                   LEFT JOIN teh_to_equipment_tbl as child
                     ON child.parent_id = parent.id WHERE child.id=:id GROUP BY child.parent_id";
      $data = ToEquipment::findBySql($sql, ['id' => $eqId])->asArray()->all();
      $parents[$eqId] = $data[0]['name'];

    }
    return json_encode($parents);
  }

}