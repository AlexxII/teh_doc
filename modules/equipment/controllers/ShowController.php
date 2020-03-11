<?php

namespace app\modules\equipment\controllers;

use Yii;
use yii\web\Controller;

use app\modules\equipment\models\SSP;

class ShowController extends Controller
{

  public $layout = '@app/views/layouts/main_ex.php';

  public function actionCategories()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    Yii::$app->view->params['bUrl'] = $_GET['back-url'];
    Yii::$app->view->params['title'] = 'По категориям';
    return [
      'data' => [
        'success' => true,
        'data' => $this->render('categories'),
        'message' => 'Page load',
      ],
      'code' => 1,
    ];
  }

  public function actionPlacement()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    Yii::$app->view->params['bUrl'] = $_GET['back-url'];
    Yii::$app->view->params['title'] = 'По размещению';
    return [
      'data' => [
        'success' => true,
        'data' => $this->render('placements'),
        'message' => 'Page load',
      ],
      'code' => 1,
    ];
  }

  public function actionServerSide()
  {
    $table = 'equipment_tools_tbl';
    $primaryKey = 'id';
    $columns = array(
      array('db' => 'id', 'dt' => 0),
      array('db' => 'eq_title', 'dt' => 1),
      array('db' => 'eq_manufact', 'dt' => 2),
      array('db' => 'eq_model', 'dt' => 3),
      array('db' => 'eq_serial', 'dt' => 4),
      array(
        'db' => 'eq_factdate',
        'dt' => 5,
        'formatter' => function ($d, $row) { //TODO разобраться с форматом отображения даты
          if ($d != null) {
            return date('jS M y', strtotime($d));
          } else {
            return '-';
          }
        }
      ),
      array(
        'db' => 'quantity',
        'dt' => 6,
        'formatter' => function ($d, $row) { //TODO
          return $d . ' шт.';
        }
      ),
    );

    $sql_details = \Yii::$app->params['sql_details'];

    if (isset($_GET['lft'])) {
      if ($_GET['lft']) {
        $lft = (int)$_GET['lft'];
        $rgt = (int)$_GET['rgt'];
        $root = (int)$_GET['root'];
        $table_ex = (string)$_GET['db_tbl'];
        $identifier = (string)$_GET['identifier'];
        $where = ' ' . $identifier . ' in (SELECT id
    FROM ' . $table_ex . '
      WHERE ' . $table_ex . '.lft >= ' . $lft .
          ' AND ' . $table_ex . '.rgt <= ' . $rgt .
          ' AND ' . $table_ex . '.root = ' . $root . ')';

        return json_encode(
          SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, NULL, $where)
        );
      }
    }
    if (isset($_GET['index'])) {
      $index = $_GET['index'];
      $where = ' id in (SELECT eq_id FROM equipment_settings_tbl WHERE ' . $index . '= 1)';
    } else {
      $where = ' lvl != 0';
    }

    $result = SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, NULL, $where);

    return json_encode($result);
  }
}