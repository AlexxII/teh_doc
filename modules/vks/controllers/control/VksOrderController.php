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
    $parentOrder = VksOrders::findOne($parentId);
    $newOrder = new VksOrders(['name' => $title]);
    $newOrder->parent_id = $parentOrder->ref;
    $newOrder->ref = mt_rand();
    $newOrder->appendTo($parentOrder);
    $data['acceptedTitle'] = $title;
    $data['acceptedId'] = $newOrder->id;
    return json_encode($data);
  }

  public function actionCreateRoot($title)
  {
    $newRoot = new VksOrders(['name' => $title]);
    $result = $newRoot->makeRoot();
    if ($result) {
      $data['acceptedTitle'] = $title;
      return json_encode($data);
    } else {
      return var_dump('0');
    }
  }

  public function actionUpdate($id, $title)
  {
    $order = VksOrders::findOne(['id' => $id]);
    $order->name = $title;
    if ($order->save()){
      $data['acceptedTitle'] = $title;
      return json_encode($data);
    }
    return false;
  }

  public function actionMove($item, $action, $second, $parentId)
  {
    $item_model = VksOrders::findOne($item);
    $second_model = VksOrders::findOne($second);
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
    $parent = VksOrders::findOne($parentId);
    $item_model->parent_id = $parent->ref;
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
      $order = VksOrders::findOne(['id' => $id]);
      $order->delete();
    }
  }

  public function actionDeleteRoot()
  {
    if (!empty($_POST)) {
      $id = $_POST['id'];
      $root = VksOrders::findOne(['id' => $id]);
    }
    $root->deleteWithChildren();
  }

}