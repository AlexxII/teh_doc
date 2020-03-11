<?php


use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\AirDatepickerAsset;

AirDatepickerAsset::register($this);
?>

<style>
  .datepicker {
    z-index: 999999999;
  }
  .form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
    background-color: #fff;
    opacity: 1;
  }
</style>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => '']]); ?>

<div class="dynamic-content">
  <div class="col-md-12 col-lg-12"
       style="border: dashed 1px #0c0c0c; border-radius: 4px; padding: 10px 0px 10px 0px; margin-bottom: 10px; position: relative">
      <span class="text-muted dynamic-control" style="position: absolute;top:5px;right:10px;font-size: 10px">
        <i class="fa fa-plus-square-o"
           id="add"
           aria-hidden="true"
           style="cursor: pointer; font-size: 25px; color: #4eb305"
           title="Добавить часть отпуска">
        </i></span>
    <span class="text-muted dynamic-title" style="position: absolute;top:5px;left:10px;font-size: 10px"></span>

    <div class="dynamic-wrap">
      <div class="form-group col-md-11 col-lg-11" style="margin-bottom: 0px">
      <div class="form-group">
        <?= $form->field($model, 'date')->textInput(['class' => 'date form-control',
          'data-datepicker' => 'datepicker',
          'onclick' => 'calendarShow(this)',
          'readonly' => true
        ])
          ->hint(' ', ['class' => ' w3-label-under']); ?>
      </div>
      <input name="VksSessions[vks_type_text]" id="vks_type_text" style="display: none">
    </div>
  </div>
</div>

<?php ActiveForm::end(); ?>

<script>
  $(document).ready(function () {

    var mines = '' +
      '      <span class="text-muted " style="position: absolute;top:5px;right:10px;font-size: 10px">\n' +
      '        <i class="fa fa-minus-square-o del"\n' +
      '           aria-hidden="true"\n' +
      '           style="cursor: pointer; font-size: 25px; color: #c72e26"\n' +
      '           title="Удалить часть">\n' +
      '        </i></span>\n';

    var newDiv = '' +
      '        <div class="form-group col-md-11 col-lg-11  dynamic" style="margin-top: 10px">' +
      '          <label class="control-label">Часть отпуска:</label>' +
      '        </div>';

    $('.dynamic-content').on('click', '.del', function () {
      var div = $(this).closest("div");
      mainCounter--;
      if (mainCounter == 1) {
        $(parentSpanTitle).text('');
      }
      div.remove();
    });


    var mainCounter = 1;
    var parentSpanTitle;
    var lastInsert;
    var maxInput = 10;
    $('#add').on('click', function () {
      if (mainCounter >= maxInput) {
        return;
      }
      var div = $(this).closest("div");
      // parentSpanTitle = $(div).find('.dynamic-title').text(mainCounter+'-ая часть');

      var insert = $(div).clone();
      insert.find('.dynamic-wrap').html(newDiv);
      // var spanControl = $(insert).find('.dynamic-title').text(mainCounter + 1 +'-ая часть');
      var spanTitle = $(insert).find('.dynamic-control').replaceWith(mines);
      // переименовать контейнер
      $(insert).find('.dynamic').addClass("dynamic-container-" + mainCounter);
      insert.find('.control-label').attr('for', 'new-type-' + mainCounter);
      // клонировать selectы, переименовать их id, name  и всавить в клонированный div после label
      div.find('.date').clone()
        .attr('name', 'holiday[' + mainCounter + ']')
        .attr('id', 'absence-date-' + mainCounter)
        .insertAfter(insert.find('.control-label')).val('');

      $(insert).tooltip();
      if (mainCounter == 1) {
        insert.insertAfter(div);
      } else {
        insert.insertAfter(lastInsert);
      }
      lastInsert = insert;
      $('#w0').yiiActiveForm('add', {
        id: 'new-type-' + mainCounter,
        name: 'test-type[' + mainCounter + ']',
        container: '.dynamic-container-' + mainCounter,
        input: '#new-type-' + mainCounter,
        validate: function (attribute, value, messages, deferred, $form) {
          yii.validation.required(value, messages, {message: "Validation Message Here"});
        }
      });
      mainCounter++;
    })


  });
</script>