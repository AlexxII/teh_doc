<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\modules\vks\assets\VksFormAsset;
use app\assets\BootstrapDatepickerAsset;

\yii\widgets\MaskedInputAsset::register($this);
VksFormAsset::register($this);
BootstrapDatepickerAsset::register($this);

$vks_date_hint = 'Обязательное поле! Укажите дату проведения сеанса ВКС';
$vks_type_hint = 'Обязательное поле! Укажите ТИП сеанса ВКС (Напрмер: ЗВС-ОГВ, КВС и т.д.)';
$vks_place_hint = 'Обязательное поле! Укажите место проведения сеанса видеосвязи';
$vks_subscr_hint = 'Вводите фамилию абонента, при совпадении предложенного варианты выберите его. Ведомство определится автоматически';
$vks_subscrof_hint = 'Обязательное поле! Укажите  должность старшего абонента';
$vks_order_hint = 'Обязательное поле! Укажите распоряжение на проведение сеанса ВКС';
$vks_employee_hint = 'Обязательное поле! Укажите сотрудника СпецСвязи, обеспечивающего сеанс ВКС';
$vks_subscr_reg_hint = 'Вводите фамилию абонента, при совпадении предложенного варианты выберите его. Ведомство определится автоматически';
$vks_subscr_regof_hint = 'Обязательное поле! Укажите должность абонента в регионе';
$vks_tools_hint = 'Обязательное поле! Укажите оборудование ВКС'

?>

