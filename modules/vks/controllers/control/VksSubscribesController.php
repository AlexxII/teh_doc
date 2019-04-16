<?php

namespace app\modules\vks\controllers\control;

use app\modules\vks\models\VksSubscribes;
use yii\web\Controller;

class VksSubscribesController extends Controller
{
  public $defaultAction = 'index';

  public function actionIndex()
  {
    return $this->render('index');
  }

  public function actionSubscribes()
  {
    $id = VksSubscribes::find()->where(['=', 'lvl', 0])->all();
    if (!$id) {
      $data = array();
      $data = [['title' => 'База данных пуста', 'key' => -999]];
      return json_encode($data);
    }
    $roots = VksSubscribes::findModel($id)->tree();
    return json_encode($roots);
  }

  public function actionVksSubscribesCreate($parentId, $title)
  {
    $data = [];
    $parentSubscr = VksSubscribes::findModel($parentId);
    $newSubscr = new VksSubscribes();
    $newSubscr->name = $title;
    $newSubscr->parent_id = $parentSubscr->id;
    $newSubscr->list = $parentSubscr->list;
    $newSubscr->appendTo($parentSubscr);
    $data['acceptedTitle'] = $title;
    $data['acceptedId'] = $newSubscr->id;
    $data['lvl'] = $newSubscr->lvl;
    return json_encode($data);
  }

  public function actionUpdate($id, $title)
  {
    $subscr = VksSubscribes::findModel($id);
    $subscr->name = $title;
    if ($subscr->save()){
      $data['acceptedTitle'] = $title;
      return json_encode($data);
    }
    return false;
  }

  public function actionMove($item, $action, $second, $parentId)
  {
    $item_model = VksSubscribes::findModel($item);
    $second_model = VksSubscribes::findModel($second);
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
    $parent = VksSubscribes::findModel($parentId);
    $item_model->parent_id = $parent->id;
    $item_model->list = $parent->list;
    if ($item_model->save()) {
      return true;
    }
    return false;
  }


  public function actionDelete()
  {
    if (!empty($_POST)) {
      $id = $_POST['id'];
      $subscr = VksSubscribes::findModel($id);
      if ($subscr->delete()) {
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
      $root = VksSubscribes::findModel($id);
      if ($root->deleteWithChildren()) {
        return true;
      }
      return false;
    }
    return false;
  }

  public function actionSurnames($id)
  {
    $model = VksSubscribes::findOne(['id' => $id]);
    return json_encode($model->surnames);
  }

  public function actionSurnamesSave()
  {
    if (!empty($_POST)) {
      $id = $_POST['id'];
      $model = VksSubscribes::findOne(['id' => $id]);
      $model->surnames = $_POST['Data'];
      if ($model->save()) {
        return true;
      }
      return false;
    }
  }

}