<?php

use yii\helpers\Html;

$this->title = 'Графики ТО';
$this->params['breadcrumbs'][] = ['label' => 'Тех.документация', 'url' => ['/tehdoc']];
$this->params['breadcrumbs'][] = ['label' => 'ТО', 'url' => ['/tehdoc/to']];
$this->params['breadcrumbs'][] = $this->title;

$about = "Графики технического обслуживания.";

require 'to_array.php';

?>

<style>
  td {
    text-align: center;
  }
  td .fa {
    font-size: 25px;
  }
  #main-table tbody td {
    font-size: 12px;
  }
  #main-table tr {
    font-size: 12px;
  }
</style>


<div class="tool-task">
  <h3><?= Html::encode('Графики ТО') ?>
    <sup class="h-title fa fa-question-circle-o" aria-hidden="true"
         data-toggle="tooltip" data-placement="bottom" title="<?php echo $about ?>"></sup>
  </h3>

  <div class="row">
    <div class="w3-col l8">
      <div class="container-fluid" style="margin-bottom: 20px">
        <?= Html::a('Новый график', ['create'], ['class' => 'btn btn-success btn-sm', 'style' => ['margin-top' => '5px']]) ?>
      </div>
    </div>

    <div class="w3-row">
      <div class="w3-col l8 m7" id="edit-block" style="display:none">
        <button type="button" id="table-editor" class="btn btn-primary btn-sm">Редактировать</button>
        <button type="button" id="table-del" class="btn btn-danger btn-sm">Удалить</button>
        <P></P>
      </div>
    </div>

    <div class="container-fluid">
      <table id="main-table" class="display no-wrap cell-border" style="width:100%">
        <thead>
        <tr>
          <th data-priority="1">№ п.п.</th>
          <th ></th>
          <th data-priority="1">Месяц</th>
          <th data-priority="7">Отметки</th>
          <th>Год</th>
          <th>Объем ТО</th>
          <th>Ответственный за проведение</th>
          <th>Ответственный за контроль</th>
          <th data-priority="2">DO_it</th>
          <th data-priority="3">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($tos) : ?>
          <?php foreach ($tos as $to) : ?>
            <tr>
              <td></td>
              <td> <?= $to['plan_date']?> </td>
              <td> <?= strftime("%B", strtotime($to['plan_date'])) ?> </td>
              <td>
                <?php
                if (strlen($to['checkmark']) == 1) {
                  if ($to['checkmark'] == 1) {
                    echo '<span style="color:#4CAF50"><strong>ТО проведено</strong></span>';
                  } else {
                    if (date('Y-m-d') < $to['plan_date']) {
                      echo '<span style="color:#4349cc"><strong>ТО не проведено</strong></span>';
                    } else {
                      echo '<span style="color:#CC0000"><strong>ТО не проведено</strong></span>';
                    }
                  }
                } else {
                  echo '<span style="color:#4349cc"><strong>Проведено не полностью</strong></span>';
                }
                ?>
              </td>
              <td> <?= strftime("%G", strtotime($to['plan_date'])) . ' год' ?> </td>
              <td> <?= $to['to_type']?> </td>
              <td> <?= $to['admins'] ?> </td>
              <td> <?= $to['auditors'] ?> </td>
              <td style="text-align: center">
                <?= Html::a('', ["perform?id=" . $to['schedule_id']], [
                  'class' => 'fa fa-check-square-o',
                  'title' => 'Отметить выполнение графика',
                  'data-toggle' => 'tooltip',
                  'data-placement' => 'top'
                ]); ?>
              </td>
              <td style="text-align: center">
                <?= Html::a('', ["view?id=" . $to['schedule_id']], [
                  'class' => 'fa fa-eye',
                  'title' => 'Просмотр',
                  'data-toggle' => 'tooltip',
                  'data-placement' => 'top'
                ]); ?>
                <?= Html::a('', ['delete', 'id' => $to['schedule_id']], [
                  'class' => 'fa fa-trash',
                  'title' => 'Удалить весь график',
                  'data-toggle' => 'tooltip',
                  'data-placement' => 'top',
                  'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить объект?',
                    'method' => 'post',
                  ]]); ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  $(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
  });

  $(document).ready(function () {
    var table = $('#main-table').DataTable({
      "columnDefs": [
        {"visible": false, "targets": 1},
        {"visible": false, "targets": 4},
      ],
      orderFixed: [[4, 'desc']],
      order: [[1, 'desc']],
      rowGroup: {
        dataSrc: 4
      },
      select: false,
      responsive: true,
      fixedHeader: {
        header: true,
        headerOffset: $('#topnav').height()
      },
      language: {
        url: "/lib/ru.json"
      }
    });
    table.on('order.dt search.dt', function () {
      table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
        cell.innerHTML = i + 1;
      });
    }).draw();
  });

</script>