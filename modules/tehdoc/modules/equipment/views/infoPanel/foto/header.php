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
    <a href="../docs/index">
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


<div class="complex-fotos" style="margin-top: 15px">

  <div class="row">
    <div class="col-lg-9 col-md-6">
      <h3 style="margin-top: 0px">
        <?= Html::encode('Изображения') ?>
      </h3>
    </div>
    <div class="col-lg-3 col-md-6 text-right">
      <p>
        <a href="#" class="btn btn-sm btn-danger" id="delete-doc" disabled="true">Удалить</a>
        <a href="create" class="btn btn-sm btn-success">Добавить</a>
      </p>
    </div>
  </div>

  <?= $this->render('index', [
    'photoModels' => $photoModels
  ]) ?>

</div>
