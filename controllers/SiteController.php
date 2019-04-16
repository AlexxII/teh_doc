<?php

namespace app\controllers;

use app\modules\admin\models\Testmodel;
use app\modules\admin\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::class,
        'only' => ['logout'],
        'rules' => [
          [
            'actions' => ['logout'],
            'allow' => true,
            'roles' => ['@'],
          ],
        ],
      ],
      'verbs' => [
        'class' => VerbFilter::class,
        'actions' => [
          'logout' => ['post'],
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function actions()
  {
    return [
      'error' => [
        'class' => 'yii\web\ErrorAction',
      ],
      'captcha' => [
        'class' => 'yii\captcha\CaptchaAction',
        'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
      ],
    ];
  }

  /**
   * Displays homepage.
   *
   * @return string
   */
  public function actionIndex()
  {
    return $this->render('index');
  }

  public function actionLogin()
  {
    if (!Yii::$app->user->isGuest) {
      return $this->goHome();
    }

    $model = new LoginForm();
    if ($model->load(Yii::$app->request->post()) && $model->login()) {
      return $this->goBack();
    }

    $model->password = '';
    return $this->render('login', [
      'model' => $model,
    ]);
  }

  public function actionLogout()
  {
    Yii::$app->user->logout();

    return $this->goHome();
  }

  public function actionContact()
  {
    $model = new ContactForm();
    if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
      Yii::$app->session->setFlash('contactFormSubmitted');

      return $this->refresh();
    }
    return $this->render('contact', [
      'model' => $model,
    ]);
  }

  public function actionAbout()
  {
    return $this->render('about');
  }

  public function actionAddSuperadmin()
  {
    $model = User::find()->where(['login' => 'sAdmin'])->one();
    if (empty($model)) {
      $user = new User();
      $user->username = 'Суперадмин';
      $user->login = 'sAdmin';
      $user->setPassword('vinegar');
      $user->email = 'super@admin.ru';
//      $user->ref = mt_rand();
      $user->generateAuthKey();
      if ($user->save()) {
        echo 'СуперАдмин создан';
        return $this->redirect(['set-super-role']);
      } else {
        echo 'Что-то пошло не так!';
      }
    } else {
      echo 'СуперАдмин уже был создан';
    }
  }

  public function actionSetSuperRole()
  {
    $authManager = \Yii::$app->authManager;
    $admin = $authManager->createRole('superAdmin');
    $authManager->add($admin);
    $user = User::find()->where(['login' => 'sAdmin'])->one();
    Yii::$app->authManager->assign($admin, $user->getId());
  }

  public function actionCreateRoles()
  {
    $authManager = \Yii::$app->authManager;
    $military = $authManager->createRole('military');
    $authManager->add($military);
    $civilian = $authManager->createRole('civilian');
    $authManager->add($civilian);
    return $this->redirect(['setchild']);
  }

  public function actionSetchild()
  {
    $roleMilitary = Yii::$app->authManager->getRole('military');
    $roleCivilian = Yii::$app->authManager->getRole('civilian');
    Yii::$app->authManager->addChild($roleMilitary, $roleCivilian);
    echo 'Роли добавлены и унаследованы';
  }

  public function actionTest($id)
  {
    return $this->render('test', [
      'a' => $id
    ]);
  }


//  public function actionTestt()
//  {
//    $user = User::find()->where(['login' => 'Boss'])->one();
//    $roleCivilian = Yii::$app->authManager->getRole('civilian');
//    Yii::$app->authManager->assign($roleCivilian, $user->getId());
//  }
//
//    public function actionDeleteRole()
//    {
//        $user = User::find()->where(['login' => 'Boss'])->one();
//        $auth = Yii::$app->authManager;
//        $item = $auth->getRole('civilian');
//        $item = $item ?: $auth->getPermission('civilian');
//        $auth->revoke($item, $user->getId());
//    }

  public function actionJStudy()
  {
    return $this->render('study');
  }

}
