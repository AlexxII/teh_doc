<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Отметить выполнение';
$this->params['breadcrumbs'][] = ['label' => 'Тех.документация', 'url' => ['/tehdoc']];
$this->params['breadcrumbs'][] = ['label' => 'ТО', 'url' => ['/tehdoc/to']];
$this->params['breadcrumbs'][] = ['label' => 'Графики ТО', 'url' => ['/tehdoc/to/schedule/archive']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="to-index">

  <div class="to-create">

    <?= $this->render('_per_form_ex', [
      'tos' => $tos,
      'header' => 'Отметить выполнение графика ТО на ',
      'month' => $month,
    ]) ?>

  </div>

</div>