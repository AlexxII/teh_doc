<?php

use yii\helpers\Html;

$this->title = 'Обновить данные';
$this->params['breadcrumbs'][] = ['label' => 'Тех.документация', 'url' => ['/tehdoc']];
$this->params['breadcrumbs'][] = ['label' => 'Комплекты', 'url' => ['/tehdoc/equipment/complex']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="complex-update">

  <h3><?= Html::encode($this->title) ?></h3>

  <?= $this->render('_form', [
    'modelComplex' => $model,
    'fupload' => $fupload
  ]) ?>

</div>
