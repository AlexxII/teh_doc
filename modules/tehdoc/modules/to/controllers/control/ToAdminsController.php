<?php

namespace app\modules\tehdoc\modules\to\controllers\control;

use app\modules\tehdoc\modules\to\models\ToType;
use Yii;
use yii\web\Controller;

class ToAdminsController extends Controller
{
  public function actionAllTypes()
  {
    $id = ToType::find()->select('id')->all();
    if (!$id) {
      $data = array();
      $data = [['title' => 'База данных пуста', 'key' => -999]];
      return json_encode($data);
    }
    $roots = ToType::findOne($id)->tree();
    return json_encode($roots);
  }

  public function actionIndex()
  {
    return $this->render('index');
  }

  public function actionCreateRoot($title)
  {
    $parentId = 1;
    $newGroup = new ToType(['name' => $title]);
    $parentOrder = ToType::findOne($parentId);
    $newGroup->ref = mt_rand();
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
    $tool = ToType::findOne(['id' => $id]);
    $tool->name = $title;
    if ($tool->save()) {
      $data['acceptedTitle'] = $title;
      return json_encode($data);
    }
    return false;
  }

  public function actionMoveNode($item, $action, $second, $parentId)
  {
    $item_model = ToType::findOne($item);
    $second_model = ToType::findOne($second);
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
    $parent = ToType::findOne($parentId);
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
      $type = ToType::findOne(['ref' => $id]);
      $type->delete();
    }
  }


}