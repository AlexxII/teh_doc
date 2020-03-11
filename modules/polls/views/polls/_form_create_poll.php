<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\modules\vks\assets\VksFormAsset;
use app\assets\BootstrapDatepickerAsset;

BootstrapDatepickerAsset::register($this);

$poll_title_hint = "Укажите наименование опроса без кода.";
$poll_sample_hint = "Укажите выборку цифрами.";
$poll_code_hint = "Укажите код данного опроса";
$poll_election_hint = "Является ли данный опрос - выборным";

?>
<div class="form-create-poll">
  <div class="col-lg-12 col-md-12" style="border-radius:2px;padding-top:10px">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => '']]); ?>

    <?php if($create) : ?>
      <div class="row" style="padding-bottom: 10px">
        <div class="form-group col-md-12 col-lg-12">
          <?= $form->field($xml, 'xmlFile')->fileInput([
            'id' => 'xmlupload'
          ]) ?>
        </div>
      </div>
<!--      <div class="row" style="padding-bottom: 10px">
        <div class="form-group col-md-12 col-lg-12">
          <?/*= $form->field($xml, 'xmlFile')->fileInput([
            'id' => 'xmlupload'
          ]) */?>
        </div>
      </div>
-->    <?php else: ?>
    <div class="row" style="padding-bottom:10px; top: -20px">
      <span class="text-muted" style="font-size: 10px">
        Чтобы обновить структуру опроса, выделите его в основной таблице и выбирите клавишу "Редактор анкет"
      </span>
    </div>
    <?php endif; ?>

    <div class="row">
      <div class="form-group col-md-12 col-lg-12">
        <?= $form->field($model, 'title', [
          'template' => '{label}{input}'])
          ->textInput([
            'class' => 'poll-title form-control'
          ])->hint('', ['class' => ' w3-label-under']); ?>
      </div>
    </div>

    <div class="col-md-12 col-lg-12"
         style="border: dashed 1px #0c0c0c;border-radius: 4px;padding: 20px 0px 10px 0px;margin: 10px 0px 10px 0px;position: relative">
      <span class="text-muted"
            style="position: absolute;top:5px;right:10px;font-size: 10px">Время проведения опроса</span>
      <div class="col-md-6 col-lg-6">
        <?= $form->field($model, 'start_date', [
          'template' => '{label}{input}'])
          ->textInput([
            'class' => 'start-date pool-dates form-control',
            'readonly' => true
          ])->hint('', ['class' => ' w3-label-under']); ?>
      </div>
      <div class="col-md-6 col-lg-6">
        <?= $form->field($model, 'end_date', [
          'template' => '{label}{input}'])
          ->textInput([
            'class' => 'end-date pool-dates form-control',
            'readonly' => true
          ])->hint('', ['class' => ' w3-label-under']); ?>
      </div>
    </div>

    <div class="col-md-12 col-lg-12"
         style="border: dashed 1px #0c0c0c;border-radius: 4px;padding: 20px 0px 10px 0px;margin: 10px 0px 10px 0px;position: relative">
      <span class="text-muted" style="position: absolute;top:5px;right:10px;font-size: 10px">Особенности</span>
      <div class="form-group col-md-5 col-lg-5">
        <?= $form->field($model, 'code', [
          'template' => '{label} <sup class="fa fa-info-circle nonreq" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $poll_code_hint . '"></sup>{input}{hint}'])
          ->textInput(['id' => 'poll-code'])->hint('', ['class' => ' w3-label-under']); ?>
      </div>
      <div class="form-group col-md-7 col-lg-7">
        <?= $form->field($model, 'sample', [
          'template' => '{label} <sup class="fa fa-info-circle nonreq" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $poll_sample_hint . '"></sup>{input}{hint}'])
          ->textInput(['id' => 'poll-sample'])->hint('', ['class' => ' w3-label-under']); ?>
      </div>
    </div>

    <div class="row" style="padding-top: 10px">
      <div class="form-group col-md-12 col-lg-12">
        <?php
        $template = [
          'labelOptions' => ['font-size' => '28px'],
          'template' => '{label}{input} <sup class="fa fa-info-circle nonreq" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $poll_election_hint . '"></sup>',
        ];
        echo $form->field($model, 'elections', $template)->checkbox(); ?>
      </div>
    </div>

    <div class="row">
      <div class="form-group col-md-12 col-lg-12">
        <?= $form->field($model, 'poll_comments')->textArea(array('style' => 'resize:vertical', 'rows' => '2')) ?>
      </div>
    </div>

  </div>
</div>

<?php ActiveForm::end(); ?>

<script>

  var xmlData = '';

  $(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    $('.pool-dates').datepicker({
      format: 'd MM yyyy г.',
      autoclose: true,
      language: "ru",
      startView: "days",
      minViewMode: "days",
      clearBtn: true,
      todayHighlight: true,
      daysOfWeekHighlighted: [0, 6]
    });

    if ($('.start-date').val()) {
      moment.locale('ru');
      var date = new Date($('.start-date').val());
      $('.start-date').datepicker('update', moment(date).format('D MM YYYY'));
    }

    if ($('.end-date').val()) {
      moment.locale('ru');
      var date = new Date($('.end-date').val());
      $('.end-date').datepicker('update', moment(date).format('D MM YYYY'));
    }

    $('#xmlupload').on('change', function (e) {
      // e.preventDefault();
      var txt = '';
      var selectedFile = document.getElementById('xmlupload').files[0];
      var reader = new FileReader();
      moment.locale('ru');

      reader.onload = function (e) {
        xmlData = e.target.result;
        var parser = new DOMParser();
        var doc = parser.parseFromString(xmlData, "application/xml");
        var opros = doc.getElementsByTagName("opros")[0].attributes;
        $('#polls-title').val(opros.name.nodeValue);
        $('#poll-code').val(opros.cod.nodeValue);
        var startDate = opros.start_date.nodeValue;
        var endDate = opros.end_date.nodeValue;
        var pattern = /(\d{2})\.(\d{2})\.(\d{4})/;
        $('.start-date').datepicker('update', new Date(startDate.replace(pattern, '$3-$2-$1')));
        $('.end-date').datepicker('update', new Date(endDate.replace(pattern, '$3-$2-$1')));
      };
      reader.readAsText(selectedFile);
    })


  });


</script>

