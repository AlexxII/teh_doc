<?php

namespace app\modules\vks\controllers\control;

use app\modules\vks\models\VksPlaces;
use yii\web\Controller;

class VksPlaceController extends Controller
{
  public $defaultAction = 'index';

  public function actionIndex()
  {
    return $this->render('index');
  }

  public function actionPlaces()
  {
    $id = VksPlaces::find()->select('id')->where(['=', 'lvl', 0])->all();
    if (!$id) {
      $data = array();
      $data = [['title' => 'База данных пуста', 'key' => -999]];
      return json_encode($data);
    }
    $roots = VksPlaces::findModel($id)->tree();
    return json_encode($roots);
  }

  public function actionVksPlaceCreate($parentId, $title)
  {
    $data = [];
    $parentPlace = VksPlaces::findModel($parentId);
    $newPlace = new VksPlaces();
    $newPlace->name = $title;
    $newPlace->parent_id = $parentPlace->id;
    $newPlace->appendTo($parentPlace);
    $data['acceptedTitle'] = $title;
    $data['acceptedId'] = $newPlace->id;
    $data['lvl'] = $newPlace->lvl;
    return json_encode($data);
  }


  public function actionUpdate($id, $title)
  {
    $place = VksPlaces::findModel($id);
    $place->name = $title;
    if ($place->save()){
      $data['acceptedTitle'] = $title;
      return json_encode($data);
    }
    return false;
  }

  public function actionMove($item, $action, $second, $parentId)
  {
    $item_model = VksPlaces::findModel($item);
    $second_model = VksPlaces::findModel($second);
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
    $parent = VksPlaces::findOne($parentId);
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
      $place = VksPlaces::findOne(['id' => $id]);
      if ($place->delete()) {
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
      $root = VksPlaces::findOne(['id' => $id]);
      if ($root->deleteWithChildren()) {
        return true;
      }
      return false;
    }
    return false;
  }

}