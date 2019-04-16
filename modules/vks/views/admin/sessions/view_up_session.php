<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\tehdoc\asset;
use yii\widgets\DetailView;


$this->title = 'Просмотр удаленного сеанса';

$this->params['breadcrumbs'][] = ['label' => 'ВКС', 'url' => ['/vks']];
$this->params['breadcrumbs'][] = ['label' => 'Журнал', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="to-schedule-archive">

  <h3><?= Html::encode($this->title) ?></h3>

</div>

<style>
  td {
    text-align: center;
  }
  .h-title {
    font-size: 18px;
    color: #1e6887;
  }
  table.detail-view th {
    width: 40%;
  }

</style>

<div class="row">
  <div class="">
    <div class="container-fluid " style="margin-bottom: 20px">
      <?= Html::a('Удалить', ['delete-single-completely', 'id' => $model->id], [
        'class' => 'btn btn-danger btn-sm', 'id' => 'delete', 'data-id' => $model->id]) ?>
    </div>
  </div>

  <div class="container-fluid col-lg-6 col-md-6">
    <?= DetailView::widget([
      'model' => $model,
      'attributes' => [
        [
          'label' => 'Дата проведения ВКС:',
          'value' => date('d.m.Y', strtotime($model->vks_date)) . ' г.'
        ],
        'vks_teh_time_start',
        'vks_work_time_start',
        [
          'label' => 'Тип сеанса ВКС:',
          'value' => $model->type
        ],
        [
          'label' => 'Студия проведения ВКС:',
          'value' => $model->place
        ],
        [
          'label' => 'Абонент:',
          'value' => $model->subscriber
        ],
        [
          'label' => 'Распоряжение:',
          'value' => $model->order
        ],
        [
          'label' => 'Принявший сообщение:',
          'value' => $model->vks_employee_receive_msg
        ],
        [
          'label' => 'Дата сообщения:',
          'value' => date('d.m.Y', strtotime($model->vks_receive_msg_datetime)) . ' г.'
        ],
        [
          'label' => 'Передавший сообщение:',
          'value' => $model->sendMsg
        ],
        'vks_comments',
        'vks_record_create',
        'vks_record_update'
      ]
    ]) ?>
  </div>

  <div class="vks-log col-lg-6 col-md-6">
    <?php
    foreach ($logs as $log) {
      if ($log->status == 'danger') {
        $info = 'alert-danger';
      } else {
        $info = 'alert-info';
      }
      echo '<div class="alert ' . $info . '" role="alert" style="margin-bottom: 10px">';
      echo '<div class="clock" style="font-size: 12px">';
      echo '<i class="fa fa-clock-o" aria-hidden="true"></i>';
      echo ' ';
      echo date('d.m.Y', strtotime($log->log_time)) . ' ' . date('H:i.s', strtotime($log->log_time));
      echo '</div>';
      echo " Пользователь: " . '<strong>' . $log->userName . '</strong>';
      echo '<br>';
      echo $log->log_text;
      echo '</div>';
    }
    ?>
  </div>

</div>
<br>

<script>
  $(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();

    $('#delete').click(function (event) {
      event.preventDefault();
      var url = "/vks/admin/sessions/delete-single-completely";
      var id = $(this).data('id');
      jc = $.confirm({
        icon: 'fa fa-question',
        title: 'Вы уверены?',
        content: 'Вы действительно хотите удалить запись безвозвратно?',
        type: 'red',
        closeIcon: false,
        autoClose: 'cancel|9000',
        buttons: {
          ok: {
            btnClass: 'btn-danger',
            action: function () {
              jc.close();
              remoteProcess(url, id);
            }
          },
          cancel: {
            action: function () {
              return;
            }
          }
        }
      })
    });

    function remoteProcess(url, id) {
      var csrf = $('meta[name=csrf-token]').attr("content");
      jc = $.confirm({
        icon: 'fa fa-cog fa-spin',
        title: 'Подождите!',
        content: 'Ваш запрос выполняется!',
        buttons: false,
        closeIcon: false,
        confirmButtonClass: 'hide'
      });
      $.ajax({
        url: url,
        method: 'post',
        data: {id: id, _csrf: csrf},
      }).done(function (response) {
        if (response != false) {
          jc.close();
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
                  location.href = '/vks/admin/sessions/';
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


  });
</script>


