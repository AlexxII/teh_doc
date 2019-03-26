<?php

namespace app\modules\tehdoc\modules\equipment\controllers\infoPanel;

use app\modules\tehdoc\modules\equipment\models\Docs;
use app\modules\tehdoc\modules\equipment\models\Tools;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;


class DocsController extends Controller
{
  public $defaultAction = 'index';
  public $layout = '@app/modules/tehdoc/modules/equipment/views/layouts/equipment_layout_info.php';

  public function actionIndex()
  {
    $id = $_GET['id'];
    if ($id != 1122334455) {
      $request = Tools::find()->where(['ref' => $id])->limit(1)->all();
      $model = $request[0];
      if (!empty($_GET['year'])){
        $year = $_GET['year'];
        $docModels = $model->docsYearFilter($year);
      } else {
        $docModels = $model->docsOrder;
      }
      $yearArray = $model->yearArrayDocs;
      $wikiCount = $model->countWikiPages;
      $imagesCount = $model->countImages;
      $docsCount = $model->countDocs;
      return $this->render('header', [
        'docModels' => $docModels,
        'years' => $yearArray,
        'docsCount' => $docsCount,
        'imagesCount' => $imagesCount,
        'wikiCount' => $wikiCount
      ]);
    }
    return false;
  }

  public function actionCreate()
  {
    $toolId = $_GET['id'];
    $request = Tools::find()->where(['ref' => $toolId])->limit(1)->all();
    $model = $request[0];
    $docModel = new Docs();
    $wikiCount = $model->countWikiPages;
    $imagesCount = $model->countImages;
    $docsCount = $model->countDocs;
    if ($docModel->load(Yii::$app->request->post())) {
      $docModel->docFiles = UploadedFile::getInstances($docModel, 'docFiles');
      $model = $docModel->uploadDoc($docModel, $toolId);
      $year = strftime("%G", strtotime($docModel->doc_date));
      $model->year = $year;
      $model->doc_date = $docModel->doc_date;
      $model->save();
      if ($model) {
        Yii::$app->session->setFlash('success', 'Документ добавлен');
      } else {
        Yii::$app->session->setFlash('error', 'Документ не добавлен');
      }
      $docModel = new Docs();
      return $this->redirect(['tool/' . $toolId . '/docs/index']);
    }
    return $this->render('create', [
      'model' => $docModel,
      'docsCount' => $docsCount,
      'imagesCount' => $imagesCount,
      'wikiCount' => $wikiCount
    ]);
  }

  public function actionDeleteDocs()
  {
    if (!empty($_POST['docsArray'])){
      $counter = 0;
      foreach ($_POST['docsArray'] as $docId){
        $doc = $this->findModel($docId);
        if (unlink(\Yii::$app->params['uploadDocs'] . $doc->doc_path)){
          $doc->delete();
          $counter++ ;
        }
      }
      return $counter;
    }
    return false;
  }

  protected function findModel($id)
  {
    if (($model = Docs::findOne($id)) !== null) {
        return $model;
    }
    throw new NotFoundHttpException('The requested page does not exist.');
  }
}
