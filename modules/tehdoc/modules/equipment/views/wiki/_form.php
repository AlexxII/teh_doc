<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$placeholder = 'Название страницы';

?>

<style>
  .al {
    border: #c72e26 solid 1px;
  }
</style>

<div class="row">
  <div class="col-lg-12 col-md-12" style="border-radius:2px;padding-top:10px">
    <div class="customer-form">
      <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'main-wiki-form']]); ?>
      <div class="row">
        <div class="col-md-12 col-lg-12">
          <?= $form->field($model, 'wiki_title')
            ->textInput(['placeholder' => $placeholder, 'id' => 'wiki-title'])
            ->label(false) ?>
        </div>
        <div class="col-md-12 col-lg-12">
          <?= $form->field($model, 'wiki_text')
            ->textArea(array('style' => 'resize:vertical', 'rows' => '10', 'id' => 'wiki-text'))
            ->label(false) ?>
        </div>
      </div>
      <div class="form-group" style="text-align: right">
        <a id="cancel-wiki-edit" class="btn btn-sm btn-primary">Отмена</a>
        <?php
        if ($model->isNewRecord) {
          echo '<a id="create-wiki" class="btn btn-sm btn-success">Создать</a>';
        } else {
          echo '<a id="update-wiki" data-wiki="'. $model->id . '" class="btn btn-sm btn-success">Обновить</a>';
        }
        ?>
      </div>
      <?php ActiveForm::end(); ?>
    </div>
  </div>
</div>


<script>
  var simplemde;
  $(document).ready(function () {
    simplemde = new SimpleMDE({
      showIcons: ["code", "table"],
      status: false,
      spellChecker: false,
      toolbar: [
        "bold", "italic", "heading", "|",
        "code", "quote", "unordered-list", "ordered-list", "|",
        "link", "image", "table", "|",
        "preview", "fullscreen", "|",
        {
        name: "guide",
        action: function openlink() {
          var win = window.open('https://google.com', '_blank');
          win.focus();
        },
        className: "fa fa-question-circle",
        title: "Справка",
      }]
    });

    $('#cancel-wiki-edit').on('click', function () {
      var csrf = $('meta[name=csrf-token]').attr("content");
      var url = '/tehdoc/equipment/wiki/index';
      $.ajax({
        url: url,
        type: "post",
        data: {
          id : getNodeId(),
          _csrf: csrf
        }
      })
        .done(function (result) {
          $('.about-content').html(result);
        })
        .fail(function () {
          alert("Что-то пошло не так. Перезагрузите форму с помошью клавиши.");
        });
    });

    $('#wiki-title').on('input', function () {
      if ($(this).val){
        $('#wiki-title').removeClass('al');
      }
    });

    $('#create-wiki').on('click', function () {
      var wikiTitle = $('#wiki-title').val();
      if (wikiTitle == ''){
        $('#wiki-title').toggleClass('al');
        return;
      }
      var csrf = $('meta[name=csrf-token]').attr("content");
      var url = '/tehdoc/equipment/wiki/create';
      var wikiText = simplemde.value();
      $.ajax({
        url: url,
        type: "post",
        data: {
          _csrf: csrf,
          id : getNodeId(),
          wikiTitle: wikiTitle,
          wikiText: wikiText
        }
      })
        .done(function (result) {
          $('.about-content').html(result);
        })
        .fail(function () {
          alert("Что-то пошло не так. Перезагрузите форму с помошью клавиши.");
        });
    });

    $('#update-wiki').on('click', function () {
      var wikiTitle = $('#wiki-title').val();
      if (wikiTitle == ''){
        $('#wiki-title').toggleClass('al');
        return;
      }
      var csrf = $('meta[name=csrf-token]').attr("content");
      var url = '/tehdoc/equipment/wiki/update?id=' . getWikiId();
      var wikiText = simplemde.value();
      $.ajax({
        url: url,
        type: "post",
        data: {
          _csrf: csrf,
          id : getNodeId(),
          wikiTitle: wikiTitle,
          wikiText: wikiText
        }
      })
        .done(function (result) {
          $('.about-content').html(result);
        })
        .fail(function () {
          alert("Что-то пошло не так. Перезагрузите форму с помошью клавиши.");
        });
    });

    function getNodeId() {
      var node = $("#fancyree_w0").fancytree("getActiveNode");
      if (node) {
        return node.data.ref;
      } else {
        return -1;
      }
    }

    function getWikiId() {
      return $('#update-wiki').data('wiki');
    }

    // sessionStorage.setItem("previousPage","");

  })


</script>