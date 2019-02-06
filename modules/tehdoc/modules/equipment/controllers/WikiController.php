<?php

namespace app\modules\tehdoc\modules\equipment\controllers;

use app\modules\tehdoc\modules\equipment\models\ComplexEx;
use app\modules\tehdoc\modules\equipment\models\Wiki;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class WikiController extends Controller
{
  public function actionIndex()
  {
    if (!empty($_POST)) {
      $id = $_POST['id'];
      $model = Wiki::find()->where(['eq_ref' => $id])->orderBy('wiki_record_create')->limit(1)->all();
      if (!empty($model)) {
        $indexModel = $model[0];
        return $this->renderPartial('index', [
          'model' => $indexModel,
          false,
          true
        ]);
      }
    }
    return $this->renderPartial('_index');
  }

  public function actionCreate()
  {
    $model = new Wiki();
    if (!empty($_POST)) {
      if ($_POST['wikiTitle']) {
        $date = date('Y-m-d H:i:s');
        $model->eq_ref = $_POST['id'];
        $model->wiki_title = $_POST['wikiTitle'];
        $model->wiki_text = $_POST['wikiText'];
        $model->wiki_record_create = $date;
        $model->wiki_record_update = $date;
        $model->wiki_created_user = Yii::$app->user->identity->ref;
        if ($model->save()) {
          return $this->renderPartial('index', [
            'model' => $model,
            false,
            true
          ]);
        }
      }
    }
    return $this->renderPartial('_form', [
      'model' => $model
    ]);
  }

  public function actionUpdate($id)
  {
    $model = $this->findModel($id);
    if (!empty($_POST['wikiTitle'])) {
      $date = date('Y-m-d H:i:s');
      $model->wiki_title = $_POST['wikiTitle'];
      $model->wiki_text = $_POST['wikiText'];
      $model->wiki_record_update = $date;
      if ($model->save()) {
        return $this->renderPartial('index', [
          'model' => $model,
          false,
          true
        ]);
      }
    }
    return $this->renderPartial('update', [
      'model' => $model, false, true
    ]);
  }

  protected function findModel($id)
  {
    // TODO не ососбо правильный метод поиска

    if (($model = Wiki::find()->where(['id' => $id])->limit(1)->all()) !== null) {
      if (!empty($model)) {
        return $model[0];
      }
    }
    throw new NotFoundHttpException('The requested page does not exist.');
  }

}