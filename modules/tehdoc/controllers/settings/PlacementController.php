<?php

namespace app\modules\tehdoc\controllers\settings;

use app\modules\admin\models\Placement;
use yii\web\Controller;

class PlacementController extends Controller
{

  public function actionIndex()
  {
    return $this->render('index');
  }

  public function actionPlacements()
  {
    $id = Placement::find()->select('id, lft, rgt, root')->where(['=', 'lvl', 0])->all();
    if (!$id) {
      $data = array();
      $data = [['title' => 'База данных пуста', 'key' => -999]];
      return json_encode($data);
    }
    $roots = Placement::findOne($id)->tree();
    return json_encode($roots);
  }

  public function actionMove($item, $action, $second, $parentId)
  {
    $item_model = Placement::findOne($item);
    $second_model = Placement::findOne($second);
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
    $parent = Placement::findOne($parentId);
    $item_model->parent_id = $parent->ref;
    if ($item_model->save()) {
      return true;
    }
    return false;
  }

  public function actionCreateRoot($title)
  {
    $newRoot = new Placement(['name' => $title]);
    $newRoot->makeRoot();
    $data['acceptedTitle'] = $title;
    return json_encode($data);
  }

  public function actionCreate($parentId, $title)
  {
    $data = [];
    $category = Placement::findOne($parentId);
    $newSubcat = new Placement(['name' => $title]);
    $newSubcat->parent_id = $category->ref;
    $newSubcat->ref = mt_rand();
    $newSubcat->appendTo($category);
    $data['acceptedTitle'] = $title;
    $data['acceptedId'] = $newSubcat->id;
    return json_encode($data);
  }

  public function actionUpdate($id, $title)
  {
    $category = Placement::findOne(['id' => $id]);
    $category->name = $title;
    if ($category->save()){
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
      $category = Placement::findOne(['id' => $id]);
      $category->delete();
    }
  }

  public function actionDeleteRoot()
  {
    if (!empty($_POST)) {
      $id = $_POST['id'];
      $root = Placement::findOne(['id' => $id]);
    }
    $root->deleteWithChildren();
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