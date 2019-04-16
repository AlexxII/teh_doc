<?php

namespace app\modules\tehdoc\modules\to\controllers\control;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;

use app\modules\tehdoc\modules\to\models\ToEquipment;
use app\modules\tehdoc\modules\equipment\models\Tools;
use app\modules\tehdoc\modules\equipment\models\ToolSettings;


class ToEquipmentController extends Controller
{

  public function actionAllTools()
  {
    $id = ToEquipment::find()->select('id')->all();
    if (!$id) {
      $data = array();
      $data = [['title' => 'База данных пуста', 'key' => -999]];
      return json_encode($data);
    }
    $roots = ToEquipment::findModel($id)->tree();
    return json_encode($roots);
  }

  public function actionIndex()
  {
    return $this->render('index');
  }

  public function actionToolsSerials($id)
  {
    $root = Tools::findModel($id);
    $children = $root->children()->select(['ref', 'eq_serial', 'name'])
      ->orderby(['lft' => SORT_ASC])
      ->asArray()->all();
    if (!empty($children)){
      return json_encode($children);
    } else {
      if ($root->eq_serial){
        return json_encode(['single' => $root->eq_serial]);
      } else {
        return -1;
      }
    }
    return false;
  }

  public function actionToolSerialSave()
  {
    if (!empty($_POST)) {
      $id = $_POST['id'];
      $model = ToEquipment::findModel($id);
      $model->eq_serial = $_POST['serial'];
      if ($model->save()) {
        return true;
      }
      return false;
    }
    return false;
  }


  public function actionCreateRoot($title)
  {
    $parentId = 1;
    $newGroup = new ToEquipment(['name' => $title]);
    $parentOrder = ToEquipment::findModel($parentId);
    $newGroup->parent_id = $parentOrder->ref;
    $newGroup->eq_id = 0;
    $newGroup->ref = 0;
    if ($newGroup->appendTo($parentOrder)) {
      $data['acceptedTitle'] = $title;
      $data['acceptedId'] = $newGroup->id;
      $data['acceptedRef'] = $newGroup->ref;
      return json_encode($data);
    }
    $data = $newGroup->getErrors();
    return json_encode($data);
  }

  public function actionUpdateNode($id, $title)
  {
    $tool = ToEquipment::findModel($id);
    $tool->name = $title;
    if ($tool->save()) {
      $data['acceptedTitle'] = $title;
      return json_encode($data);
    }
    return false;
  }

  public function actionMoveNode($item, $action, $second, $parentId)
  {
    $item_model = ToEquipment::findModel($item);
    $second_model = ToEquipment::findModel($second);
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
    $parent = ToEquipment::findModel($parentId);
    $item_model->parent_id = $parent->ref;
    if ($item_model->save()) {
      return true;
    }
    return false;
  }

  public function actionDeleteNode()
  {
    if (!empty($_POST)) {
      // TODO: удаление или невидимый !!!!!!!
      $id = $_POST['id'];
      $category = ToEquipment::findModel($id);
      $category->delete();
    }
  }

  public function actionDeleteRoot()
  {
    if (!empty($_POST)) {
      $id = $_POST['id'];
      $root = ToEquipment::findModel($id);
      $children = $root->children()->all();
      foreach ($children as $child){
        $settings = ToolSettings::findModel($child->ref);
        $settings->eq_to = 0;
      }
      if ($root->deleteWithChildren()){
        return true;
      }
      return false;
    }
    return false;
  }

}