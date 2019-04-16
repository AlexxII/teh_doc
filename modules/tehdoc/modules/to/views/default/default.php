<?php

$this->title = 'ТО';
$this->params['breadcrumbs'][] = ['label' => 'Тех.документация', 'url' => ['/tehdoc']];
$this->params['breadcrumbs'][] = $this->title;

$about = "ВКС";

?>

<div class="col-lg-4 col-md-6 about" data-url="/tehdoc/to/schedule" style="text-align:center; cursor: pointer">
  <div class="row" id="header">
    <h2>График</h2>
  </div>
  <div class="" id="main">
    <i class="fa fa-calendar-o" aria-hidden="true" style="font-size: 150px"></i>
  </div>
  <div class="row" id="footer">
    <h4>График ТО на текущий месяц</h4>
  </div>
</div>


<div class="col-lg-4 col-md-6 about" data-url="/tehdoc/to/schedule/create" style="text-align: center; cursor: pointer">
  <div class="row" id="header">
    <h2>Добавить график</h2>
  </div>
  <div class="" id="main">
    <i class="fa fa-calendar-plus-o" aria-hidden="true" style="font-size: 150px"></i>
  </div>
  <div class="row" id="footer">
    <h4>Добавить график ТО на месяц</h4>
  </div>
</div>


<div class="col-lg-4 col-md-6 about" data-url="/tehdoc/to/schedule/archive" style="text-align: center; cursor: pointer">
  <div class="row" id="header">
    <h2>Архив графиков</h2>
  </div>
  <div class="" id="main">
    <i class="fa fa-calendar" aria-hidden="true" style="font-size: 150px"></i>
  </div>
  <div class="row" id="footer">
    <h4>Архив графиков ТО на месяц</h4>
  </div>
</div>


<div class="col-lg-4 col-md-6 about" data-url="#" style="text-align: center; cursor: pointer">
  <div class="row" id="header">
    <h2>Добавить на год</h2>
  </div>
  <div class="" id="main">
    <i class="fa fa-calendar-plus-o" aria-hidden="true" style="font-size: 150px"></i>
  </div>
  <div class="row" id="footer">
    <h4>Добавить годовой график ТО</h4>
  </div>
</div>

<div class="col-lg-4 col-md-6 about" data-url="#" style="text-align: center; cursor: pointer">
  <div class="row" id="header">
    <h2>Архив графиков</h2>
  </div>
  <div class="" id="main">
    <i class="fa fa-calendar" aria-hidden="true" style="font-size: 150px"></i>
  </div>
  <div class="row" id="footer">
    <h4>Архив годовых графиков ТО</h4>
  </div>
</div>


<div class="col-lg-4 col-md-6 about" data-url="#" style="text-align: center; cursor: pointer">
  <div class="row" id="header">
    <h2>Наработка</h2>
  </div>
  <div class="" id="main">
    <i class="fa fa-calculator" aria-hidden="true" style="font-size: 150px"></i>
  </div>
  <div class="row" id="footer">
    <h4>Учет наработки оборудования</h4>
  </div>
</div>



<script>
  $(".about").on('click', function (e) {
    var url = $(this).data('url');
    location.href = url;
  })
</script>