<?php

namespace app\modules\equipment\controllers;

use app\modules\equipment\models\Tools;
use app\modules\equipment\models\ToolSettings;
use Yii;
use yii\web\Controller;

class DefaultController extends Controller
{

  public function actionIndex()
  {
    return $this->render('default', [
      'postSize' => $this::return_bytes(ini_get('post_max_size'))
    ]);
  }

  public function actionIndexEx($id)
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    return $this->renderAjax('default_ex', [
      'toolId' => $id
    ]);
  }

  //=============================================== working with tree =================================================

  public function actionAllTools()
  {
    $id = Tools::find()->select('id')->all();
    if (!$id) {
      $data = array();
      $data = [['title' => 'База данных пуста', 'key' => -999]];
      return json_encode($data);
    }
    $roots = Tools::findModel($id)->tree();
    return json_encode($roots);
  }

  public function actionCreateNode($parentId, $title)
  {
    $data = [];
    $date = date('Y-m-d H:i:s');
    $parentOrder = Tools::findModel($parentId);
    $newTool = new Tools();
    $newTool->name = $title;
    $toolSettings = new ToolSettings();
    $newTool->parent_id = $parentOrder->id;
    $newTool->eq_title = $title;
    if ($newTool->appendTo($parentOrder)) {
      $toolSettings->eq_id = $newTool->id;
      $toolSettings->save();
      $data['acceptedTitle'] = $title;
      $data['acceptedId'] = $newTool->id;
      return json_encode($data);
    }
    $data = $newTool->getErrors();
    return json_encode($data);
  }

  public function actionUpdateNode($nodeId, $title)
  {
    $tool = Tools::findModel($nodeId);
    $tool->name = $title;
    $tool->eq_title = $tool->name;
    if ($tool->save()) {
      $data['acceptedTitle'] = $title;
      return json_encode($data);
    }
    return false;
  }

  public function actionMoveNode($item, $action, $second, $parentId)
  {
    $item_model = Tools::findModel($item);
    $second_model = Tools::findModel($second);
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
    $parent = Tools::findModel($parentId);
    $item_model->parent_id = $parent->id;
    if ($item_model->save()) {
      return true;
    }
    return false;
  }

  public function actionDeleteNode()
  {
    usleep(400 * 1000);
    if (!empty($_POST)) {
      // TODO: удаление или невидимый !!!!!!!
      $id = $_POST['nodeId'];
      $category = Tools::findModel($id);
      if ($category->delete()) {
        return true;
      }
      return false;
    }
    return false;
  }

  public function actionDeleteRoot()
  {
    usleep(400 * 1000);
    if (!empty($_POST)) {
      $id = $_POST['nodeId'];
      $root = Tools::findModel($id);
      if ($root->deleteWithChildren()) {
        return true;
      }
      return false;
    }
    return false;
  }

  public static function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    $val = intval($val, 10);
    switch($last) {
      case 'g':
        $val *= 1024;
      case 'm':
        $val *= 1024;
      case 'k':
        $val *= 1024;
    }
    return $val;
  }


}