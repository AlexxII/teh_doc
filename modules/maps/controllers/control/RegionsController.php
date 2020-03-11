<?php

namespace app\modules\maps\controllers\control;

use Yii;
use yii\web\Controller;

use app\modules\maps\models\Regions;

class RegionsController extends Controller
{
  public $defaultAction = 'index';

  public function actionIndex()
  {
    return $this->renderAjax('index');
  }

  public function actionRegions()
  {
    $id = Regions::find()->select('id')->where(['=', 'lvl', 0])->all();
    if (!$id) {
      $data = array();
      $data = [['title' => 'База данных пуста', 'key' => -999]];
      return json_encode($data);
    }
    $roots = Regions::findModel($id)->tree();
    return json_encode($roots);
  }

  public function actionRegionsCreate($parentId, $title)
  {
    $data = [];
    $parentEmpl = Regions::findModel($parentId);
    $newEmpl = new Regions();
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
    $empl = Regions::findModel($id);
    $empl->name = $title;
    if ($empl->save()) {
      $data['acceptedTitle'] = $title;
      return json_encode($data);
    }
    return false;
  }

  public function actionMove($item, $action, $second, $parentId)
  {
    $item_model = Regions::findModel($item);
    $second_model = Regions::findModel($second);
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
    $parent = Regions::findModel($parentId);
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
      $empl = Regions::findModel($id);
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
      $root = Regions::findModel($id);
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
      $model = Regions::findModel($id);
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

  public function actionDetails($id)
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $model = Regions::findModel($id);
    $result = [];
    $result['number'] = $model->region_number;
    $result['city'] = $model->region_center;
    $result['area'] = $model->region_area;
    $result['area-place'] = $model->region_area_place;
    $result['population'] = $model->region_population;
    $result['population-place'] = $model->region_population_place;
    $result['temp'] = $model->region_temp;
    return $result;
  }


}