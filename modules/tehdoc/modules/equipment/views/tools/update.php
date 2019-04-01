<?php

use yii\helpers\Html;

$this->title = 'Обновить';
$this->params['breadcrumbs'][] = ['label' => 'Тех.документация', 'url' => ['/tehdoc']];
$this->params['breadcrumbs'][] = ['label' => 'Перечень оборудования', 'url' => ['/tehdoc/equipment/tools']];
$this->params['breadcrumbs'][] = ['label' => 'Задание на обновление', 'url' => ['/tehdoc/equipment/tools/task']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="tool-create">

  <h3><?= Html::encode($this->title)?></h3>
  <?= '<small>' . $model->toolParents(1) . $model->eq_title . '</small>';
  echo $this->render('_form', [
    'model' => $model,
    'fUpload' => $fupload
  ]) ?>

</div>
