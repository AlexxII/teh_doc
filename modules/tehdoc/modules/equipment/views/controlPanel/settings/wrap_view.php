<?php

use yii\helpers\Html;

$this->title = 'Панель управления';
$this->params['breadcrumbs'][] = ['label' => 'Тех.документация', 'url' => ['/tehdoc']];
$this->params['breadcrumbs'][] = $this->title;

?>

<style>
  .d-flex {
    display: flex !important;
  }
  .flex-items-center {
    align-items: center !important;
  }
</style>

<div class="wrap-settings">

  <div class="row d-flex flex-items-center">
    <div class="col-lg-11 col-md-11 col-xs-11">
      <h3 style="margin-top: 0px"><?= Html::encode($tool->eq_title) ?></h3>
    </div>
    <div class="text-right" style="padding: 0 15px 0 5px">
      <a type="button" href="/tehdoc/equipment/tool/<?= $tool->id?>/info/wrap"
         class="btn-primary btn-sm">Info</a>
    </div>
  </div>

  <ul class="list-group">

    <li class="list-group-item">
      <div class="form-checkbox js-complex-option">
        <label style="font-weight: 500">
          <input class="ch" type="checkbox"
                 data-id="<?= $tool->id ?>"
                 data-check='wrap-check' data-url='wrap' <?php if ($tool->wrap) echo 'checked' ?>>
          Обертка
        </label>
        <span class="status-indicator" id="wrap-check"></span>
        <p class="note" style="margin-bottom: 10px">Отображать данный объект как обертку вокруг других объектов.
          Добавлять информацию в данный объект нальзя.</p>
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
            jc = $.confirm({
                icon: 'fa fa-thumbs-up',
                title: 'Успех!',
                content: 'C данного узела снята пометка - обертка. Страница перезагрузится!',
                type: 'green',
                buttons: false,
                closeIcon: false,
                autoClose: 'ok|8000',
                confirmButtonClass: 'hide',
                buttons: {
                    ok: {
                        btnClass: 'btn-success',
                        action: function () {
                            window.location.href = 'index';
                        }
                    }
                }
            });
        },
        error: function (data) {
          $('#' + checkId).html(warningCheck);
        }
      });
    });
  })
</script>