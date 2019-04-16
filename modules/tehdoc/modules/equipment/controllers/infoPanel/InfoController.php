<?php

namespace app\modules\tehdoc\modules\equipment\controllers\infoPanel;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

use app\modules\tehdoc\modules\equipment\models\Tools;

class InfoController extends Controller
{

  public $layout = '@app/modules/tehdoc/modules/equipment/views/layouts/equipment_layout_info.php';

  public function actionWrap()
  {
    return $this->render('meeting');
  }

  public function actionIndex()
  {
    return $this->render('meeting');
  }

  public function actionInfo()
  {
    $id = $_GET['id'];                                // TODO справить возможную ошибку
    $model = Tools::findModel($id);
    $children = $model->children(1)->all();
    $wikiCount = $model->countWikiPages;
    $imagesCount = $model->countImages;
    $docsCount = $model->countDocs;
    if ($model->complex) {
      $view = 'view_complex';
    } else {
      $view = 'view_single';
    }
    return $this->render('header', [
      'model' => $model,
      'view' => $view,
      'children' => $children,
      'docsCount' => $docsCount,
      'imagesCount' => $imagesCount,
      'wikiCount' => $wikiCount,
    ]);
  }

  public function actionTest()
  {
    $id = $_GET['id'];                              // TODO исправить возможную ошибку
    $model = Tools::findModel($id);
    return $this->render('test', [
      'ar' => $model->children()->all()
    ]);
  }

}