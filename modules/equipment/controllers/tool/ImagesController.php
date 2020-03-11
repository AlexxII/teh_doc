<?php

namespace app\modules\equipment\controllers\tool;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

use app\modules\equipment\models\Tools;
use app\modules\equipment\models\Images;


class ImagesController extends Controller
{
  public $defaultAction = 'index';

  public function actionIndex()
  {
    $toolId = $_GET['id'];
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    if ($toolId != 1122334455) {
      $model = Tools::findModel($toolId);
      $photoModels = $model->images;
      return [
        'data' => [
          'success' => true,
          'data' => $this->renderAjax('index', [
            'photoModels' => $photoModels
          ]),
          'message' => 'Photos saved.',
        ],
        'code' => 1,
      ];
    }
  }

  public function actionCreate()
  {
    $toolId = $_GET['id'];
    $model = Tools::findModel($toolId);
    $imageModel = new Images();
    if ($imageModel->load(Yii::$app->request->post())) {
      Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      $imageModel->imageFiles = UploadedFile::getInstances($imageModel, 'imageFiles');
      if ($imageModel->uploadImage($toolId)) {
        $photoModels = $model->images;
        return [
          'data' => [
            'success' => true,
            'data' => $this->renderAjax('index', [
              'photoModels' => $photoModels
            ]),
            'message' => 'Photos saved.',
          ],
          'code' => 1,
        ];
      } else {
        return [
          'data' => [
            'success' => false,
            'data' => $imageModel->errors,
            'message' => 'Saving failed.',
          ],
          'code' => 0,
        ];
      }
    }
    return $this->renderAjax('_form', [
      'model' => $imageModel,
    ]);
  }

  public function actionDeleteImages()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    if (!empty($_POST['data'])) {
      foreach ($_POST['data'] as $photoId) {
        $photo = Images::findModel($photoId);
        $fileName = Yii::$app->params['uploadImg'] . $photo->image_path;
        if (is_file($fileName)) {
          if (unlink($fileName)) {
            $photo->delete();
          }
        }
        $photo->delete();
      }
      $toolId = $_POST['toolId'];
      $model = Tools::findModel($toolId);
      $photoModels = $model->images;
      return [
        'data' => [
          'success' => true,
          'data' => $this->renderAjax('index', [
            'photoModels' => $photoModels
          ]),
          'message' => 'Photos saved.',
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

  public function actionUploadImages()
  {
    $fUpload = new Images();
    if (Yii::$app->request->post()) {
      $eqId = Yii::$app->request->post('eqId');
      $fUpload->imageFiles = UploadedFile::getInstances($fUpload, 'imageFiles');
      if ($fUpload->uploadImage($eqId)) {
        return true;
      }
    }
    return false;
  }

  public function actionDeleteFromTask()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    if (!empty($_POST['key'])) {
      $photoId = $_POST['key'];
      $photo = Images::findModel($photoId);
      $fileName = Yii::$app->params['uploadImg'] . $photo->image_path;
      if (is_file($fileName)) {
        if (unlink($fileName)) {
          $photo->delete();
        }
      }
      $photo->delete();
      return true;
    } else {
      return false;
    }
  }


}