<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = '';

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
    <div class="col-lg-12 col-md-12" style="text-align: center">
      <i class="fa fa-book" aria-hidden="true" style="font-size: 28px"></i>
      <h3>Добро пожаловать в Wiki</h3>
      <p>Добавляйте любую текстовую информацию о данном оборудовании.</p>
      <p><a id="new-wiki-page" class="btn btn-sm btn-success">Создать первую страницу</a></p>
    </div>
  </div>
</div>

<script>
  $(document).ready(function () {
    $('#new-wiki-page').on('click', function () {
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
  })
</script>


