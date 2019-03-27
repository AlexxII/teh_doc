<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$placeholder = 'Название страницы';

?>

<div id="complex-wiki-form" class="row">
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
        <a id="delete-page" data-id="<?= $model->id ?>" class="btn btn-sm btn-danger">Удалить</a>
        <a onclick="history.back();" class="btn btn-sm btn-primary">Отмена</a>
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => 'btn btn-sm btn-success']) ?>
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

    $('#delete-page').on('click', function (e) {
      e.preventDefault();
      jc = $.confirm({
        icon: 'fa fa-question',
        title: 'Вы уверены?',
        content: 'Вы действительно хотите удалить страницу?',
        type: 'red',
        closeIcon: false,
        autoClose: 'cancel|9000',
        buttons: {
          ok: {
            btnClass: 'btn-danger',
            action: function () {
              var wikiId = $('#delete-page').data('id');
              jc.close();
              window.location.href = 'delete-page?id=' + wikiId;
            }
          },
          cancel: {
            action: function () {
              return;
            }
          }
        }
      });
    })
  });

</script>