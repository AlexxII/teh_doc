<?php

namespace app\modules\tehdoc\modules\equipment\controllers\controlPanel;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;

use app\modules\tehdoc\modules\equipment\models\Tools;
use app\modules\tehdoc\modules\equipment\models\ToolSettings;


class InfoController extends Controller
{

  const CATEGORY_TABLE = '{{%teh_category_tbl}}';

  public $layout = '@app/modules/tehdoc/modules/equipment/views/layouts/equipment_layout_control.php';

  public function actionMeeting()
  {
    return $this->render('meeting_main');
  }

  public function actionIndex()
  {
    $id = $_GET['id'];
    if ($id == 1122334455){
      return $this->render('meeting_main');
    } else if ($id == 5544332211) {
      return $this->render('meeting_waiting');
    }
    $tool = Tools::findModel($id);
    $wikiCount = $tool->countWikiPages;
    $imagesCount = $tool->countImages;
    $docsCount = $tool->countDocs;
    $tool->scenario = Tools::SCENARIO_UPDATE;
    if ($tool->load(Yii::$app->request->post())) {

      if ($tool->save()) {
        return $this->redirect(['control-panel/' . $tool->id . '/info/index']);
      } else {
        Yii::$app->session->setFlash('error', 'Изменения НЕ внесены');
      }
    }
    return $this->render('index', [
      'model' => $tool,
      'docsCount' => $docsCount,
      'imagesCount' => $imagesCount,
      'wikiCount' => $wikiCount,
    ]);
  }




  //=============================================== working with tree =================================================

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
    usleep(400*1000);
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
    usleep(400*1000);
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

}
