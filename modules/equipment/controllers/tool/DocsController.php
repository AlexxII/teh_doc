<?php

namespace app\modules\equipment\controllers\tool;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

use app\modules\equipment\models\Docs;
use app\modules\equipment\models\Tools;

class DocsController extends Controller
{
  public $defaultAction = 'index';

  public function actionIndex()
  {
    $id = $_GET['id'];
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    if ($id != 1122334455) {
      $model = Tools::findModel($id);
      $docModels = $model->docsOrder;
      $yearArray = $model->yearArrayDocs;
      return [
        'data' => [
          'success' => true,
          'data' => $this->renderAjax('index', [
            'docModels' => $docModels,
            'years' => $yearArray
          ]),
          'message' => 'Doc saved.',
        ],
        'code' => 1,
      ];
    }
    return false;
  }

  public function actionCreateAjax()
  {
    $toolId = $_GET['id'];
    $tool = Tools::findModel($toolId);
    $docModel = new Docs();
    if ($docModel->load(Yii::$app->request->post())) {
      Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      $docModel->docFiles = UploadedFile::getInstances($docModel, 'docFiles');
      $model = $docModel->uploadDoc($docModel, $toolId);
      $year = strftime("%G", strtotime($docModel->doc_date));
      $model->year = $year;
      $model->doc_date = $docModel->doc_date;
      $model->save();
      if ($model) {
        $docModels = $tool->docsOrder;
        $yearArray = $tool->yearArrayDocs;
        return [
          'data' => [
            'success' => true,
            'data' => $this->renderAjax('index', [
              'docModels' => $docModels,
              'years' => $yearArray
            ]),
            'message' => 'Doc saved.',
          ],
          'code' => 1,
        ];
      } else {
        return [
          'data' => [
            'success' => false,
            'data' => $model->errors,
            'message' => 'Saving failed.',
          ],
          'code' => 0,
        ];
      }
    }
    return $this->renderAjax('_form', [
      'model' => $docModel
    ]);
  }

  public function actionDeleteDocs()
  {
    if (!empty($_POST['data'])){
      Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      $counter = 0;
      foreach ($_POST['data'] as $docId){
        $doc = Docs::findModel($docId);
        $fileName = Yii::$app->params['uploadDocs'] . $doc->doc_path;
        if (is_file($fileName)) {
          if (unlink($fileName)) {
            $doc->delete();
            continue;
          }
        }
        $doc->delete();
      }
      $toolId = $_POST['toolId'];
      $tool = Tools::findModel($toolId);
      $docModels = $tool->docsOrder;
      $yearArray = $tool->yearArrayDocs;
      return [
        'data' => [
          'success' => true,
          'data' => $this->renderAjax('index', [
            'docModels' => $docModels,
            'years' => $yearArray
          ]),
          'message' => 'Model has been saved.',
        ],
        'code' => 1,
      ];
    }
    return [
      'data' => [
        'success' => false,
        'data' => null,
        'message' => '$_POST["data"] - empty',
      ],
      'code' => 0,
    ];
  }

}