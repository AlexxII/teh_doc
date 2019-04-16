<?php

namespace app\modules\vks\controllers\control;

use app\modules\vks\models\VksTypes;
use yii\web\Controller;

class VksTypeController extends Controller
{
  public $defaultAction = 'index';

  public function actionIndex()
  {
    return $this->render('index');
  }

  public function actionTypes()
  {
    $id = VksTypes::find()->select('id')->where(['=', 'lvl', 0])->all();
    if (!$id) {
      $data = array();
      $data = [['title' => 'База данных пуста', 'key' => -999]];
      return json_encode($data);
    }
    $roots = VksTypes::findModel($id)->tree();
    return json_encode($roots);
  }

  public function actionVksTypeCreate($parentId, $title)
  {
    $data = [];
    $parentType = VksTypes::findModel($parentId);
    $newType = new VksTypes();
    $newType->name = $title;
    $newType->parent_id = $parentType->id;
    $newType->appendTo($parentType);
    $data['acceptedTitle'] = $title;
    $data['acceptedId'] = $newType->id;
    $data['lvl'] = $newType->lvl;
    return json_encode($data);
  }

  public function actionUpdate($id, $title)
  {
    $type = VksTypes::findModel($id);
    $type->name = $title;
    if ($type->save()){
      $data['acceptedTitle'] = $title;
      return json_encode($data);
    }
    return false;
  }

  public function actionMove($item, $action, $second, $parentId)
  {
    $item_model = VksTypes::findModel($item);
    $second_model = VksTypes::findModel($second);
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
    $parent = VksTypes::findModel($parentId);
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
      $type = VksTypes::findModel($id);
      if ($type->delete()) {
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
      $root = VksTypes::findModel($id);
      if ($root->deleteWithChildren()) {
        return true;
      }
      return false;
    }
    return false;
  }

}