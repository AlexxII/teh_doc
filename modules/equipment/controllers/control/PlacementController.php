<?php

namespace app\modules\equipment\controllers\control;

use yii\web\Controller;

use app\modules\equipment\models\Placement;


class PlacementController extends Controller
{

  public $layout = '@app/views/layouts/main_ex.php';

  public function actionIndex()
  {
    return $this->renderAjax('index');
  }

  public function actionPlacements()
  {
    $id = Placement::find()->select('id')->where(['=', 'lvl', 0])->all();
    if (!$id) {
      $data = array();
      $data = [['title' => 'База данных пуста', 'key' => -999]];
      return json_encode($data);
    }
    $roots = Placement::findModel($id)->tree();
    return json_encode($roots);
  }

  public function actionMove($item, $action, $second, $parentId)
  {
    $item_model = Placement::findModel($item);
    $second_model = Placement::findModel($second);
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
    $parent = Placement::findModel($parentId);
    $item_model->parent_id = $parent->id;
    if ($item_model->save()) {
      return true;
    }
    return false;
  }

  public function actionCreate($parentId, $title)
  {
    $data = [];
    $category = Placement::findModel($parentId);
    $newSubcat = new Placement();
    $newSubcat->name = $title;
    $newSubcat->parent_id = $category->id;
    $newSubcat->appendTo($category);
    $data['acceptedTitle'] = $title;
    $data['acceptedId'] = $newSubcat->id;
    $data['lvl'] = $newSubcat->lvl;
    return json_encode($data);
  }

  public function actionUpdate($id, $title)
  {
    $category = Placement::findModel($id);
    $category->name = $title;
    if ($category->save()) {
      $data['acceptedTitle'] = $title;
      return json_encode($data);
    }
    return false;
  }

  public function actionDelete()
  {
    if (!empty($_POST)) {
      // TODO: удаление или невидимый !!!!!!!
      $id = $_POST['id'];
      $category = Placement::findModel($id);
      if ($category->delete()) {
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
      $root = Placement::findModel($id);
      if ($root->deleteWithChildren()) {
        return true;
      }
      return false;
    }
    return false;
  }

  public function actionGetLeaves()
  {
    $array = array();
    $leaves = Placement::find()->select('id')->leaves()->orderBy('lft')->asArray()->all();
    $categories = Placement::find()->select('id')->where(['!=', 'lvl', '0'])->orderBy('lft')->asArray()->all();
    $array['leaves'] = $leaves;
    $array['cat'] = $categories;
    return json_encode($array);
  }

}