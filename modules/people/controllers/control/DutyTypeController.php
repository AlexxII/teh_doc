<?php

namespace app\modules\scheduler\controllers\control;

use Yii;
use yii\web\Controller;

use app\modules\scheduler\models\DutyType;

class DutyTypeController extends Controller
{
  public function actionAllTypes()
  {
    $id = DutyType::find()->all();
    if (!$id) {
      $data = array();
      $data = [['title' => 'База данных пуста', 'key' => -999]];
      return json_encode($data);
    }
    $roots = DutyType::findModel($id)->tree();
    return json_encode($roots);
  }

  public function actionIndex()
  {
    return $this->render('index');
  }

  public function actionCreateNode($title)
  {
    $newType = new DutyType();
    $newType->name = $title;
    $parentOrder = DutyType::findModel(['name' => 'Дежурства']);
    if ($newType->appendTo($parentOrder)) {
      $data['acceptedTitle'] = $title;
      $data['acceptedId'] = $newType->id;
      return json_encode($data);
    }
    $data = $newType->getErrors();
    return json_encode($data);
  }

  public function actionUpdateNode($id, $title)
  {
    $type = DutyType::findModel($id);
    $type->name = $title;
    if ($type->save()) {
      $data['acceptedTitle'] = $title;
      return json_encode($data);
    }
    return false;
  }

  public function actionMoveNode($item, $action, $second, $parentId)
  {
    $item_model = DutyType::findModel($item);
    $second_model = DutyType::findModel($second);
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
    $parent = DutyType::findModel($parentId);
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
      $type = DutyType::findModel($id);
      if ($type->delete()){
        return true;
      }
      return false;
    }
    return false;
  }


}