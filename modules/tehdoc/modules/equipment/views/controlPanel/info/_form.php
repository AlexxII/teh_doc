<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use app\modules\tehdoc\models\Equipment;
use app\modules\tehdoc\asset\TehFormAsset;

?>

<style>
  .nonreq {
    color: #1e6887;
  }
  .select-selected {
    padding-left: 40px;
  }
</style>

<?php
TehFormAsset::register($this);

// текст к подсказкам
$class_hint = 'Выберите класс оборудования.';
$cat_hint = 'Необходима для классификации оборудования.';
$title_hint = 'Необходимо для отображения в таблице.';
$serial_hint = 'Укажите серийный номер (s/n), на некоторых моделях оборудования указывается только производственный номер (p/n), 
                  тогда укажите его.';
$invent_hint = 'Укажите инвентарный номер.';
$oTime_hint = 'Если известно, укажите наработку в часах на день занесения.';
$place_hint = 'Укажите точное размещение оборудования.';
$date_hint = 'Если не известен месяц, выберите январь известного года.';
$quantity_hint = 'Внимание! Указывайте отличную от 1 цифру 
ТОЛЬКО для идентичного оборудования и расходных материалов. Например: офисная бумага, батарейки. 
Будьте ВНИМАТЕЛЬНЫ, не вводите себя в заблуждение.';

?>

