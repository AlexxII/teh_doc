<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Markdown;

//$this->title = $model->name;
$this->title = $model->wiki_title;

?>

<style>
  .panel-body {
    padding: 5px;
  }
  .d-block {
    text-decoration: #0b3e6f;
  }
</style>

<div class="info-view">

  <div class="row">
    <div class="col-lg-9 col-md-6">
      <h3 style="margin-top: 0px"><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="col-lg-3 col-md-6 text-right">
      <p>
        <a id="edit-wiki-page" data-wiki="<?php echo $model->id ?>" class="btn btn-sm btn-primary">Edit</a>
        <a id="new-wiki-page" class="btn btn-sm btn-success">New</a>
      </p>
    </div>
  </div>

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
          <li class="list-group-item">
            <a class="" href="#">Home</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function () {
    $('#edit-wiki-page').on('click', function () {
      var url = '/tehdoc/equipment/wiki/update?id=';
      $.ajax({
        url: url + getWikiId(),
        type: "get",
      })
        .done(function (result) {
          $('.about-content').html(result);
        })
        .fail(function () {
          alert("Что-то пошло не так. Перезагрузите форму с помошью клавиши.");
        });
    });

    $('#new-wiki-page').on('click', function () {
      var csrf = $('meta[name=csrf-token]').attr("content");
      var url = '/tehdoc/equipment/wiki/create';
      $.ajax({
        url: url,
        type: "get",
      })
        .done(function (result) {
          $('.about-content').html(result);
        })
        .fail(function () {
          alert("Что-то пошло не так. Перезагрузите форму с помошью клавиши.");
        });
    });


    function getWikiId() {
      return $('#edit-wiki-page').data('wiki');
    }
  })
</script>


