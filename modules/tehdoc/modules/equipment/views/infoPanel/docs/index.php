<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\file\FileInput;

$title_hint = 'Укажите точное наименование документа.';
$date_hint = 'Дата документа.';

?>
<style>
  .date {
    color: #8f8f8f;
    font-size: 12px;
  }
  .news-item {
    font-family: "PTF75F-webfont";
    font-size: 18px;
    margin: 20px 0px;
  }
  .news-item > p > span, .news-info__name, .news-info__name * {
    background-image: url("/lib/border-bot.jpg");
    background-position: 0 95%;
    background-repeat: repeat-x;
    background-size: 1px 1px;
    display: inline;
  }
  .news-item > a:hover .news-info__name {
    background-image: none;
    color: #9d4d71;
  }
  .info-file .format-file {
    font-family: "PTF75F-webfont";
    font-size: 14px;
    margin: 5px 0;
    padding: 1px 13px 1px 4px;
    position: relative;
  }
  .news-info > a > div:hover .format-file, .news-info p:hover .format-file {
    background: #676767;
    color: #ffffff !important;
  }
  .doc-format {
    color: #0b58a2
  }
  .doc-format:hover {
    background-color: #468fc7;
    color: #fff;
  }
  .news-info > a > div:hover .doc-format, .news-info p:hover .doc-format {
    background: #468fc7;
  }
  .news-info > a > div:hover .format-file, .news-info p:hover .format-file {
    background: #676767;
    color: #ffffff !important;
  }
  .down {
    color: #fff;
    font-size: 10px;
  }
  .hide {
    display: none
  }
  .calendar ul li.active {
    background-color: #f9edc1;
  }
</style>


<div id="complex-docs">
  <?php if ($years): ?>
    <div class="calendar">
      <ul class="list-inline" style="border-top: 1px solid #cbcbcb;">
        <?php foreach ($years as $year): ?>
          <li class="<?php if ($year == $selectYear)echo 'active' ?>">
            <a href="?year=<?= $year ?>"><?= $year ?></a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php foreach ($docModels as $docModel): ?>
    <div style="margin-left: 20px" id="">
      <div>
        <input class="doc-select" type="checkbox" style="float: left; margin: 7px 0px 0px -20px"
               data-docid="<?= $docModel->id ?>">
      </div>
      <div class="news-item">
        <a href="<?= $docModel->docUrl ?>" style="text-align: justify">
          <div>
            <div class="news-info__name">
              <?= $docModel->doc_title ?>
            </div>
            <span class="info-file">
          <span class="format-file doc-format">
            <?= $docModel->doc_extention ?>
            <i class="fa fa-download down" aria-hidden="true"></i>
          </span>
          <span class="weight-file"></span>
        </span>
          </div>
        </a>
        <div>
          <span class="date"><?= $docModel->uploadDate . ' (' . $docModel->docDate . ')' ?> </span>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>


<script>

  var jc;

  $(document).ready(function () {

    $('.fact-date').datepicker({
      format: 'dd MM yyyy г.',
      autoclose: true,
      language: "ru",
      clearBtn: true
    });

    if ($('.fact-date').val()) {
      var date = new Date($('.fact-date').val());
      moment.locale('ru');
      $('.fact-date').datepicker('update', moment(date).format('MMMM YYYY'))
    }

    $('.doc-select').change(function (e) {
      if ($('input:checkbox:checked').length) {
        $('#delete-doc').attr('disabled', false);
      } else {
        $('#delete-doc').attr('disabled', true);
      }
    });

    $('#delete-doc').on('click', function (event) {
      event.preventDefault();
      if ($(this).attr('disabled')) {
        return;
      }
      jc = $.confirm({
        icon: 'fa fa-question',
        title: 'Вы уверены?',
        content: 'Вы действительно хотите удалить выделенное?',
        type: 'red',
        closeIcon: false,
        autoClose: 'cancel|9000',
        buttons: {
          ok: {
            btnClass: 'btn-danger',
            action: function () {
              jc.close();
              deleteProcess();
            }
          },
          cancel: {
            action: function () {
              return;
            }
          }
        }
      });
    });
  });

  function deleteProcess() {
    var csrf = $('meta[name=csrf-token]').attr("content");
    var uri = window.location.href;
    var selected = [];
    $('.doc-select:checked').each(function () {
      selected.push($(this).data('docid'));
    });
    jc = $.confirm({
      icon: 'fa fa-cog fa-spin',
      title: 'Подождите!',
      content: 'Ваш запрос выполняется!',
      buttons: false,
      closeIcon: false,
      confirmButtonClass: 'hide'
    });
    $.ajax({
      url: 'delete-docs',
      method: 'post',
      data: {
        docsArray: selected,
        _csrf: csrf
      }
    }).done(function (response) {
      if (response != false) {
        jc.close();
        var count = $('li.active span.Counter').text();
        $('li.active span.Counter').text(count - response);
        jc = $.confirm({
          icon: 'fa fa-thumbs-up',
          title: 'Успех!',
          content: 'Ваш запрос выполнен.',
          type: 'green',
          buttons: false,
          closeIcon: false,
          autoClose: 'ok|8000',
          confirmButtonClass: 'hide',
          buttons: {
            ok: {
              btnClass: 'btn-success',
              action: function () {
                window.location.href = uri;
              }
            }
          }
        });
      } else {
        jc.close();
        jc = $.confirm({
          icon: 'fa fa-exclamation-triangle',
          title: 'Неудача!',
          content: 'Запрос не выполнен. Что-то пошло не так.',
          type: 'red',
          buttons: false,
          closeIcon: false,
          autoClose: 'ok|8000',
          confirmButtonClass: 'hide',
          buttons: {
            ok: {
              btnClass: 'btn-danger',
              action: function () {
              }
            }
          }
        });
      }
    }).fail(function () {
      jc.close();
      jc = $.confirm({
        icon: 'fa fa-exclamation-triangle',
        title: 'Неудача!',
        content: 'Запрос не выполнен. Что-то пошло не так.',
        type: 'red',
        buttons: false,
        closeIcon: false,
        autoClose: 'ok|4000',
        confirmButtonClass: 'hide',
        buttons: {
          ok: {
            btnClass: 'btn-danger',
            action: function () {
            }
          }
        }
      });
    });
  }

</script>