<?php

namespace app\modules\to\controllers\control\schedule;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

use app\modules\to\models\schedule\ToEquipment;
use app\modules\equipment\models\Tools;

class ToEquipmentController extends Controller
{
  public function actionAllTools()
  {
    $id = ToEquipment::find()->select("id")->all();
    if (!$id) {
      $data = array();
      $data = [["title" => "База данных пуста", "key" => -999]];
      return json_encode($data);
    }
    $roots = ToEquipment::findModel($id)->tree();
    return json_encode($roots);
  }

  public function actionIndex()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    return [
      "data" => [
        "success" => true,
        "data" => $this->renderAjax("index"),
        "message" => "Page load.",
      ],
      "code" => 1,
    ];

//    return $this->renderAjax("index");
  }

  public function actionToData($id)
  {
    $parent = Tools::findModel($id);
    $children = $parent->children()->select(["id", "eq_serial", "name"])
      ->orderby(["lft" => SORT_ASC])
      ->asArray()->all();
    $toTool = $parent->to;
    $toToolInfo = [];
    $toToolInfo["toDuration"] = $toTool->to_duration;
    $toToolInfo["onDuration"] = $toTool->on_time;
    $toToolInfo["offDuration"] = $toTool->shutdown_time;
    $result = [];
    if (!empty($children)){                                     // есть вложенное оборудование
      $parentTool = [];
      $parentTool["id"] = $parent->id;
      $parentTool["eq_serial"] = $parent->eq_serial;
      $parentTool["name"] = $parent->name;
      array_unshift($children, $parentTool);
      $result["selectData"] = $children;
    } else {                                                    // вложенного оборудования нет, подтягиваем номер ноды
      if ($parent->eq_serial){
        $result["selectData"] = ["single" => $parent->eq_serial];
      } else {
        $result["selectData"] = -1;
      }
    }
    $result["toData"] = $toToolInfo;
    return json_encode($result);
  }

  public function actionToDataSave()
  {
    if (!empty($_POST)) {
      $id = $_POST["id"];
      $data = $_POST["data"];
      $model = ToEquipment::findModel($id);
      $model->eq_serial = $data["serial"];
      $model->to_duration = $data["toDuration"];
      $model->on_time = $data["onTime"];
      $model->shutdown_time = $data["blackOut"];
      if ($model->save()) {
        return true;
      }
      return false;
    }
    return false;
  }

  public function actionCreateNode($title)
  {
    $parentId = 1;
    $newGroup = new ToEquipment();
    $newGroup->name = $title;
    $parentOrder = ToEquipment::findModel(["name" => "Оборудование"]);
    $newGroup->parent_id = $parentOrder->id;
    $newGroup->eq_id = 0;
    if ($newGroup->appendTo($parentOrder)) {
      $data["acceptedTitle"] = $title;
      $data["acceptedId"] = $newGroup->id;
      return json_encode($data);
    }
    $data = $newGroup->getErrors();
    return json_encode($data);
  }

  public function actionUpdateNode($id, $title)
  {
    $tool = ToEquipment::findModel($id);
    $tool->name = $title;
    if ($tool->save()) {
      $data["acceptedTitle"] = $title;
      return json_encode($data);
    }
    return false;
  }

  public function actionMoveNode($item, $action, $second, $parentId)
  {
    $item_model = ToEquipment::findModel($item);
    $second_model = ToEquipment::findModel($second);
    switch ($action) {
      case "after":
        $item_model->insertAfter($second_model);
        break;
      case "before":
        $item_model->insertBefore($second_model);
        break;
      case "over":
        $item_model->appendTo($second_model);
        break;
    }
    $parent = ToEquipment::findModel($parentId);
    $item_model->parent_id = $parent->id;
    if ($item_model->save()) {
      return true;
    }
    return false;
  }

  // Удаляет только папки - агригаторы

  public function actionDelete()
  {
    if (!empty($_POST)) {
      // TODO: удаление или невидимый !!!!!!!
      $id = $_POST["id"];
      $toWrap = ToEquipment::findModel($id);
      if ($toWrap->eq_id == 0) {
        if ($toWrap->delete()) {
          return true;
        }
      }
      return false;
    }
    return false;
  }

}