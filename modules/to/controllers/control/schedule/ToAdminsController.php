<?php

namespace app\modules\to\controllers\control\schedule;

use Yii;
use yii\web\Controller;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use app\modules\admin\models\User;
use app\modules\to\models\schedule\ToAdmins;

class ToAdminsController extends Controller
{
  public function actionAllAdmins()
  {
    $id = ToAdmins::find()->all();
    if (!$id) {
      $data = array();
      $data = [['title' => 'База данных пуста', 'key' => -999]];
      return json_encode($data);
    }
    $roots = ToAdmins::findOne($id)->tree();
    return json_encode($roots);
  }

  public function actionIndex()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $model = new ToAdmins();
    return [
      'data' => [
        'success' => true,
        'data' => $this->renderAjax('index', [
          'model' => $model
        ]),
        'message' => 'Page load.',
      ],
      'code' => 1,
    ];
  }

  public function actionCreateNode($title)
  {
    $newAdmin = new ToAdmins();
    $newAdmin->name = $title;
    $parentOrder = ToAdmins::findModel(['name' => 'Сотрудники, участвующие в ТО']);
    if ($newAdmin->appendTo($parentOrder)) {
      $data['acceptedTitle'] = $title;
      $data['acceptedId'] = $newAdmin->id;
      return json_encode($data);
    }
    $data = $newAdmin->getErrors();
    return json_encode($data);
  }

  public function actionUpdateNode($id, $title)
  {
    $admin = ToAdmins::findModel($id);
    $admin->name = $title;
    if ($admin->save()) {
      $data['acceptedTitle'] = $title;
      return json_encode($data);
    }
    return false;
  }

  public function actionMoveNode($item, $action, $second, $parentId)
  {
    $item_model = ToAdmins::findModel($item);
    $second_model = ToAdmins::findModel($second);
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
    $parent = ToAdmins::findModel($parentId);
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
      $type = ToAdmins::findModel($id);
      if ($type->delete()) {
        return true;
      }
      return false;
    }
    return false;
  }

  public function actionSaveSettings()
  {
    if (!empty($_POST['id'])) {
      $id = $_POST['id'];
      $admin = ToAdmins::findModel($id);
      if (!empty($_POST['userVal'])){
        $admin->user_id = $_POST['userVal'];
      }
      $admin->admin = $_POST['roleVal'];
      if ($admin->save()){
        return true;
      }
      return false;
    }
    return false;
  }

}