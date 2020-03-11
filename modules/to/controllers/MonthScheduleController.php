<?php

namespace app\modules\to\controllers;

use Yii;
use yii\web\Controller;

use app\base\Model;
use app\modules\to\models\schedule\ToEquipment;
use app\modules\to\models\schedule\ToSchedule;


class MonthScheduleController extends Controller
{
  public $layout = '@app/views/layouts/main_ex.php';

  const TO_TABLE = 'to_schedule_tbl';
  const TO_YEAR_TABLE = 'to_year_schedule_tbl';
  const ADMINS_TABLE = 'to_admins_tbl';
  const TOTYPE_TABLE = 'to_type_tbl';
  const TOEQUIPMENT_TABLE = 'to_equipment_tbl';


  // Все графики ТО с основной таблицы
  public function actionIndex()
  {
    //    $this->layout = '@app/views/layouts/main_ex.php';

    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $schTable = self::TO_TABLE;
    $usersTable = self::ADMINS_TABLE;
    $toTable = self::TOTYPE_TABLE;
    $sql = "SELECT {$schTable}.id, {$schTable}.plan_date, {$schTable}.schedule_id, 
              YEAR({$schTable}.plan_date) as year,
              GROUP_CONCAT(DISTINCT {$schTable}.checkmark ORDER BY {$schTable}.checkmark ASC SEPARATOR ', ') as checkmark,
              GROUP_CONCAT(DISTINCT t1.name ORDER BY t1.name ASC SEPARATOR ',<br> ') as admins,
              GROUP_CONCAT(DISTINCT t2.name ORDER BY t2.name ASC SEPARATOR ',<br> ') as auditors,
              GROUP_CONCAT(DISTINCT t3.name ORDER BY t3.name ASC SEPARATOR ',<br> ') as to_type
            from {$schTable}
              LEFT JOIN {$usersTable} as t1 on {$schTable}.admin_id = t1.id
              LEFT JOIN {$usersTable} as t2 on {$schTable}.auditor_id = t2.id
              LEFT JOIN {$toTable} as t3 on {$schTable}.to_type = t3.id
            GROUP BY schedule_id";
    $data["data"] = ToSchedule::findBySql($sql)->asArray()->all();
    return $data;
  }

  public function actionDelete()
  {
    $report = true;
    foreach ($_POST['jsonData'] as $scheduleId) {
      $models = ToSchedule::find()->where(['schedule_id' => $scheduleId])->all();
      foreach ($models as $m) {
        $result = $m->delete();
      }
    }
    if ($report) {
      return true;
    }
    return false;
  }

  protected function findModel($id)
  {
    $model = ToSchedule::findOne(['id' => $id]);
    if (!empty($model)) {
      return $model;
    }
    throw new NotFoundHttpException('The requested page does not exist.');
  }


  // Инициализация страницы создания графика ТО
  public function actionCreate()
  {
    $this->layout = '@app/views/layouts/main_ex.php';
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    Yii::$app->view->params['title'] = 'Создать график';
    Yii::$app->view->params['bUrl'] = $_GET['back-url'];
    return [
      'data' => [
        'success' => true,
        'data' => $this->render('create'),
        'message' => 'Page load.',
      ],
      'code' => 1,
    ];
  }

