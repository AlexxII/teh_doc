<?php

namespace app\modules\tehdoc\modules\equipment\controllers\infoPanel;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

use app\modules\tehdoc\modules\equipment\models\Docs;
use app\modules\tehdoc\modules\equipment\models\Tools;


class DocsController extends Controller
{
  public $defaultAction = 'index';
  public $layout = '@app/modules/tehdoc/modules/equipment/views/layouts/equipment_layout_info.php';

  public function actionIndex()
  {
    $id = $_GET['id'];
    if ($id != 1122334455) {
      $model = Tools::findModel($id);
      $selectYear = 0;
      if (!empty($_GET['year'])){
        $year = $_GET['year'];
        $selectYear = $year;
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
        'sYear' => $selectYear,
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
    $model = Tools::findModel($toolId);
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
        $doc = Docs::findModel($docId);
        if (unlink(\Yii::$app->params['uploadDocs'] . $doc->doc_path)){
          $doc->delete();
          $counter++ ;
        }
      }
      return $counter;
    }
    return false;
  }

}