<div class="form-confirm-session">
  <div class="col-lg-12 col-md-12" style="border-radius:2px;padding-top:10px">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => '']]); ?>

    <div class="row">
      <div class="col-md-12 col-lg-9" style="padding: 0px">
        <div class="col-md-6 col-lg-6">
          <?= $form->field($model, 'vks_date', [
            'template' => '{label} <sup class="fa fa-info-circle" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" data-container="body" title="' . $vks_date_hint . '"></sup>{input}{hint}'
          ])->textInput([
            'class' => 'fact-date form-control',
            'readonly' => true
          ])->hint('', ['class' => ' w3-label-under']); ?>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="form-group col-md-12 col-lg-9" style="padding: 0px">
        <div class="form-group">
          <div class="form-group col-md-6 col-lg-6">
            <?= $form->field($model, 'vks_teh_time_start')->textInput([
              'class' => 'time-mask form-control right',
              'id' => 'teh-start'
            ])->hint('', ['class' => ' w3-label-under']); ?>
          </div>
          <div class="form-group col-md-6 col-lg-6">
            <?= $form->field($model, 'vks_teh_time_end')->textInput([
              'class' => 'time-mask form-control',
              'id' => 'teh-end'
            ])->hint('', ['class' => ' w3-label-under']); ?>
          </div>
          <input name="VksSessions[vks_duration_teh]" id="vks-duration-teh" style="display: none">
        </div>
      </div>
    </div>

    <div class="row">
      <div class="form-group col-md-12 col-lg-9" style="padding: 0px">
        <div class="form-group">
          <div class="form-group col-md-6 col-lg-6">
            <?= $form->field($model, 'vks_work_time_start')->textInput([
              'class' => 'time-mask form-control right',
              'id' => 'work-start'
            ])->hint('', ['class' => ' w3-label-under']); ?>
          </div>
          <div class="form-group col-md-6 col-lg-6">
            <?= $form->field($model, 'vks_work_time_end')->textInput([
              'class' => 'time-mask form-control',
              'id' => 'work-end'
            ])->hint('', ['class' => ' w3-label-under']); ?>
          </div>
          <input name="VksSessions[vks_duration_work]" id="vks-duration-work" style="display: none">
        </div>
      </div>
    </div>

    <div class="row">
      <div class="form-group col-md-5 col-lg-5">
        <?php
        echo $form->field($model, 'vks_type', [
          'template' => '{label} <sup class="fa fa-info-circle" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $vks_type_hint . '"></sup>{input}{hint}'
        ])->dropDownList($model->vksTypesList, ['id' => 'vks-type', 'data-name' => 'vks_type',
          'prompt' => ['text' => 'Выберите',
            'options' => [
              'value' => 'none',
              'disabled' => 'true',
              'selected' => 'true'
            ]]])->hint('', ['class' => ' w3-label-under']);
        ?>
        <input name="VksSessions[vks_type_text]" id="vks_type_text" style="display: none">
      </div>

      <div class="form-group col-md-7 col-lg-7">
        <?= $form->field($model, 'vks_place', [
          'template' => '{label} <sup class="fa fa-info-circle req" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $vks_place_hint . '"></sup>{input}{hint}'
        ])->dropDownList($model->vksPlacesList, ['id' => 'vks-place', 'data-name' => 'vks_place',
          'prompt' => ['text' => 'Выберите',
            'options' => [
              'value' => 'none',
              'disabled' => 'true',
              'selected' => 'true'
            ]]])->hint('', ['class' => ' w3-label-under']);
        ?>
        <input name="VksSessions[vks_place_text]" id="vks_place_text" style="display: none">
      </div>
    </div>

    <div class="col-md-12 col-lg-12"
         style="border: dashed 1px #0c0c0c;border-radius: 4px;padding: 20px 0px 10px 0px;margin: 10px 0px 10px 0px;position: relative">
      <span class="text-muted" style="position: absolute;top:5px;right:10px;font-size: 10px">Старший абонент</span>
      <div class="form-group col-md-5 col-lg-5">
        <?= $form->field($model, 'vks_subscriber_name', [
          'template' => '{label} <sup class="fa fa-info-circle nonreq" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $vks_subscr_hint . '"></sup>{input}{hint}'])
          ->textInput(['id' => 'subscriber-name'])->hint('', ['class' => ' w3-label-under']); ?>
      </div>
      <div class="form-group col-md-7 col-lg-7">
        <?= $form->field($model, 'vks_subscriber_office', [
          'template' => '{label} <sup class="fa fa-info-circle req" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $vks_subscrof_hint . '"></sup>{input}{hint}'])
          ->dropDownList($model->vksMskSubscribesList, ['id' => 'subscriber-office', 'data-name' => 'vks_subscriber_office',
            'prompt' => ['text' => 'Выберите',
              'options' => [
                'value' => 'none',
                'disabled' => 'true',
                'selected' => 'true'
              ]]])->hint('', ['class' => ' w3-label-under']);
        ?>
        <input name="VksSessions[vks_subscriber_office_text]" id="vks_subscriber_office_text"
               style="display: none">
      </div>
    </div>

    <div class="row">
      <div class="form-group col-md-5 col-lg-5">
        <?= $form->field($model, 'vks_order', [
          'template' => '{label} <sup class="fa fa-info-circle req" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $vks_order_hint . '"></sup>{input}{hint}'])
          ->dropDownList($model->vksOrdersList, ['data-name' => 'vks_order',
            'prompt' => ['text' => 'Выберите',
              'options' => [
                'value' => 'none',
                'disabled' => 'true',
                'selected' => 'true'
              ]]])->hint('', ['class' => ' w3-label-under']);
        ?>
        <input name="VksSessions[vks_order_text]" id="vks_order_text" style="display: none">
      </div>

      <div class="form-group col-md-7 col-lg-7">
        <?= $form->field($model, 'vks_employee', [
          'template' => '{label} <sup class="fa fa-info-circle req" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $vks_employee_hint . '"></sup>{input}{hint}'])
          ->dropDownList($model->vksEmployees4List, ['id' => 'employee', 'data-name' => 'vks_employee',
            'prompt' => ['text' => 'Выберите',
              'options' => [
                'value' => 'none',
                'disabled' => 'true',
                'selected' => 'true'
              ]]])->hint('', ['class' => ' w3-label-under']);
        ?>
        <input name="VksSessions[vks_employee_text]" id="vks_employee_text" style="display: none">
      </div>
    </div>

    <div class="col-md-12 col-lg-12"
         style="border: dashed 1px #0c0c0c;border-radius: 4px;padding: 20px 0px 10px 0px;margin: 10px 0px 10px 0px;position: relative">
      <span class="text-muted" style="position: absolute;top:5px;right:10px;font-size: 10px">Абонент субъекта</span>
      <div class="form-group col-md-5 col-lg-5">
        <?= $form->field($model, 'vks_subscriber_reg_name', [
          'template' => '{label} <sup class="fa fa-info-circle nonreq" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $vks_subscr_reg_hint . '"></sup>{input}{hint}'
        ])->textInput(['id' => 'subscriber-reg-name'])->hint('', ['class' => ' w3-label-under']); ?>
      </div>
      <div class="form-group col-md-7 col-lg-7">
        <?= $form->field($model, 'vks_subscriber_reg_office', [
          'template' => '{label} <sup class="fa fa-info-circle req" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $vks_subscr_regof_hint . '"></sup>{input}{hint}'
        ])->dropDownList($model->vksRegionSubscribesList, ['id' => 'subscriber-reg-office', 'data-name' => 'vks_subscriber_reg_office',
          'prompt' => ['text' => 'Выберите',
            'options' => [
              'value' => 'none',
              'disabled' => 'true',
              'selected' => 'true'
            ]]])->hint('', ['class' => ' w3-label-under']);
        ?>
        <input name="VksSessions[vks_subscriber_reg_office_text]" id="vks_subscriber_reg_office_text"
               style="display: none">

      </div>
    </div>

    <div class="row">
      <div class="form-group col-md-12 col-lg-12">
        <?= $form->field($model, 'vks_equipment', [
          'template' => '{label} <sup class="fa fa-info-circle req" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $vks_tools_hint . '"></sup>{input}{hint}'
        ])->dropDownList($model->vksToolsList, ['data-name' => 'vks_equipment',
          'prompt' => ['text' => 'Выберите',
            'options' => [
              'value' => 'none',
              'disabled' => 'true',
              'selected' => 'true'
            ]]])->hint('', ['class' => ' w3-label-under']);
        ?>
        <input name="VksSessions[vks_equipment_text]" id="vks_equipment_text"
               style="display: none">
      </div>
    </div>

    <div class="row">
      <div class="form-group col-md-12 col-lg-12">
        <?php
        $template = [
          'labelOptions' => ['font-size' => '28px'],
          'template' => '{label}{input}   ',
        ];
        echo $form->field($model, 'vks_remarks', $template)->checkbox(); ?>
      </div>
    </div>

    <div class="row">

      <div class="form-group col-md-12 col-lg-12">
        <?= $form->field($model, 'vks_comments')->textArea(array('style' => 'resize:vertical', 'rows' => '2')) ?>
      </div>
    </div>

  </div>
</div>

<?php ActiveForm::end(); ?>

<script>
  $(document).ready(function () {
    $(function () {
      $.mask.definitions['H'] = '[012]';
      $.mask.definitions['M'] = '[012345]';
      $('.time-mask').mask('H9:M9', {
          placeholder: "_",
          completed: function () {
            var val = $(this).val().split(':');
            if (val[0] * 1 > 23) val[0] = '23';
            if (val[1] * 1 > 59) val[1] = '59';
            $(this).val(val.join(':'));
            $(this).next('.time-mask').focus();
            var attr = $(this).attr('id');
            if (attr == 'teh-start' || attr == 'teh-end') {
              if ($('#teh-start').val() != '' && $('#teh-end').val() != '') {
                var tehStart = (moment($('#teh-start').val(), 'HH:mm'));
                var tehEnd = (moment($('#teh-end').val(), 'HH:mm'));
                var duration = moment.duration(tehEnd.diff(tehStart)).asMinutes();
                var label = $(this).parent().find('label');
                if (duration > 0) {
                  $(this).css('border-color', 'lightgrey');
                  $(label).css('color', '#333');
                  $('#vks-duration-teh').val(duration);
                } else {
                  $(this).css('border-color', '#c72e26');
                  $(label).css('color', '#a94442');
                  $('#vks-duration-teh').val('');
                }
              }
            } else if (attr == 'work-start' || attr == 'work-end') {
              if ($('#work-start').val() != '' && $('#work-end').val() != '') {
                var workStart = (moment($('#work-start').val(), 'HH:mm'));
                var workEnd = (moment($('#work-end').val(), 'HH:mm'));
                var duration = moment.duration(workEnd.diff(workStart)).asMinutes();
                var label = $(this).parent().find('label');
                if (duration > 0) {
                  $(this).css('border-color', 'lightgrey');
                  $(label).css('color', '#333');
                  $('#vks-duration-work').val(duration);
                } else {
                  $(this).css('border-color', '#c72e26');
                  $(label).css('color', '#a94442');
                  $('#vks-duration-work').val('');
                }
              }
            }
          }
        }
      );
    });

    $('[data-toggle="tooltip"]').tooltip();

    $('#w1-tree-input-menu').on('change', function (e) {
      var text = $('#w1-tree-input').text();
      $('#equipment-eq_title').val(text);
    });

    $('.fact-date').datepicker({
      format: 'd MM yyyy г.',
      autoclose: true,
      language: "ru",
      startView: "days",
      minViewMode: "days",
      clearBtn: true,
      todayHighlight: true,
      daysOfWeekHighlighted: [0, 6]
    });

    // дублирование времени поля окончания тех.сеанса
    if ($('#teh-end').val() === '') {
      var workStart = (moment($('#work-start').val(), 'HH:mm'));
      var tehStart = (moment($('#teh-start').val(), 'HH:mm'));
      var duration = moment.duration(workStart.diff(tehStart)).asMinutes();
      if (duration > 0) {
        var d = $('#work-start').val();
        $('#teh-end').val(d);
        $('#vks-duration-teh').val(duration);
      }
    }

    if ($('.fact-date').val()) {
      var date = new Date($('.fact-date').val());
      moment.locale('ru');
      $('.fact-date').datepicker('update', moment(date).format('D MM YYYY'));
    }

    $.ajax({
      type: 'get',
      url: '/vks/sessions/subscribers-region',
      autoFocus: true,
      success: function (data) {
        var mskNames = $.parseJSON(data);
        $(function () {
          $("#subscriber-reg-name").autocomplete({
            source: mskNames,
            focus: function (event, ui) {
              event.preventDefault();
              $(this).val(ui.item.label);
            },
            select: function (event, ui) {
              event.preventDefault();
              $(this).val(ui.item.label);
              $('#subscriber-reg-office').val(ui.item.value);
              var text = $('#subscriber-reg-office').find("option:selected").text();
              $('#vks_subscriber_reg_office_text').val(text);              // т.к. событие всавки, а не изменения
            }
          });
        });
      },
      error: function (data) {
        console.log('error occure');
      }
    });

    $.ajax({
      type: 'get',
      url: '/vks/sessions/subscribers-msk',
      autoFocus: true,
      success: function (data) {
        var mskNames = $.parseJSON(data);
        $(function () {
          $("#subscriber-name").autocomplete({
            source: mskNames,
            focus: function (event, ui) {
              event.preventDefault();
              $(this).val(ui.item.label);
            },
            select: function (event, ui) {
              event.preventDefault();
              $(this).val(ui.item.label);
              $('#subscriber-office').val(ui.item.value);
              var text = $('#subscriber-office').find("option:selected").text();
              $('#vks_subscriber_office_text').val(text);              // т.к. событие всавки, а не изменения
            }
          });
        });
      },
      error: function (data) {
        console.log('error occure');
      }
    });

    $('select').each(function () {
      var val = $(this).find("option:selected").text();
      if (val != 'Выберите') {
        var text = '_text';
        var id = '#' + $(this).data("name") + text;
        $(id).val(val);
      }
    });
    $('select').on('change', function (e) {
      var val = $(this).find("option:selected").text();
      var text = '_text';
      var id = '#' + $(this).data("name") + text;
      $(id).val(val);
    });
  });


</script>

