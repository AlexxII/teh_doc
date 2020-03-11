<?php

namespace app\modules\equipment\controllers\control;

use yii\web\Controller;

use app\modules\equipment\models\Category;


class CategoryController extends Controller
{

  public function actionIndex()
  {
    return $this->renderAjax('index');
  }

  public function actionCategories()
  {
    $id = Category::find()->select('id')->where(['=', 'lvl', 0])->all();
    if (!$id) {
      $data = array();
      $data = [['title' => 'База данных пуста', 'key' => -999]];
      return json_encode($data);
    }
    $roots = Category::findModel($id)->tree();
    return json_encode($roots);
  }

  public function actionMove($item, $action, $second, $parentId)
  {
    $item_model = Category::findModel($item);
    $second_model = Category::findModel($second);
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
    $parent = Category::findModel($parentId);
    $item_model->parent_id = $parent->id;
    if ($item_model->save()) {
      $parent->save();
      return true;
    }
    return false;
  }

  public function actionCreate($parentId, $title)
  {
    $data = [];
    $category = Category::findModel($parentId);
    $newSubcat = new Category();
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
    $category = Category::findModel($id);                       // TODO опасно!!!!
    $category->name = $title;
    if ($category->save()){
      $data['acceptedTitle'] = $title;
      return json_encode($data);
    }
    return false;
  }

  public function actionDelete()
  {
    usleep(400*1000);
    if (!empty($_POST)) {
      // TODO: удаление или невидимый !!!!!!!
      $id = $_POST['id'];
      $category = Category::findModel($id);
      if ($category->delete()){
        return true;
      }
      return false;
    }
    return false;
  }

  public function actionDeleteRoot()
  {
    usleep(400*1000);
    if (!empty($_POST)) {
      $id = $_POST['id'];
      $root = Category::findModel($id);
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
    $leaves = Category::find()->select('id')->leaves()->orderBy('lft')->asArray()->all();
    $categories = Category::find()->select('id')->where(['!=', 'lvl', '0'])->orderBy('lft')->asArray()->all();
    $array['leaves'] = $leaves;
    $array['cat'] = $categories;
    return json_encode($array);
  }


}