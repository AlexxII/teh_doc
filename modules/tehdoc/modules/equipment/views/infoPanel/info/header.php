<?php

use yii\helpers\Html;

$this->title = 'Перечень оборудования';
$this->params['breadcrumbs'][] = ['label' => 'Тех.документация', 'url' => ['/tehdoc']];
$this->params['breadcrumbs'][] = $this->title;

?>

<style>
  td {
    text-align: center;
  }
  .h-title {
    font-size: 18px;
    color: #1e6887;
  }
  .d-flex {
    display: flex !important;
  }
  .flex-items-center {
    align-items: center !important;
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
  table.detail-view th {
    width: 25%;
  }
  table.detail-view td {
    width: 75%;
  }
</style>

<ul class="nav nav-tabs" id="main-teh-tab">
  <li class="active">
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
  <li>
    <a href="../wiki/index" style="cursor: pointer">
      Wiki
      <span class="Counter"><?= $wikiCount ?></span>
    </a>
  </li>
</ul>

<div class="complex-info-view">

  <div class="row d-flex flex-items-center">
    <div class="col-lg-11 col-md-11 col-xs-11">
      <h2>
        <span style="position: relative">
        <?= Html::encode($model->name);
        if ($model->specialStatus || $model->specialChildrenStatus ) {
          echo '
          <i class="fa fa-shield" aria-hidden="true" style="font-size: 14px;position: absolute;top:-5px;right:-10px"
             title="Проведены Специальные работы"></i>';
        }
        ?>
        </span>
      </h2>
    </div>
    <div class="text-right" style="padding: 7px 15px 0 0">
      <a type="button" href="/tehdoc/equipment/control-panel/<?= $model->id ?>/info/index"
         class="btn-success btn-sm">Control</a>
    </div>
  </div>

  <?= $this->render($view, [
    'model' => $model,
    'children' => $children
  ]) ?>

</div>
