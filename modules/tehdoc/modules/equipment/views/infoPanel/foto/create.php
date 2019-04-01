<?php

use yii\helpers\Html;

$this->title = 'Перечень оборудования';
$this->params['breadcrumbs'][] = ['label' => 'Тех.документация', 'url' => ['/tehdoc']];
$this->params['breadcrumbs'][] = $this->title;

?>

<style>
  .Counter {
    background-color: rgba(27, 31, 35, .08);
    border-radius: 20px;
    color: #586069;
    display: inline-block;
    font-size: 12px;
    font-weight: 600;
    line-height: 1;
    padding: 2px 5px;
    font-family: BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif,
    Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol;
  }
</style>


<ul class="nav nav-tabs" id="main-teh-tab">
  <li>
    <a href="../info/index">
      Инфо
    </a>
  </li>
  <li>
    <a href="../docs/index" style="cursor: pointer">
      Docs
      <span class="Counter"><?= $docsCount ?></span>
    </a>
  </li>
  <li class="active">
    <a href="../foto/index">
      Photo
      <span class="Counter"><?= $imagesCount ?></span>
    </a>
  </li>
  <li>
    <a href="../wiki/index" style="cursor: pointer">
      Wiki
      <span class="Counter"><?= $wikiCount ?></span>
    </a>
  </li>
</ul>

<div class="complex-wiki-create">
  <h3><?= Html::encode('Добавить изображение') ?></h3>
  <?= $this->render('_form', [
    'model' => $model
  ]) ?>

</div>
