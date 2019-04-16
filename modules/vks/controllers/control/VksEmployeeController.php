<?php

namespace app\modules\vks\controllers\control;

use app\modules\vks\models\VksEmployees;
use yii\web\Controller;

class VksEmployeeController extends Controller
{
  public $defaultAction = 'index';

  public function actionIndex()
  {
    return $this->render('index');
  }

  public function actionEmployees()
  {
    $id = VksEmployees::find()->select('id')->where(['=', 'lvl', 0])->all();
    if (!$id) {
      $data = array();
      $data = [['title' => 'База данных пуста', 'key' => -999]];
      return json_encode($data);
    }
    $roots = VksEmployees::findModel($id)->tree();
    return json_encode($roots);
  }

  public function actionVksEmployeeCreate($parentId, $title)
  {
    $data = [];
    $parentEmpl = VksEmployees::findModel($parentId);
    $newEmpl = new VksEmployees();
    $newEmpl->name = $title;
    $newEmpl->parent_id = $parentEmpl->id;
    $newEmpl->list = $parentEmpl->list;
    $newEmpl->appendTo($parentEmpl);
    $data['acceptedTitle'] = $title;
    $data['acceptedId'] = $newEmpl->id;
    $data['lvl'] = $newEmpl->lvl;
    return json_encode($data);
  }

  public function actionUpdate($id, $title)
  {
    $empl = VksEmployees::findModel($id);
    $empl->name = $title;
    if ($empl->save()) {
      $data['acceptedTitle'] = $title;
      return json_encode($data);
    }
    return false;
  }

  public function actionMove($item, $action, $second, $parentId)
  {
    $item_model = VksEmployees::findModel($item);
    $second_model = VksEmployees::findModel($second);
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
    $parent = VksEmployees::findModel($parentId);
    $item_model->parent_id = $parent->id;
    $item_model->list = $parent->list;
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
      $empl = VksEmployees::findModel($id);
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
      $root = VksEmployees::findModel($id);
      if ($root->deleteWithChildren()) {
        return true;
      }
      return false;
    }
    return false;

  }


}