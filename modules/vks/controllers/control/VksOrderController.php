<?php

namespace app\modules\vks\controllers\control;

use app\modules\vks\models\VksOrders;
use yii\web\Controller;

class VksOrderController extends Controller
{
  public $defaultAction = 'index';

  public function actionIndex()
  {
    return $this->render('index');
  }

  public function actionOrders()
  {
    $id = VksOrders::find()->select('id, rgt, lft, root')->where(['=', 'lvl', 0])->all();
    if (!$id) {
      $data = array();
      $data = [['title' => 'База данных пуста', 'key' => -999]];
      return json_encode($data);
    }
    $roots = VksOrders::findOne($id)->tree();
    return json_encode($roots);
  }

  public function actionVksOrderCreate($parentId, $title)
  {
    $data = [];
    $parentOrder = VksOrders::findModel($parentId);
    $newOrder = new VksOrders();
    $newOrder->name = $title;
    $newOrder->parent_id = $parentOrder->id;
    $newOrder->appendTo($parentOrder);
    $data['acceptedTitle'] = $title;
    $data['acceptedId'] = $newOrder->id;
    $data['lvl'] = $newOrder->lvl;
    return json_encode($data);
  }

  public function actionUpdate($id, $title)
  {
    $order = VksOrders::findModel($id);
    $order->name = $title;
    if ($order->save()) {
      $data['acceptedTitle'] = $title;
      return json_encode($data);
    }
    return false;
  }

  public function actionMove($item, $action, $second, $parentId)
  {
    $item_model = VksOrders::findModel($item);
    $second_model = VksOrders::findModel($second);
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
    $parent = VksOrders::findModel($parentId);
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
      $order = VksOrders::findModel($id);
      if ($order->delete()) {
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
      $root = VksOrders::findModel($id);
      if ($root->deleteWithChildren()) {
        return true;
      }
      return false;
    }
    return false;
  }

}