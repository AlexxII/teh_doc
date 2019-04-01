<?php

namespace app\modules\tehdoc\modules\equipment\controllers\infoPanel;

use app\modules\tehdoc\modules\equipment\models\Tools;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class InfoController extends Controller
{

  public $layout = '@app/modules/tehdoc/modules/equipment/views/layouts/equipment_layout_info.php';

  public function actionIndex()
  {
    return $this->render('meeting');
  }

  public function actionInfo()
  {
    $id = $_GET['id'];
    $request = Tools::find()->where(['ref' => $id])->limit(1)->all();
    $model = $request[0];
    $children = $model->children(1)->all();
    $wikiCount = $model->countWikiPages;
    $imagesCount = $model->countImages;
    $docsCount = $model->countDocs;
    if ($model->complex){
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

  protected function findModel($id)
  {
    if (($model = Tools::find()->where(['ref' => $id])->limit(1)->all()) !== null) {
      if (!empty($model)) {
        return $model[0];
      }
    }
    throw new NotFoundHttpException('The requested page does not exist.');
  }

}