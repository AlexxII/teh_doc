<?php

namespace app\modules\equipment\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;

use app\modules\equipment\models\Images;
use app\modules\equipment\models\Tools;
use app\modules\equipment\models\ToolSettings;

class TaskController extends Controller
{
  public $layout = '@app/views/layouts/main_ex.php';

  public function actionIndex()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    Yii::$app->view->params['bUrl'] = $_GET['back-url'];
    $models = Tools::find()
      ->where(['settings_table.eq_task' => 1])
      ->joinWith('settings settings_table')
      ->orderBy('lft')
      ->all();
    Yii::$app->view->params['title'] = 'Задание';
    return [
      'data' => [
        'success' => true,
        'data' => $this->render('task', [
          'models' => $models,
        ]),
        'message' => 'Page load',
      ],
      'code' => 1,
    ];
  }

  // серверная часть установки флажка "В задании на обновление"
  public function actionSet()
  {
    if (isset($_POST['toolId'])) {
      $toolId = $_POST['toolId'];
      $model = ToolSettings::findModel($toolId);
      Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      if (isset($_POST['bool'])) {
        if ($_POST['bool'] === '1') {
          $model->eq_task = 1;
        } else {
          $model->eq_task = 0;
        }
      } else {
        return [
          'data' => [
            'success' => false,
            'model' => null,
            'message' => '$_POST["bool"] empty'
          ],
          'code' => 0
        ];
      }
      if ($model->save()) {
        return [
          'data' => [
            'success' => true,
            'data' => $model->eq_task,
            'message' => 'Done'
          ],
          'code' => 1
        ];
      }
      return [
        'data' => [
          'success' => false,
          'data' => $model->errors,
          'message' => 'Model error occured'
        ],
        'code' => 0
      ];
    }
    return [
      'data' => [
        'success' => false,
        'model' => null,
        'message' => '$_POST["toolId"] empty'
      ],
      'code' => 0
    ];
  }

// серверная часть установки флажка "В задании на обновление" - пакетная обработка
  public function actionTaskSetPckg()
  {
    sleep(1);
    if (isset($_POST['jsonData']) && isset($_POST['bool'])) {
      if ($_POST['bool'] === 'true') {
        $bool = 1;
      } else {
        $bool = 0;
      }
      $result = false;
      foreach ($_POST['jsonData'] as $toolId) {
        $model = ToolSettings::findModel($toolId);
        $model->eq_task = $bool;
        $result = $model->save();
      }
      return $result;
    }
    return false;
  }

  public function actionUpdateTaskTool()
  {
    $id = $_GET['id'];
    $tool = Tools::findModel($id);
    $fUpload = new Images();
    $tool->scenario = Tools::SCENARIO_UPDATE;
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    if (Yii::$app->request->isAjax) {
      if ($tool->load(Yii::$app->request->post())) {
        if ($tool->save()) {
          $children = $tool->children(1)->all();
          if ($tool->complex) {
            $view = 'view_complex';
          } else {
            $view = 'view_single';
          }
          return [
            'data' => [
              'success' => true,
              'data' => [
                'image' => $tool->countImages,
                'title' => $tool->eq_title
              ],
              'message' => 'Update done'
            ],
            'code' => 1
          ];
        } else {
          return [
            'data' => [
              'success' => false,
              'data' => null,
              'message' => 'Update false'
            ],
            'code' => 0
          ];
        }
      }
    }
    return [
      'data' => [
        'success' => true,
        'data' => $this->renderAjax('_form', [
          'model' => $tool,
          'fUpload' => $fUpload
        ]),
        'message' => 'Update done'
      ],
      'code' => 1
    ];
  }

  public function actionUpdateImageCount()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    if (!empty($_GET['id'])){
      $id = $_GET['id'];
      $tool = Tools::findModel($id);
      return [
        'data' => [
          'success' => true,
          'data' => $tool->countImages,
          'message' => 'Images was count'
        ],
        'code' => 1
      ];
    }
    return [
      'data' => [
        'success' => false,
        'data' => null,
        'message' => 'Empty $_GET["id"]'
      ],
      'code' => 0
    ];
  }


}