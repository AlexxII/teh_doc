<?php

namespace app\modules\polls\controllers\settings;

use Yii;
use yii\web\Controller;
use app\modules\polls\models\Towns;

class TownsController extends Controller
{
  public $defaultAction = 'index';

  public function actionIndex()
  {
    return $this->renderAjax('index');
  }

  public function actionTowns()
  {
    $id = Towns::find()->select('id')->where(['=', 'lvl', 0])->all();
    if (!$id) {
      $data = array();
      $data = [['title' => 'База данных пуста', 'key' => -999]];
      return json_encode($data);
    }
    $roots = Towns::findModel($id)->tree();
    return json_encode($roots);
  }

  public function actionTownCreate($parentId, $title)
  {
    $data = [];
    $parentEmpl = Towns::findModel($parentId);
    $newEmpl = new Towns();
    $newEmpl->name = $title;
    $newEmpl->parent_id = $parentEmpl->id;
    $newEmpl->appendTo($parentEmpl);
    $data['acceptedTitle'] = $title;
    $data['acceptedId'] = $newEmpl->id;
    $data['lvl'] = $newEmpl->lvl;
    return json_encode($data);
  }

  public function actionUpdate($id, $title)
  {
    $empl = Towns::findModel($id);
    $empl->name = $title;
    if ($empl->save()) {
      $data['acceptedTitle'] = $title;
      return json_encode($data);
    }
    return false;
  }

  public function actionMove($item, $action, $second, $parentId)
  {
    $item_model = Towns::findModel($item);
    $second_model = Towns::findModel($second);
    switch ($action) {
      case 'after':
        $item_model->insertAfter($second_model);
        break;
      case 'before':
        $item_model->insertBefore($second_model);
        break;
      case 'over':
        $item_model->appendTo($second_model);
        break;
    }
    $parent = Towns::findModel($parentId);
    $item_model->parent_id = $parent->id;
    if ($item_model->save()) {
      return true;
    }
    return false;
  }

  public function actionDelete()
  {
    if (!empty($_POST)) {
      // TODO: удаление или невидимый !!!!!!!
      $id = $_POST['id'];
      $empl = Towns::findModel($id);
      if ($empl->delete()) {
        return true;
      }
      return false;
    }
    return false;
  }

  public function actionDeleteRoot()
  {
    if (!empty($_POST)) {
      $id = $_POST['id'];
      $root = Towns::findModel($id);
      if ($root->deleteWithChildren()) {
        return true;
      }
      return false;
    }
    return false;

  }

  public function actionSaveDetails()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    if ($_POST) {
      $id = $_POST['id'];
      $model = Towns::findModel($id);
      if ($model) {
        $input = $_POST['input'];
        $model->$input = $_POST['val'];
        if ($model->save()) {
          return [
            'data' => [
              'success' => true,
              'data' => 'model save',
              'message' => 'model save',
            ],
            'code' => 1,
          ];
        }
        return [
          'data' => [
            'success' => false,
            'data' => 'Failed to save data',
            'message' => 'Failed',
          ],
          'code' => 0,
        ];
      }
      return [
        'data' => [
          'success' => false,
          'data' => 'No model found',
          'message' => 'Failed',
        ],
        'code' => 0,
      ];
    }
    return [
      'data' => [
        'success' => false,
        'data' => 'No data in $_POST',
        'message' => 'Failed',
      ],
      'code' => 0,
    ];
  }
}