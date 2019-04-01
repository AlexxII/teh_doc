<?php

namespace app\modules\tehdoc\modules\equipment\controllers\controlPanel;

use app\modules\tehdoc\modules\equipment\models\ToolSettings;
use yii\web\Controller;
use app\modules\tehdoc\modules\equipment\models\Tools;
use Yii;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;


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
    $tool = $this->findTool($id);
    $wikiCount = $tool->countWikiPages;
    $imagesCount = $tool->countImages;
    $docsCount = $tool->countDocs;
    $tool->scenario = Tools::SCENARIO_UPDATE;
    if ($tool->load(Yii::$app->request->post())) {
      if ($tool->save()) {
        return $this->redirect(['control-panel/' . $tool->ref . '/info/index']);
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

  protected function findTool($id)
  {
    if (($model = Tools::find()->where(['ref' => $id])->limit(1)->all()) !== null) {
      if (!empty($model)) {
        return $model[0];
      }
    }
    throw new NotFoundHttpException('The requested page does not exist.');
  }

  //=============================================== working with tree =================================================

  public function actionCreateNode($parentId, $title)
  {
    $data = [];
    $date = date('Y-m-d H:i:s');
    $parentOrder = Tools::findOne($parentId);
    $newTool = new Tools(['name' => $title]);
    $toolSettings = new ToolSettings();
    $newTool->parent_id = $parentOrder->ref;
    $newTool->ref = mt_rand();
    $newTool->eq_title = $title;
    if ($newTool->appendTo($parentOrder)) {
      $toolSettings->eq_id = $newTool->ref;
      $toolSettings->save();
      $data['acceptedTitle'] = $title;
      $data['acceptedId'] = $newTool->id;
      $data['acceptedRef'] = $newTool->ref;
      return json_encode($data);
    }
    $data = $newTool->getErrors();
    return json_encode($data);
  }

  public function actionUpdateNode($ref, $title)
  {
    $tool = Tools::findOne(['ref' => $ref]);
    $tool->name = $title;
    if ($tool->save()) {
      $data['acceptedTitle'] = $title;
      return json_encode($data);
    }
    return false;
  }

  public function actionMoveNode($item, $action, $second, $parentId)
  {
    $item_model = Tools::findOne($item);
    $second_model = Tools::findOne($second);
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
    $parent = Tools::findOne($parentId);
    $item_model->parent_id = $parent->ref;
    if ($item_model->save()) {
      return true;
    }
    return false;
  }

  public function actionDeleteNode()
  {
    if (!empty($_POST)) {
      // TODO: удаление или невидимый !!!!!!!
      $id = $_POST['id'];
      $category = Tools::findOne(['ref' => $id]);
      $category->delete();
    }
  }

  public function actionDeleteRoot()
  {
    if (!empty($_POST)) {
      $id = $_POST['id'];
      $root = Tools::findOne(['ref' => $id]);
    }
    $root->deleteWithChildren();
  }

}
