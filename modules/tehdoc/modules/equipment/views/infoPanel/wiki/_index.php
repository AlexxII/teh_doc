<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Перечень оборудования';
$this->params['breadcrumbs'][] = ['label' => 'Тех.документация', 'url' => ['/tehdoc']];
$this->params['breadcrumbs'][] = $this->title;

?>

<style>
  .panel-body {
    padding: 5px;
  }
  .d-block {
    text-decoration: #0b3e6f;
  }
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
  <li>
    <a href="../foto/index">
      Photo
      <span class="Counter"><?= $imagesCount ?></span>
    </a>
  </li>
  <li class="active">
    <a href="../wiki/index" style="cursor: pointer">
      Wiki
      <span class="Counter"><?= $wikiCount ?></span>
    </a>
  </li>
</ul>

<div class="complex-wiki-default" style="margin-top: 15px">
  <div class="row">
    <div class="col-lg-12 col-md-12" style="text-align: center">
      <i class="fa fa-book" aria-hidden="true" style="font-size: 28px"></i>
      <h3>Добро пожаловать в Wiki</h3>
      <p>Добавляйте любую текстовую информацию о данном оборудовании.</p>
      <p><a href="create" id="new-wiki-page" class="btn btn-sm btn-success">Создать первую страницу</a></p>
    </div>
  </div>
</div>

