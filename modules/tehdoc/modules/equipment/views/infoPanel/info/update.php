<?php

use yii\helpers\Html;

$this->title = 'Обновить';
$this->params['breadcrumbs'][] = ['label' => 'Тех.документация', 'url' => ['/tehdoc']];
$this->params['breadcrumbs'][] = ['label' => 'Перечень оборудования', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="tool-update">

  <h3><?= Html::encode($this->title) ?></h3>

  <?= $this->render('_form', [
    'model' => $model,
    'fUpload' => $fupload,
  ]) ?>

</div>
