<?php

namespace app\modules\vks\controllers;

use app\modules\vks\models\SSP;
use app\modules\vks\models\VksEmployees;
use app\modules\vks\models\VksOrders;
use app\modules\vks\models\VksPlaces;
use app\modules\vks\models\VksSubscribes;
use app\modules\vks\models\VksTools;
use app\modules\vks\models\VksTypes;

use yii\web\Controller;

class AnalyticsController extends Controller
{
  public function actionIndex()
  {
    return $this->render('index');
  }

  public function actionDefault()
  {
    $id = VksTypes::find()->where(['=', 'lvl', 0])->all();
    if (!$id) {
      $data = array();
      $data = [['title' => 'База данных пуста', 'key' => -999]];
      return json_encode($data);
    }
    $roots = VksTypes::findOne($id)->tree();
    return json_encode($roots);
  }

  public function actionPlaces()
  {
    $id = VksPlaces::find()->where(['=', 'lvl', 0])->all();
    if (!$id) {
      $data = array();
      $data = [['title' => 'База данных пуста', 'key' => -999]];
      return json_encode($data);
    }
    $roots = VksPlaces::findOne($id)->tree();
    return json_encode($roots);
  }

  public function actionOrders()
  {
    $id = VksOrders::find()->where(['=', 'lvl', 0])->all();
    if (!$id) {
      $data = array();
      $data = [['title' => 'База данных пуста', 'key' => -999]];
      return json_encode($data);
    }
    $roots = VksOrders::findOne($id)->tree();
    return json_encode($roots);
  }

  public function actionSubscribersMsk()
  {
    $id = VksSubscribes::find()->where(['=', 'lvl', 0])->all();
    if (!$id) {
      $data = array();
      $data = [['title' => 'База данных пуста', 'key' => -999]];
      return json_encode($data);
    }
    $roots = VksSubscribes::findOne($id)->tree();
    $result[0] = $roots[0];                             // отображение только 0го массива (0 обязателен)
    return json_encode($result);
  }

  public function actionSubscribersReg()
  {
    $id = VksSubscribes::find()->where(['=', 'lvl', 0])->all();
    if (!$id) {
      $data = array();
      $data = [['title' => 'База данных пуста', 'key' => -999]];
      return json_encode($data);
    }
    $roots = VksSubscribes::findOne($id)->tree();
    $result[0] = $roots[1];                             // отображение только 1го массива (1 обязателен)
    return json_encode($result);
  }

  public function actionEmployees()
  {
    $id = VksEmployees::find()->where(['=', 'lvl', 0])->all();
    if (!$id) {
      $data = array();
      $data = [['title' => 'База данных пуста', 'key' => -999]];
      return json_encode($data);
    }
    $roots = VksEmployees::findOne($id)->tree();
    $result[0] = $roots[0];                             // отображение только 0го массива (0 обязателен)
    return json_encode($result);
  }

  public function actionEquipment()
  {
    $id = VksTools::find()->where(['=', 'lvl', 0])->all();
    if (!$id) {
      $data = array();
      $data = [['title' => 'База данных пуста', 'key' => -999]];
      return json_encode($data);
    }
    $roots = VksTools::findOne($id)->tree();
    return json_encode($roots);
  }

  public function actionList()
  {
    $ar = array();
    $tables = [
      'vks_types_tbl',
      'vks_places_tbl',
      'vks_orders_tbl',
      'vks_subscribes_tbl',
      'vks_subscribes_tbl',
      'vks_employees_tbl',
      'vks_tools_tbl'
    ];
    $tablesNames = [
      'Типы ВКС',
      'Места проведения',
      'Распоряжению',
      'Старшие абоненты',
      'Абоненты в субъекте',
      'Сотрудники СпецСвязи',
      'Оборудование'
    ];
    $tablesData = [
      'vks_type',
      'vks_place',
      'vks_order',
      'vks_subscriber_office',
      'vks_subscriber_reg_office',
      'vks_employee',
      'vks_equipment'
    ];
    $tablesTreeData = [
      'default',
      'places',
      'orders',
      'subscribers-msk',
      'subscribers-reg',
      'employees',
      'equipment'
    ];

    foreach ($tables as $key => $table) {
      $ar[$key]['table'] = $table;
      $ar[$key]['title'] = $tablesNames[$key];
      $ar[$key]['ident'] = $tablesData[$key];
      $ar[$key]['tree'] = $tablesTreeData[$key];
    }
    return json_encode($ar);
  }

  public function actionServerSide()
  {
    $table = 'vks_sessions_tbl';
    $primaryKey = 'id';
    $columns = array(
      array('db' => 'id', 'dt' => 0),
      array(
        'db' => 'vks_date',
        'dt' => 1,
        'formatter' => function ($d, $row) { //TODO разобраться с форматом отображения даты
          if ($d != null) {
            return date('d.m.Y', strtotime($d));
          } else {
            return '-';
          }
        }
      ),
      array('db' => 'vks_type_text', 'dt' => 4),
      array('db' => 'vks_place_text', 'dt' => 5),
      array('db' => 'vks_subscriber_office_text', 'dt' => 6),
      array('db' => 'vks_upcoming_session', 'dt' => 7),      // для группировки
      array(
        'db' => 'vks_duration_teh',
        'dt' => 10,
        'formatter' => function ($d, $row) {
          if ($d != null) {
            return $d;
          } else {
            return '-';
          }
        }
      ),
      array(
        'db' => 'vks_duration_work',
        'dt' => 11,
        'formatter' => function ($d, $row) {
          if ($d != null) {
            return $d;
          } else {
            return '-';
          }
        }
      ),
      array('db' => 'vks_teh_time_start', 'dt' => 12),
      array('db' => 'vks_teh_time_end', 'dt' => 13),
      array('db' => 'vks_work_time_start', 'dt' => 14),
      array('db' => 'vks_work_time_end', 'dt' => 15),
      array('db' => 'vks_subscriber_name', 'dt' => 16),
    );

    $sql_details = \Yii::$app->params['sql_details'];

    if ($_GET['stDate']){
      $startDate = $_GET['stDate'];
    } else {
      $startDate = "1970-01-01";
    }
    if ($_GET['eDate']){
      $endDate = $_GET['eDate'];
    } else {
      $endDate = "2099-12-31";
    }

    if (isset($_GET['lft'])) {
      if ($_GET['lft']) {
        $lft = (int)$_GET['lft'];
        $rgt = (int)$_GET['rgt'];
        $root = (int)$_GET['root'];
        $table_ex = (string)$_GET['db_tbl'];
        $identifier = (string)$_GET['identifier'];
        $whereEx = ' ' . $table . '.vks_upcoming_session = 0 AND Date(vks_date) >= "' . $startDate . '" AND Date(vks_date) <= "' . $endDate . '"  AND vks_cancel = 0';
        $where = '' . $identifier . ' in (SELECT id
    FROM ' . $table_ex . '
      WHERE ' . $table_ex . '.lft >= ' . $lft .
          ' AND ' . $table_ex . '.rgt <= ' . $rgt .
          ' AND ' . $table_ex . '.root = ' . $root . ')';
        return json_encode(
          SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, $where, $whereEx)
        );
      }
    }
    $whereEx = ' ' . $table . '.vks_upcoming_session = 0 AND Date(vks_date) >= "' . $startDate . '" AND Date(vks_date) <= "' . $endDate . '" AND vks_cancel = 0';

    return json_encode(
      SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, NULL, $whereEx)
    );
  }


}