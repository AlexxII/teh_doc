<?php

namespace app\modules\study;

class StudyModule extends \yii\base\Module
{
//  public $layout = 'study_layout.php';

  public $layout = '@app/views/layouts/main.php';

  public function init()
  {
    \Yii::$app->view->title = 'Полигон';
    parent::init();
  }

}