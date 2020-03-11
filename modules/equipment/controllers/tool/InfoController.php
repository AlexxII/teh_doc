<?php

namespace app\modules\equipment\controllers\tool;

use app\modules\equipment\models\Images;
use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;

use app\modules\equipment\models\Tools;
use app\modules\equipment\models\ToolSettings;

class InfoController extends Controller
{

  const CATEGORY_TABLE = '{{%equipment_category_tbl}}';

  public function actionIndex()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    if (!empty($_GET['id'])) {
      $id = $_GET['id'];
      $model = Tools::findModel($id);
      $children = $model->children(1)->all();
      if ($model->complex) {
        $view = 'view_complex';
      } else {
        $view = 'view_single';
      }
      return [
        'data' => [
          'success' => true,
          'data' => $this->renderAjax('index', [
            'model' => $model,
            'view' => $view,
            'children' => $children,
          ]),
          'message' => 'Page load.',
        ],
        'code' => 1,
      ];
    }
    return [
      'data' => [
        'success' => true,
        'data' => $this->renderAjax('meeting'),
        'message' => 'Page load.',
      ],
      'code' => 1,
    ];
  }

  public function actionMeeting()
  {
    return $this->render('meeting_main');
  }

  // обновление оборудования на главной страницы
  public function actionUpdate()
  {
    $id = $_GET['id'];
    $tool = Tools::findModel($id);
    $fUpload = new Images();
    $tool->scenario = Tools::SCENARIO_UPDATE;
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    if (Yii::$app->request->isAjax) {
      if ($tool->load(Yii::$app->request->post())) {
        $tool->name = $tool->eq_title;
        if ($tool->save()) {
          $children = $tool->children(1)->all();
          if ($tool->complex) {
            $view = 'view_complex';
          } else {
            $view = 'view_single';
          }
          return [
            'data' => [
              'success' => true,
              'data' => $this->renderAjax('index', [
                'model' => $tool,
                'view' => $view,
                'children' => $children
              ]),
              'message' => $tool->eq_title
            ],
            'code' => 1
          ];
        } else {
          return [
            'data' => [
              'success' => false,
              'data' => null,
              'message' => 'Update false'
            ],
            'code' => 0
          ];
        }
      }
    }
    return [
      'data' => [
        'success' => true,
        'data' => $this->renderAjax('_update_form', [
          'model' => $tool,
          'fUpload' => $fUpload
        ]),
        'message' => 'Update done'
      ],
      'code' => 1
    ];
  }

  public function actionCounters()
  {
    if (!empty($_GET['id'])) {
      $result = [];
      $id = $_GET['id'];
      $model = Tools::findModel($id);
      $result['docsCount'] = $model->countDocs;
      $result['imagesCount'] = $model->countImages;
      $result['wikiCount'] = $model->countWikiPages;
      return json_encode($result);
    }
    return json_encode(false);
  }


  public function actionCreate($root = null)
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $tool = new Tools();
    $toolSettings = new ToolSettings();
    $tool->scenario = Tools::SCENARIO_CREATE;
    $fUpload = new Images();
    $tool->quantity = 1;                             // По умолчанию, кол-во оборудования - 1
    $tool->tempId = mt_rand();

    if ($tool->load(Yii::$app->request->post())) {
      if (isset($_POST['eqId'])) {
        $tool->id = $_POST['eqId'];
      } else {
        $tool->id = $tool->tempId;
      }
      $toolSettings->eq_id = $tool->id;
      $tool->parent_id = 0;
      $tool->name = $tool->eq_title;
      if ($root) {
        $parentNode = Tools::findModel($root);
      } else {
        $parentNode = Tools::findModel(['name' => 'Необработанное']);
      }
      $tool->appendTo($parentNode);
      if ($tool->save()) {
        $toolSettings->save();                                                            // TODO необходима проверка!!!
        $children = $tool->children(1)->all();
        if ($tool->complex) {
          $view = 'view_complex';
        } else {
          $view = 'view_single';
        }
        return [
          'data' => [
            'success' => true,
            'data' => $tool->id,
            'message' => $tool->eq_title
          ],
          'code' => 1
        ];
      } else {
        return [
          'data' => [
            'success' => false,
            'data' => $tool->errors,
            'message' => 'Failed to save tool'
          ],
          'code' => 0
        ];
      }
    }
    return [
      'data' => [
        'success' => true,
        'data' => $this->renderAjax('_create_form', [
          'model' => $tool,
          'fUpload' => $fUpload
        ]),
        'message' => 'Update done'
      ],
      'code' => 1
    ];
  }

  public function actionDelete()
  {
    // TODO отстутствуют проверки!!!!
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $report = true;
    foreach ($_POST['data'] as $d) {
      $model = Tools::findModel($d);
      $images = $model->images;
      foreach ($images as $image) {
        $image->delete();
        unlink(\Yii::$app->params['uploadImg'] . $image->image_path);
      }
      $docs = $model->docs;
      foreach ($docs as $doc) {
        $doc->delete();
        unlink(\Yii::$app->params['uploadDocs'] . $doc->doc_path);
      }
      $setting = $model->settings;
      $setting->delete();
      $report = $model->delete();
      $to = $model->to;                 // найти оборудование в то
      if ($to) {
        $to->valid = 0;
        $to->save();
      }
    }
    if ($report) {
      return [
        'data' => [
          'success' => true,
          'data' => 'Tool deleted',
          'message' => 'Success'
        ],
        'code' => 1
      ];
    }
    return [
      'data' => [
        'success' => false,
        'data' => $model->errors,
        'message' => 'Failed to delete tool'
      ],
      'code' => 0
    ];
  }

  public function actionDeleteSingleTool($id)
  {
    $model = $this->findModel($id);
    $photos = $model->photos;
    foreach ($photos as $photo) {
      unlink(\Yii::$app->params['uploadPath'] . $photo->image_path);
    }
    if ($model->delete()) {
      Yii::$app->session->setFlash('success', 'Оборудование удалено');
      return $this->redirect(['index']);
    }
    Yii::$app->session->setFlash('error', 'Удалить оборудование не удалось');
    return $this->redirect(['index']);
  }


  /*  public function actionIndex()
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
    }*/

}
