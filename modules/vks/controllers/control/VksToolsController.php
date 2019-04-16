<?php

namespace app\modules\vks\controllers\control;

use app\modules\vks\models\VksTools;
use yii\web\Controller;

class VksToolsController extends Controller
{
  public $defaultAction = 'index';

  public function actionIndex()
  {
    return $this->render('index');
  }

  public function actionTools()
  {
    $id = VksTools::find()->select('id')->where(['=', 'lvl', 0])->all();
    if (!$id) {
      $data = array();
      $data = [['title' => 'База данных пуста', 'key' => -999]];
      return json_encode($data);
    }
    $roots = VksTools::findModel($id)->tree();
    return json_encode($roots);
  }

  public function actionVksToolsCreate($parentId, $title)
  {
    $data = [];
    $parentTool = VksTools::findModel($parentId);
    $newTool = new VksTools();
    $newTool->name = $title;
    $newTool->parent_id = $parentTool->id;
    $newTool->appendTo($parentTool);
    $data['acceptedTitle'] = $title;
    $data['acceptedId'] = $newTool->id;
    $data['lvl'] = $newTool->lvl;
    return json_encode($data);
  }

  public function actionUpdate($id, $title)
  {
    $tool = VksTools::findModel($id);
    $tool->name = $title;
    if ($tool->save()) {
      $data['acceptedTitle'] = $title;
      return json_encode($data);
    }
    return false;
  }

  public function actionMove($item, $action, $second, $parentId)
  {
    $item_model = VksTools::findModel($item);
    $second_model = VksTools::findModel($second);
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
    $parent = VksTools::findModel($parentId);
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
      $tool = VksTools::findModel($id);
      if ($tool->delete()) {
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
      $root = VksTools::findModel($id);
      if ($root->deleteWithChildren()) {
        return true;
      }
      return false;
    }
    return false;
  }

  public function actionSurnames($id)
  {
    $model = VksTools::findModel($id);
    return json_encode($model->surnames);
  }

  public function actionSurnamesSave()
  {
    if (!empty($_POST)) {
      $id = $_POST['id'];
      $model = VksTools::findModel($id);
      $model->surnames = $_POST['Data'];
      if ($model->save()) {
        return true;
      }
      return false;
    }
    return false;
  }

  public function actionTool()
  {
    return 1;
  }
}
