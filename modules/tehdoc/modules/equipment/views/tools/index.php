<?php

use yii\helpers\Html;

$this->title = 'Таблица с оборудованием';
$this->params['breadcrumbs'][] = ['label' => 'Тех.документация', 'url' => ['/tehdoc']];
$this->params['breadcrumbs'][] = ['label' => 'Перечень оборудования', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="tool-create">

  <h3><?= Html::encode($this->title) ?></h3>

</div>
