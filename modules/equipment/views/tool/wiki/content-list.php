<?php

use yii\helpers\Markdown;
use yii\helpers\Html;

?>

<div class="complex-wiki-update">
  <div class="row">
    <div class="col-lg-9 col-md-6">
      <h3 id="wiki-title"><?= Html::encode($model->wiki_title) ?></h3>
    </div>
    <div class="col-lg-3 col-md-6 text-right">
      <p>
        <a href="" id="update-wikipage" data-id="<?= $model->id ?>" class="btn btn-sm btn-primary">Edit</a>
        <a href="" id="new-wiki-page"  data-tree="tools-main-tree" class="btn btn-sm btn-success">New</a>
      </p>
    </div>
  </div>

  <div class="complex-wiki">
    <div class="wiki-content">
      <div id="wiki-body" class="col-lg-9 col-md-9">
        <?= Markdown::process($model->wiki_text, 'extra') ?>
      </div>
      <div id="wiki-rightbar" class="col-lg-3 col-md-3 text-right">
        <div class="panel panel-default">
          <div class="panel-heading">
            Страницы
          </div>
          <ul class="list-group" id="wiki-pages">
            <?php
            foreach ($list as $item) {
              echo '<li class="list-group-item show-wiki" data-wiki-id="' . $item["id"] . '">' . $item["wiki_title"] . '</li>';
            }
            ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<script>

</script>