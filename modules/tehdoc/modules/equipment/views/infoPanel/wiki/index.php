<?php

use yii\helpers\Markdown;

?>

<style>
  .panel-body {
    padding: 5px;
  }
  .d-block {
    text-decoration: #0b3e6f;
  }
</style>

<div class="complex-wiki">
  <div class="wiki-content">
    <div id="wiki-body" class="col-lg-9 col-md-9" style="padding-left:0px">
      <?= Markdown::process($model->wiki_text, 'extra') ?>
    </div>
    <div id="wiki-rightbar" class="col-lg-3 col-md-3 text-right" style="padding: 0px">
      <div class="panel panel-default">
        <div class="panel-heading" style="text-align: left">
          Страницы
        </div>
        <ul class="list-group">
          <?php
          foreach ($list as $item) {
            echo '<li class="list-group-item"><a href="view?page='. $item["id"] . '">' . $item["wiki_title"] . '</a></li>';
          }
          ?>
        </ul>
      </div>
    </div>
  </div>
</div>