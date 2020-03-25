<?php

namespace app\modules\polls\asset;

use yii\web\AssetBundle;

class PollAsset extends AssetBundle
{
  public $sourcePath = '@app/modules/polls/lib';

  public $js = [
    'js/vars.js',
    'js/settings/towns.js',

    'js/common/poll.js',
    'js/common/question.js',
    'js/common/answer.js',

    'js/drive/user.class.js',
    'js/drive/drive.js',
    'js/drive/worksheet.class.js',
    'js/drive/respondent.class.js',
    'js/drive/danswer.class.js',
    'js/drive/dquestion.class.js',
    'js/default.js',

    'js/jquery.maskedinput.js',

    'js/control/control.js',
    'js/control/info/info.js',
    'js/control/construct/pollconstructor.class.js',
    'js/control/construct/cquestion.class.js',
    'js/control/construct/canswer.class.js',
    'js/control/construct/construct.js',

    'js/control/batch/batch.class.js',
    'js/control/batch/banswer.class.js',
    'js/control/batch/bquestion.class.js',
    'js/control/batch/batch.js',

    'js/control/parcha/parcha.js',
    'js/control/results/results.js'

  ];

  public $css = [
    'css/drivein.css',
    'css/poll_style.css',
    'css/construct.css',
    'css/batch.css',
    'css/analytics.css',
    'css/parcha.css',
    'css/towns.css',
    'css/results.css'
  ];

  public $depends = [
  ];

}
