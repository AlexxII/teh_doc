<?php

namespace app\modules\to\controllers;

use Yii;
use yii\web\Controller;
use app\modules\to\models\schedule\ToSchedule;


class DefaultController extends Controller
{
  const TO_TABLE = 'to_schedule_tbl';
  const TO_YEAR_TABLE = 'to_year_schedule_tbl';
  const ADMINS_TABLE = 'to_admins_tbl';
  const TOTYPE_TABLE = 'to_type_tbl';

  public function actionIndex()
  {
    $schTable = self::TO_TABLE;
    $usersTable = self::ADMINS_TABLE;
    $toTable = self::TOTYPE_TABLE;
    $sql = "SELECT {$schTable}.id, {$schTable}.plan_date, {$schTable}.schedule_id,
              GROUP_CONCAT(DISTINCT {$schTable}.checkmark ORDER BY {$schTable}.checkmark ASC SEPARATOR ', ') as checkmark,
              GROUP_CONCAT(DISTINCT t1.name ORDER BY t1.name ASC SEPARATOR ',<br> ') as admins,
              GROUP_CONCAT(DISTINCT t2.name ORDER BY t2.name ASC SEPARATOR ',<br> ') as auditors,
              GROUP_CONCAT(DISTINCT t3.name ORDER BY t3.name ASC SEPARATOR ',<br> ') as to_type
            from {$schTable}
              LEFT JOIN {$usersTable} as t1 on {$schTable}.admin_id = t1.id
              LEFT JOIN {$usersTable} as t2 on {$schTable}.auditor_id = t2.id
              LEFT JOIN {$toTable} as t3 on {$schTable}.to_type = t3.id
            GROUP BY schedule_id";

    return $this->render('default', [
      'tos' => ToSchedule::findBySql($sql)->asArray()->all(),
      'month' => 1
    ]);
  }



}