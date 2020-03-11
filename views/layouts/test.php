<?php

use yii\helpers\Html;
use yii\bootstrap\BootstrapPluginAsset;
use app\assets\AppAsset;
use app\assets\SlidebarsAsset;
use app\assets\JConfirmAsset;

AppAsset::register($this);    // регистрация ресурсов всего приложения
JConfirmAsset::register($this);
//SlidebarsAsset::register($this);
BootstrapPluginAsset::register($this);

$this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
  <meta charset="<?= Yii::$app->charset ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?= Html::csrfMetaTags() ?>
  <title><?= Html::encode($this->title) ?></title>
  <?php $this->head() ?>

</head>

<style>
  #main-content {
    width: 100%;
  }
</style>
<?php $this->beginBody() ?>

<?= $content ?>

<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
