<?php

use app\modules\maps\asset\LeafletAsset;

LeafletAsset::register($this);

?>

<style>
  #main-content {
    width: 100% !important;
  }
  #map {
    width: 100%;
    height: 550px;
  }
</style>

<div class="control-wrap">
  <div style="display: flex">
    <div id="control-leftside">
      <div class="panel-group" id="accordion">
        <!-- панель -->
        <div class="panel panel-default">
          <!-- Заголовок панели -->
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseSix">Инфо</a>
            </h4>
          </div>
          <div id="collapseSix" class="panel-collapse collapse in">
            <!-- Содержимое панели -->
            <div class="panel-body">
              <p><a class="ext control-poll-info" id="about">Об опросе</a></p>
              <p><a class="ext control-poll-info" id="files">Файлы</a></p>
            </div>
          </div>
        </div>
        <div class="panel panel-default">
          <!-- Заголовок 1 панели -->
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Результаты опроса</a>
            </h4>
          </div>
          <div id="collapseOne" class="panel-collapse collapse">
            <!-- Содержимое 1 панели -->
            <div class="panel-body">
              <p><a class="ext control-poll-results" id="array-codes">Данные</a></p>
              <p><a class="ext control-poll-results" id="control-charts">Линейное распределение</a></p>
            </div>
          </div>
        </div>
        <!-- 2 панель -->
        <div class="panel panel-default">
          <!-- Заголовок 2 панели -->
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">КПТС АСО</a>
            </h4>
          </div>
          <div id="collapseTwo" class="panel-collapse collapse">
            <!-- Содержимое 2 панели -->
            <div class="panel-body">
              <p><a class="ext control-poll-parcha" id="xml-analyze">Анализ XML</a></p>
            </div>
          </div>
        </div>
        <!-- панель -->
        <div class="panel panel-default">
          <!-- Заголовок  панели -->
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseSeven">Редактор анкет</a>
            </h4>
          </div>
          <div id="collapseSeven" class="panel-collapse collapse">
            <!-- Содержимое панели -->
            <div class="panel-body">
              <p><a class="ext control-poll-construct" id="list-view">В виде списка</a></p>
              <p><a class="ext control-poll-construct" id="grid-view">В виде сетки</a></p>
            </div>
          </div>
        </div>
        <!-- 5 панель -->
        <div class="panel panel-default">
          <!-- Заголовок 5 панели -->
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive">Пакетный ввод</a>
            </h4>
          </div>
          <div id="collapseFive" class="panel-collapse collapse">
            <!-- Содержимое 5 панели -->
            <div class="panel-body">
              <p><a class="ext control-batch-input" id="input">Ввод данных</a></p>
            </div>
          </div>
        </div>
        <!-- 3 панель -->
        <div class="panel panel-default">
          <!-- Заголовок 3 панели -->
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">Статистика</a>
            </h4>
          </div>
          <div id="collapseThree" class="panel-collapse collapse">
            <div class="panel-body">
              <p><a class="ext control-statistic" id="drive-statistic">Статистика по вводу</a></p>
            </div>
          </div>
        </div>
        <!-- 4 панель -->
        <div class="panel panel-default">
          <!-- Заголовок 4 панели -->
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">Тестирование</a>
            </h4>
          </div>
          <div id="collapseFour" class="panel-collapse collapse">
            <div class="panel-body">
              <p><a class="ext control-poll-tests" id="generate">Генерация</a></p>
              <p><a class="ext control-poll-tests" id="settings">Настройки</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="control-rightside">
      <div id="control-header">
      </div>
      <div id="control-result">
      </div>
      <div id="control-footer">
      </div>
    </div>
  </div>

</div>