<div class="row">
  <div class="col-lg-12 col-md-12" style="border-radius:2px;padding-top:10px">
    <div class="customer-form">
      <?php $form = ActiveForm::begin([
        'options' => [
          'enctype' => 'multipart/form-data'],
        'action' => 'update'
      ]); ?>
      <div class="row">
        <div class="col-md-6 col-lg-6">
          <?php
          echo $form->field($model, 'category_id', [
            'template' => '{label} <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $cat_hint . '"></sup>{input}{hint}'])
            ->dropDownList($model->toolCategoryList, ['data-name' => 'vks_type', 'prompt' => ['text' => 'Выберите',
              'options' => [
                'value' => 'none',
                'disabled' => 'true',
                'selected' => 'true'
              ]]])->hint('Выберите категорию', ['class' => ' w3-label-under']);
          ?>
        </div>
        <div class="col-md-6 col-lg-6">
          <?= $form->field($model, 'eq_title', [
            'template' => '{label} <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $title_hint . '"></sup>{input}{hint}'])
            ->textInput()->hint('Например: Коммутатор с автоопределителем', ['class' => ' w3-label-under']); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 col-lg-6">
          <?= $form->field($model, 'eq_manufact')->textInput(['id' => 'manufact'])
            ->hint('Например: HP, ACER', ['class' => ' w3-label-under']); ?>
        </div>
        <div class="col-md-6 col-lg-6">
          <?= $form->field($model, 'eq_model')->textInput(['id' => 'models'])
            ->hint('Например: LJ 1022', ['class' => ' w3-label-under']); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 col-lg-6">
          <?= $form->field($model, 'eq_serial', [
            'template' => '{label} <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $serial_hint . '"></sup>{input}{hint}'
          ])->textInput()->hint('Например: HRUEO139UI92', ['class' => ' w3-label-under']); ?>
        </div>
        <div class="col-md-6 col-lg-6">
          <?= $form->field($model, 'invent_number', [
            'template' => '{label} <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $invent_hint . '"></sup>{input}{hint}'
          ])->textInput()->hint('Например: 20205147', ['class' => ' w3-label-under']); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 col-lg-6">
          <?= $form->field($model, 'eq_factdate', [
            'template' => '{label} <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $date_hint . '"></sup>{input}{hint}'
          ])->textInput([
            'class' => 'fact-date form-control'
          ])->hint('Введите или выберите дату', ['class' => ' w3-label-under']); ?>
        </div>
        <div class="form-group col-md-6 col-lg-6">
          <?= $form->field($model, 'eq_operating_time', [
            'template' => '{label} <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $oTime_hint . '"></sup>{input}{hint}'
          ])->textInput()->hint('Например: 124948', ['class' => ' w3-label-under']); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8">
          <?php
          echo $form->field($model, 'place_id', [
            'template' => '{label} <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $place_hint . '"></sup>{input}{hint}'
          ])->dropDownList($model->toolPlacesList, ['data-name' => 'vks_type', 'prompt' => ['text' => 'Выберите',
            'options' => [
              'value' => 'none',
              'disabled' => 'true',
              'selected' => 'true'
            ]]])->hint('Выберите место нахождения оборудования', ['class' => ' w3-label-under']);
          ?>
        </div>
        <div class="col-md-4">
          <?= $form->field($model, 'quantity', [
            'template' => '{label} <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $quantity_hint . '"></sup>{input}{hint}'])
            ->textInput()->hint('Введите количество', ['class' => ' w3-label-under']); ?>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 col-lg-12">
          <?= $form->field($model, 'eq_comments')->textArea(array('style' => 'resize:vertical', 'rows' => '2')) ?>
        </div>
      </div>

      <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => 'btn btn-primary']) ?>
      </div>
    </div>
  </div>
</div>

<?php ActiveForm::end(); ?>

<script>


  $(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();

    // $('#tools-category_id').attr('disabled', 'disabled');

/*
    $('#tools-category_id').on('change', function (e) {
      var text = $("#tools-category_id option:selected").text();
      $('#tools-eq_title').val(text);
    });
*/

    $('#tools-eq_title').on('input', function (e) {
      var node = $("#fancyree_w0").fancytree("getActiveNode");
      node.setTitle($(this).val());
      node.render();
    })

  });


  $(document).ready(function () {
    $('.fact-date').datepicker({
      format: 'MM yyyy г.',
      autoclose: true,
      language: "ru",
      startView: "months",
      minViewMode: "months",
      clearBtn: true
    })
  });

  $(document).ready(function () {
    if ($('.fact-date').val()) {
      var date = new Date($('.fact-date').val());
      moment.locale('ru');
      $('.fact-date').datepicker('update', moment(date).format('MMMM YYYY'))
    }
  });

  //преобразование дат перед отправкой
  $(document).ready(function () {
    $('#w0').submit(function () {
      var d = $('.fact-date').data('datepicker').getFormattedDate('yyyy-mm-dd');
      $('.fact-date').val(d);
    });
  });

  function contains(arr, elem) {
    for (var i = 0; i < arr.length; i++) {
      if (arr[i] === elem) {
        return true;
      }
    }
    return false;
  }

  // функционал улучшения интерфейса формы

  $(document).ready(function () {
    $.ajax({
      type: 'get',
      url: '/tehdoc/settings/interface/manufact',
      autoFocus: true,
      success: function (data) {
        var manufact = $.parseJSON(data);
        $(function () {
          $("#manufact").autocomplete({
            source: manufact,
          });
        });
      },
      error: function (data) {
        console.log('Error loading Manufact list.');
      }
    });
  });

  $(document).ready(function () {
    $.ajax({
      type: 'get',
      url: '/tehdoc/settings/interface/models',
      autoFocus: true,
      success: function (data) {
        var models = $.parseJSON(data);
        $(function () {
          $("#models").autocomplete({
            source: models,
          });
        });
      },
      error: function (data) {
        console.log('Error loading Models list');
      }
    });
  });


/*
  $('#tools-eq_class').on('change',function(){
    var classId = $(this).val();
    if(classId){
      $.ajax({
        type:'POST',
        url:'../control/category-list',
        data:'classId='+classId,
        success:function(html){
          $('#tools-category_id').html(html);
          $('#tools-category_id').removeAttr('disabled');
          // $('#city').html('<option value="">Select state first</option>');
        }
      });
    }else{
      $('#state').html('<option value="">Select country first</option>');
      $('#city').html('<option value="">Select state first</option>');
    }
  });
*/


  /*
    $(document).ready(function () {
      var variable = [];
      var cats, leaves, del = [];
      $.ajax("/admin/category/get-leaves")
        .done(function (data) {
          data = jQuery.parseJSON(data);
          cats = data.cat;
          leaves = data.leaves;
          for (var i = 0; i < cats.length; i++) {
            variable[i] = cats[i].id;
          }
          for (var i = 0; i < leaves.length; i++) {
            del[i] = leaves[i].id;
          }
          variable.forEach(function (t) {
            if (contains(del, t)) {
              return;
            }
            var element = $("select option[value='" + t + "']");
            $("select option[value='" + t + "']").attr('disabled', true);
            $("select option[value='" + t + "']").css({
              "background-color": '#e8e8e8',
              "font-weight": 700
            });
          });
        })
        .fail(function () {
          alert("Произошла ошибка в выводе категорий.");
        })
        .always(function () {
          alert("complete");
        });

    });
  */

</script>

