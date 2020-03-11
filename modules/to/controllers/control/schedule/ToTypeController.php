<?php

namespace app\modules\to\controllers\control\schedule;

use Yii;
use yii\web\Controller;

use app\modules\to\models\schedule\ToType;

class ToTypeController extends Controller
{
  public function actionAllTypes()
  {
    $id = ToType::find()->all();
    if (!$id) {
      $data = array();
      $data = [['title' => 'База данных пуста', 'key' => -999]];
      return json_encode($data);
    }
    $roots = ToType::findModel($id)->tree();
    return json_encode($roots);
  }

  public function actionIndex()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    return [
      'data' => [
        'success' => true,
        'data' => $this->renderAjax('index'),
        'message' => 'Page load.',
      ],
      'code' => 1,
    ];
  }


  public function actionCreateNode($title)
  {
    $newType = new ToType();
    $newType->name = $title;
    $parentOrder = ToType::findModel(['name' => 'Виды ТО']);
    if ($newType->appendTo($parentOrder)) {
      $data['acceptedTitle'] = $title;
      $data['acceptedId'] = $newType->id;
      return json_encode($data);
    }
    $data = $newType->getErrors();
    return json_encode($data);
  }

  public function actionUpdateNode($id, $title)
  {
    $type = ToType::findModel($id);
    $type->name = $title;
    if ($type->save()) {
      $data['acceptedTitle'] = $title;
      return json_encode($data);
    }
    return false;
  }

  public function actionMoveNode($item, $action, $second, $parentId)
  {
    $item_model = ToType::findModel($item);
    $second_model = ToType::findModel($second);
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
    $parent = ToType::findModel($parentId);
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
      $type = ToType::findModel($id);
      if ($type->delete()){
        return true;
      }
      return false;
    }
    return false;
  }


}