  //оборудование для создания графика - формирование списка для страницы создания графика ТО
  public function actionEquipment()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $equipmentTable = self::TOEQUIPMENT_TABLE;
    $sql = "SELECT {$equipmentTable}.id, {$equipmentTable}.name, {$equipmentTable}.eq_serial,
              parent.name as parent
            from {$equipmentTable}
              LEFT JOIN {$equipmentTable} as parent on {$equipmentTable}.parent_id = parent.id
              WHERE {$equipmentTable}.valid = 1 AND {$equipmentTable}.eq_id != 0
              ORDER BY {$equipmentTable}.lft ASC";
    $data["data"] = ToEquipment::findBySql($sql)->asArray()->all();
    foreach ($data["data"] as $key => $d) {
      $d["id"] = "<select></select>";
    }
    return $data;
  }

  // Получение списка видов ТО на выбранный месяц (из плана-графика ТО на год)
  public function actionGetTypes()
  {
    sleep(1);
    if ($_POST) {
      $year = $_POST['year'];
      $monthNumber = $_POST['month'];
      $month = 'm' . $monthNumber;
      $table = self::TO_YEAR_TABLE;
      $sql = "SELECT eq_id, {$month} as month FROM {$table} WHERE schedule_year = :year";
      $req = Yii::$app->db->createCommand($sql)
        ->bindValue(':year', $year)
        ->queryAll();
      return json_encode($req);
    }
    return false;
  }

  // Сохрание созданного графика ТО
  public function actionSaveSchedule()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    if ($_POST) {
      $data = $_POST;
      $schId = rand();
      $result = false;
      foreach ($data['data'] as $key => $schedule) {
        $model = new ToSchedule(['scenario' => ToSchedule::SCENARIO_CREATE]);
        $model->eq_id = $key;
        $model->schedule_id = $schId;
        $model->to_type = $schedule['type'];
        $model->plan_date = $schedule['date'];
        $model->admin_id = $schedule['admin'];
        $model->auditor_id = $schedule['auditor'];
        $result = $model->save();
      }
      if ($result) {
        return [
          'data' => [
            'success' => true,
            'data' => 'Success',
            'message' => 'Good work',
          ],
          'code' => 1,
        ];
      }
      return [
        'data' => [
          'success' => false,
          'data' => $model->errors,
          'message' => 'FAILED to save schedule',
        ],
        'code' => 0,
      ];
    }
    return [
      'data' => [
        'success' => false,
        'data' => 'No data in $_POST',
        'message' => 'FAILED to save schedule',
      ],
      'code' => 0,
    ];
  }



  public function actionView()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    Yii::$app->view->params["bUrl"] = $_POST["back-url"];
    $data = $_POST["data"];
    $year = $data["year"];
    $month = $data["month"];
    $monthVal = $data["monthVal"];
    $schedule = $data["id"];
    Yii::$app->view->params["title"] = "График на " . $month . ' ' . $year . " г.";
    return [
      "data" => [
        "success" => true,
        "data" => $this->render("view", [
          'scheduleId' => $schedule,
          'month' => $monthVal,
          'year' => $year
        ]),
        "message" => "Page load.",
      ],
      "code" => 1,
    ];
  }

  public function actionScheduleView($id)
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $schTable = self::TO_TABLE;
    $usersTable = self::ADMINS_TABLE;
    $toTable = self::TOTYPE_TABLE;
    $equipmentTable = self::TOEQUIPMENT_TABLE;
    $sql = "SELECT {$schTable}.id, 
              {$schTable}.plan_date, 
              {$schTable}.fact_date, 
              {$schTable}.schedule_id, 
              YEAR({$schTable}.plan_date) as year, 
              {$schTable}.eq_id as equipment,
              t1.name as admin, 
              t2.name as auditor,
              t3.name as toType,
              t4.name as equipment,
              t4.eq_serial as 's/n',
              t5.name as parent,
              {$schTable}.checkmark
            from {$schTable}
              LEFT JOIN {$usersTable} as t1 on {$schTable}.admin_id = t1.id
              LEFT JOIN {$usersTable} as t2 on {$schTable}.auditor_id = t2.id
              LEFT JOIN {$toTable} as t3 on {$schTable}.to_type = t3.id
              LEFT JOIN {$equipmentTable} as t4 on {$schTable}.eq_id = t4.id
              LEFT JOIN {$equipmentTable} as t5 on t4.parent_id = t5.id
              WHERE {$schTable}.schedule_id = {$id}
              ORDER BY t4.lft ASC";
    $data["data"] = ToSchedule::findBySql($sql)->asArray()->all();
    return $data;
  }

  // Отметка о выполнении графика ТО на выбранный месяц - формирование страницы
  public function actionPerform()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    Yii::$app->view->params["bUrl"] = $_POST["back-url"];
    $data = $_POST["data"];
    $year = $data["year"];
    $monthText = $data["monthText"];
    $schedule = $data["id"];
    Yii::$app->view->params["title"] = "Выполнение графика на " . $monthText . ' ' . $year . " г.";
    return [
      "data" => [
        "success" => true,
        "data" => $this->render("perform", [
          "scheduleId" => $schedule
        ]),
        "message" => "Page load.",
      ],
      "code" => 1,
    ];
  }

  // Отметка о выполнении графика ТО на выбранный месяц - сохранение результатов
  public function actionPerformSchedule()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    if ($_POST["data"]){
      $data = $_POST["data"];
      $result = false;
      foreach ($data as $key => $scheduleDate) {
        $model = ToSchedule::findOne($key);
        if ($model) {
          $model->checkmark = 1;
          $model->fact_date = $scheduleDate;
          $result = $model->save();
          continue;
        }
        return [
          "data" => [
            "success" => false,
            "data" => "Model wasn`t find",
            "message" => "Model wasn`t find",
          ],
          "code" => 0,
        ];
      }
      if ($result) {
        return [
          "data" => [
            "success" => true,
            "data" => "Schedules was saved",
            "message" => "Schedules was saved",
          ],
          "code" => 1,
        ];
      }
      return [
        "data" => [
          "success" => false,
          "data" => $model->errors,
          "message" => "Failed to save data",
        ],
        "code" => 0,
      ];
    }
    return [
      "data" => [
        "success" => false,
        "data" => "$_POST is empty",
        "message" => "$_POST is empty",
      ],
      "code" => 0,
    ];
  }

  public function actionEdit()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    Yii::$app->view->params["bUrl"] = $_POST["back-url"];
    $data = $_POST["data"];
    $year = $data["year"];
    $monthText = $data["monthText"];
    $schedule = $data["id"];
    Yii::$app->view->params["title"] = "Обновить график на " . $monthText . ' ' . $year . " г.";
    return [
      "data" => [
        "success" => true,
        "data" => $this->render("edit", [
          "scheduleId" => $schedule
        ]),
        "message" => "Page load.",
      ],
      "code" => 1,
    ];
  }

  public function actionEditSave()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    if ($_POST["data"]){
      $data = $_POST["data"];
      $result = false;
      foreach ($data as $key => $schedule) {
        $model = ToSchedule::findOne($key);
        if ($model) {
          $model->to_type = $schedule['type'];
          $model->plan_date = $schedule['date'];
          $model->admin_id = $schedule['admin'];
          $model->auditor_id = $schedule['auditor'];
          $result = $model->save();
          continue;
        }
        return [
          "data" => [
            "success" => false,
            "data" => "Model wasn`t find",
            "message" => "Model wasn`t find",
          ],
          "code" => 0,
        ];
      }
      if ($result) {
        return [
          "data" => [
            "success" => true,
            "data" => "Schedules was saved",
            "message" => "Schedules was saved",
          ],
          "code" => 1,
        ];
      }
      return [
        "data" => [
          "success" => false,
          "data" => $model->errors,
          "message" => "Failed to save data",
        ],
        "code" => 0,
      ];
    }
    return [
      "data" => [
        "success" => false,
        "data" => "$_POST is empty",
        "message" => "$_POST is empty",
      ],
      "code" => 0,
    ];

  }

  public function actionFreeDays($start_date, $end_date)
  {
    return false;
    $sql = 'SELECT people_labor_status.people_id, people_labor_status.free_date as free_dates,
              people_labor_status.comment,
              people_labor_title.title as labor_title
            from people_labor_status
              LEFT JOIN people_labor_title on people_labor_status.labor_title = people_labor_title.id
            WHERE free_date >= :start_date
            and free_date <= :end_date ORDER BY people_id, free_date';
    $ar = Yii::$app->db->createCommand($sql)
      ->bindValue(':start_date', $start_date)
      ->bindValue(':end_date', $end_date)
      ->queryAll();
    return json_encode($ar);
  }


  /*
  public function actionViewE($id)
  {
    $model = ToSchedule::find()
      ->with(['admin', 'auditor', 'toType', 'toEq'])
      ->where(['schedule_id' => $id]);
    $month = $model->max('plan_date');
    setlocale(LC_ALL, 'ru_RU');
    $month = strftime("%B %Y", strtotime($month));
    return $this->render('view_', [
      'tos' => $model->all(),
      'month' => $month,
      'id' => $id
    ]);
  }
*/



}