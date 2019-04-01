<?php

use app\modules\tehdoc\modules\equipment\asset\MasonryAsset;

MasonryAsset::register($this);

?>

<style>
  .date {
    color: #8f8f8f;
    font-size: 10px;
  }
</style>

<div>
  <div class="dw" style="margin-top: 10px">
    <?php foreach ($photoModels as $photoModel): ?>
      <div class="dw-panel">
        <div class="thumbnail dw-panel__content">
          <div>
            <input class="doc-select" type="checkbox" data-docid="<?= $photoModel->id ?>">
          </div>
          <a href="<?= $photoModel->imageUrl ?>">
            <img src="<?= $photoModel->imageUrl ?>">
          </a>
          <span class="date">Добавлено: <?= $photoModel->uploadDate ?> </span>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>


<script>
  $(document).ready(function () {
    $('.doc-select').change(function (e) {
      if ($('input:checkbox:checked').length) {
        $('#delete-doc').attr('disabled', false);
      } else {
        $('#delete-doc').attr('disabled', true);
      }
    });

    var jc;

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
      url: 'delete-photos',
      method: 'post',
      data: {
        photosArray: selected,
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
