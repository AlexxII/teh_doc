<?php

use yii\helpers\Html;

$this->title = 'Панель управления';
$this->params['breadcrumbs'][] = ['label' => 'Тех.документация', 'url' => ['/tehdoc']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="wrap-settings">

  <h3><?= Html::encode($tool->eq_title) ?></h3>

  <div class="subhead" style="padding-top: 0px">
    <h3 class="setting-header" style="padding-top: 0px">Отображение</h3>
  </div>
  <ul class="list-group">

    <li class="list-group-item">
      <div class="form-checkbox js-complex-option">
        <label style="font-weight: 500">
          <input class="ch" type="checkbox"
                 data-id="<?= $tool->ref ?>"
                 data-check='wrap-check' data-url='wrap' <?php if ($tool->wrap) echo 'checked' ?>>
          Обертка
        </label>
        <span class="status-indicator" id="wrap-check"></span>
        <p class="note" style="margin-bottom: 10px">Отображать данный объект как обертку вокруг других объектов.</p>
      </div>
    </li>
  </ul>


</div>


<script>

  $(document).ready(function () {
    var successCheck = '<i class="fa fa-check" id="consolidated-check" aria-hidden="true" style="color: #4eb305"></i>';
    var warningCheck = '<i class="fa fa-times" id="consolidated-check" aria-hidden="true" style="color: #cc0000"></i>';
    var infoCheck = '<i class="fa fa-exclamation" id="consolidated-check" aria-hidden="true" style="color: #cc0000"></i>';
    var waiting = '<i class="fa fa-cog fa-spin" aria-hidden="true"></i>';
    var csrf = $('meta[name=csrf-token]').attr("content");

    $('.ch').change(function (e) {
      var checkId = $(this).data('check');
      $('#' + checkId).html(waiting);
      var url = $(this).data('url');
      var nodeId = $(this).data('id');
      var result = $(this).is(':checked');
      $.ajax({
        url: url,
        type: "post",
        dataType: "JSON",
        data: {
          _csrf: csrf,
          toolId: nodeId,
          bool: result
        },
        success: function (data) {
          $('#' + checkId).html(successCheck);
        },
        error: function (data) {
          $('#' + checkId).html(warningCheck);
        }
      });
    });
  })
</script>