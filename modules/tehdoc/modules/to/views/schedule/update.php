<?php

use yii\helpers\Html;

$this->title = 'Добавить график ТО';
$this->params['breadcrumbs'][] = ['label' => 'Тех.документация', 'url' => ['/tehdoc']];
$this->params['breadcrumbs'][] = ['label' => 'Графики ТО', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$about = "График технического обслуживания.";
require "to_array.php";

?>
<div class="to-update">

  <?= $this->render('_form', [
    'tos' => $tos,
    'header' => 'Обновить график ТО на'
  ]) ?>



</div>