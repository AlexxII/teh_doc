<?php

namespace app\modules\tehdoc\controllers\control;

use app\modules\admin\models\Category;
use yii\web\Controller;

class CategoryController extends Controller
{

  public function actionIndex()
  {
    return $this->render('index');
  }

  public function actionCategories()
  {
    $id = Category::find()->select('id, rgt, lft, root')->where(['=', 'lvl', 0])->all();
    if (!$id) {
      $data = array();
      $data = [['title' => 'База данных пуста', 'key' => -999]];
      return json_encode($data);
    }
    $roots = Category::findOne($id)->tree();
    return json_encode($roots);
  }

  public function actionMove($item, $action, $second, $parentId)
  {
    $item_model = Category::findOne($item);
    $second_model = Category::findOne($second);
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
    $parent = Category::findOne($parentId);
    $item_model->parent_id = $parent->ref;
    if ($item_model->save()) {
      return true;
    }
    return false;
  }

  public function actionCreateRoot($title)
  {
    \Yii::$app->db->schema->refresh();
    $newRoot = new Category(['name' => $title]);
    $result = $newRoot->makeRoot();
    if ($result) {
      $data['acceptedTitle'] = $title;
      return json_encode($data);
    } else {
      return var_dump('0');
    }
  }

  public function actionCreate($parentId, $title)
  {
    $data = [];
    $category = Category::findOne($parentId);
    $newSubcat = new Category(['name' => $title]);
    $newSubcat->parent_id = $category->ref;
    $newSubcat->ref = mt_rand();
    $newSubcat->appendTo($category);
    $data['acceptedTitle'] = $title;
    $data['acceptedId'] = $newSubcat->id;
    return json_encode($data);
  }

  public function actionUpdate($id, $title)
  {
    $category = Category::findOne(['id' => $id]);
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
      $category = Category::findOne(['id' => $id]);
      $category->delete();
    }
  }

  public function actionDeleteRoot()
  {
    if (!empty($_POST)) {
      $id = $_POST['id'];
      $root = Category::findOne(['id' => $id]);
    }
    $root->deleteWithChildren();
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