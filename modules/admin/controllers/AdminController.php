<?php

namespace app\modules\admin\controllers;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class AdminController extends Controller
{
  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::class,
        'rules' => [
          [
            'allow' => true,
            'roles' => ['@']
          ],
        ],
      ],
    ];
  }


  public function actionIndex()
  {
    return $this->render('index');
  }

}