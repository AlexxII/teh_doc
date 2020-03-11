<?php

namespace app\modules\maps\controllers;

use app\modules\maps\models\Regions;
use Yii;
use yii\web\Controller;

class MapRusController extends Controller
{

  public function actionIndex()
  {
    return $this->render('index');
  }

  public function actionDetail($number)
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    if ($number) {
      $model = Regions::find()->where(['=', 'region_number', $number])->one();
      return [
        'data' => [
          'success' => true,
          'data' => $this->renderAjax('_detail', [
            'model' => $model
          ]),
          'message' => 'Page load.',
        ],
        'code' => 1,
      ];
    }
    return [
      'data' => [
        'success' => false,
        'data' => 'No data in $_GET',
        'message' => 'Failed',
      ],
      'code' => 0,
    ];
  }

  public function actionColor()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $regions = Regions::find()->select('region_number, parent_id')->where(['=', 'lvl', 1])->asArray()->all();
    $result['regions'] = $regions;
    $result['color'] = [
      '30976400480' => '#ffff81',
      '78898343747' => '#62d2c5',
      '205321542553' => '#fc8b8b',
      '291077377916' => '#aa6ca6',
      '545147568136' => '#37ce04',
      '794270165344' => '#c7cb8f',
      '954752120491' => '#01bee7',
      '1086763915908' => '#fece2c'
    ];
    return [
      'data' => [
        'success' => true,
        'data' => $result,
        'message' => 'Color data loaded',
      ],
      'code' => 1,
    ];

  }

}