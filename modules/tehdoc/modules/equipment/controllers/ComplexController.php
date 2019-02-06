<?php

namespace app\modules\tehdoc\modules\equipment\controllers;

use app\base\ModelEx;
use app\modules\tehdoc\models\Images;
use app\modules\tehdoc\modules\equipment\models\Complex;
use app\modules\tehdoc\modules\equipment\models\ComplexEx;
use app\modules\tehdoc\modules\equipment\models\SSP;
use app\modules\tehdoc\modules\equipment\models\Tools;
use app\modules\tehdoc\modules\equipment\models\Wiki;
use yii\base\DynamicModel;
use yii\web\Controller;
use Yii;
use app\base\Model;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;

class ComplexController extends Controller
{
  public function actionIndex()
  {
    return $this->render('index');
  }

  public function actionComplexes()
  {
    $id = ComplexEx::find()->select('id, rgt, lft, root')->all();
    if (!$id) {
      $data = array();
      $data = [['title' => 'База данных пуста', 'key' => -999]];
      return json_encode($data);
    }
    $roots = ComplexEx::findOne($id)->tree();
    return json_encode($roots);
  }

  public function actionCreate($parentId, $title)
  {
    $data = [];
    $date = date('Y-m-d H:i:s');
    $parentOrder = ComplexEx::findOne($parentId);
    $newComplex = new ComplexEx(['name' => $title]);
    $newComplex->parent_id = $parentOrder->ref;
    $newComplex->ref = mt_rand();
    $newComplex->key = $newComplex->ref;
    $newComplex->complex_title = $title;

    $newWiki = new Wiki();
    $newWiki->eq_ref = $newComplex->ref;
    $newWiki->wiki_title = 'Home';
    $newWiki->wiki_record_create = $date;
    $newWiki->wiki_record_update = $date;
    $newWiki->wiki_created_user = Yii::$app->user->identity->ref;

    if ($newComplex->appendTo($parentOrder)){
      $newWiki->save();
      $data['acceptedTitle'] = $title;
      $data['acceptedId'] = $newComplex->id;
      $data['acceptedRef'] = $newComplex->ref;
      return json_encode($data);
    }
    $data = $newComplex->getErrors();
    return json_encode($data);
  }

  public function actionUpdate($id, $title)
  {
    $order = ComplexEx::findOne(['ref' => $id]);
    $order->name = $title;
    if ($order->save()) {
      $data['acceptedTitle'] = $title;
      return json_encode($data);
    }
    return false;
  }

  public function actionUpdateC($id)
  {
    $model = $this->findModel($id);
    $fUpload = new Images();

    if ($model->load(Yii::$app->request->post())) {
      if ($model->save(false)) {                                    // TODO Разобраться с валидацией, при вкл - не сохраняет
        if ($fUpload->load(Yii::$app->request->post())) {
          $fUpload->imageFiles = UploadedFile::getInstances($fUpload, 'imageFiles');
          if ($fUpload->uploadImage($model->ref)) {
            Yii::$app->session->setFlash('success', 'Изменения внесены');
          }
        } else {
          Yii::$app->session->setFlash('success', 'Изменения внесены!!');
        }
        return $this->redirect(['view', 'id' => $model->ref]);
      } else {
        Yii::$app->session->setFlash('error', 'Изменения НЕ внесены');
      }
    }
    return $this->render('update', [
      'model' => $model,
      'fupload' => $fUpload,
    ]);
  }

  public function actionCreateC($id)
  {
    $modelComplex = new ComplexEx();
    $fUpload = new Images();
    $modelComplex->quantity = 1;                             // По умолчанию, кол-во оборудования - 1.php

    if ($modelComplex->load(Yii::$app->request->post())) {
      $modelComplex->ref = mt_rand();
      $modelComplex->parent_id = 0;
      if ($modelComplex->save()) {
        if ($fUpload->load(Yii::$app->request->post())) {
          $fUpload->imageFiles = UploadedFile::getInstances($fUpload, 'imageFiles');
          if ($fUpload->uploadImage($modelComplex->ref)) {
            Yii::$app->session->setFlash('success', 'Оборудование добавлено');
          } else {
            Yii::$app->session->setFlash('success', 'Оборудование добавлено, <strong>НО</strong> не загружены изображения');
          }
        } else {
          Yii::$app->session->setFlash('success', 'Оборудование добавлено');
        }
        if (isset($_POST['stay'])) {
          return $this->redirect(['create']);
        }
        return $this->redirect(['view', 'id' => $modelComplex->ref]);
      } else {
        Yii::$app->session->setFlash('error', 'Ошибка валидации');
      }
    }
    return $this->render('create', [
      'modelComplex' => $modelComplex,
      'fupload' => $fUpload
    ]);
  }


  public function actionMove($item, $action, $second, $parentId)
  {
    $item_model = ComplexEx::findOne($item);
    $second_model = ComplexEx::findOne($second);
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
    $parent = ComplexEx::findOne($parentId);
    $item_model->parent_id = $parent->ref;
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
      $category = ComplexEx::findOne(['id' => $id]);
      $category->delete();
    }
  }

  public function actionDeleteRoot()
  {
    if (!empty($_POST)) {
      $id = $_POST['id'];
      $root = ComplexEx::findOne(['id' => $id]);
    }
    $root->deleteWithChildren();
  }

  public function actionInfo()
  {
    if (!empty($_POST)) {
      $id = $_POST['id'];
      return $this->renderPartial('info/view', [
        'model' => $this->findModel($id),
      ]);
    }
    return false;
  }

  public function actionFiles()
  {
    if (!empty($_POST)) {
      $id = $_POST['id'];
      return $this->renderPartial('files/index', [
        'model' => $this->findModel($id),
      ]);
    }
    return false;
  }

  public function actionWiki()
  {
    if (!empty($_POST)) {
      $id = $_POST['id'];
      return $this->renderPartial('wiki/index', [
        'model' => $this->findModel($id),
      ]);
    }
    return false;
  }

  public function actionLog()
  {
    if (!empty($_POST)) {
      $id = $_POST['id'];
      return $this->renderPartial('log/index', [
        'model' => $this->findModel($id),
      ]);
    }
    return false;
  }

  protected function findModel($id)
  {
    if (($model = ComplexEx::find()->where(['ref' => $id])->limit(1)->all()) !== null) {
      if (!empty($model)) {
        return $model[0];
      }
    }
    throw new NotFoundHttpException('The requested page does not exist.');
  }

}