<?php

use app\assets\TableBaseAsset;
use app\modules\polls\asset\PollAsset;
use app\assets\NotyAsset;
use app\assets\SortableJSAsset;
use app\assets\NprogressAsset;
use app\assets\Select2Asset;
use app\modules\maps\asset\LeafletAsset;
use app\modules\maps\asset\LeafletClusterAsset;
use app\assets\FancytreeAsset;


FancytreeAsset::register($this);
LeafletAsset::register($this);
LeafletClusterAsset::register($this);
NprogressAsset::register($this);
PollAsset::register($this);
TableBaseAsset::register($this);                // регистрация ресурсов таблиц datatables
NotyAsset::register($this);
SortableJSAsset::register($this);
Select2Asset::register($this);

?>
<div class="tool-task">
  <div class="" style="position: relative">
    <div class="container-fluid" style="position: relative">
      <div id="add-poll-wrap" class="hidden-xs hidden-sm">
        <a id="add-poll" class="fab-button"
           data-url="/poll/polls/create" data-back-url="/to" title="Добавить новый опрос">
          <div class="plus"></div>
        </a>
      </div>
    </div>

    <div id="delete-wrap" style="position: absolute; top: 10px; right:-60px;display: none ;fill: white">
      <a id="del-wrap" class="fab-button" title="Удалить выделенный опроc"
         style="cursor: pointer; background-color: red">
        <svg width="50" height="50" viewBox="-1 -1 24 24">
          <path d="M15 4V3H9v1H4v2h1v13c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V6h1V4h-5zm2 15H7V6h10v13z"></path>
          <path d="M9 8h2v9H9zm4 0h2v9h-2z"></path>
        </svg>
      </a>
    </div>

    <div id="poll-control" style="position: absolute; top: 70px; right:-60px;display: none;fill: white">
      <a id="poll-control-a" class="fab-button" title="Настройки и результаты"
         style="cursor: pointer; background-color: gold;padding-left: 10px;padding-top: 10px ">
        <svg viewBox="-79 0 512 512" width="35" height="35">
          <g>
            <path d="m76.328125 391.902344h200.667969v40.058594h-200.667969zm0 0"/>
            <path d="m176.664062 512c31.722657 0 58.59375-21.125 67.316407-50.039062h-134.636719c8.722656 28.917968
            35.59375 50.039062 67.320312 50.039062zm0 0"/>
            <path d="m301.460938 51.625c-33.363282-33.300781-77.664063-51.625-124.796876-51.625-.113281
            0-.230468 0-.34375 0-97.355468.1875-176.453124 79.546875-176.320312 176.90625.0585938 43.207031 15.886719
            84.777344 44.566406 117.046875 17.226563 19.386719 27.941406 43.101563 30.914063 67.949219h202.425781c3.0625-24.929688
            13.8125-48.710938 31.042969-68.164063 28.617187-32.300781 44.375-73.882812 44.375-117.074219
            0-47.253906-18.417969-91.660156-51.863281-125.039062zm-18.132813 140.039062h-31.519531c-1.992188
            10.003907-5.9375 19.3125-11.425782 27.507813l22.308594 22.308594-21.210937 21.214843-22.308594-22.308593c-8.199219
            5.484375-17.503906 9.429687-27.511719 11.425781v31.519531h-30v-31.519531c-10.003906-1.996094-19.3125-5.941406-27.507812-11.425781l-22.308594
            22.308593-21.214844-21.214843 22.308594-22.308594c-5.484375-8.195313-9.429688-17.503906-11.425781-27.507813h-31.519531v-30h31.519531c1.996093-10.003906 5.941406-19.3125
            11.425781-27.507812l-22.308594-22.308594 21.214844-21.214844 22.308594 22.308594c8.195312-5.484375
            17.503906-9.429687 27.507812-11.425781v-31.519531h30v31.519531c10.003906 1.996094 19.3125 5.941406
            27.507813 11.425781l22.308593-22.308594 21.214844 21.214844-22.308594 22.308594c5.484376 8.195312
            9.429688 17.503906 11.425782 27.507812h31.519531zm0 0"/>
            <path d="m176.664062 130.03125c-25.714843
            0-46.632812 20.921875-46.632812 46.632812 0 25.710938 20.917969 46.632813 46.632812 46.632813 25.710938
            0 46.628907-20.921875 46.628907-46.632813 0-25.710937-20.917969-46.632812-46.628907-46.632812zm0 0"/>
          </g>
        </svg>
      </a>
    </div>

    <div class="container-fluid">
      <table id="poll-main-table" class="display no-wrap cell-border poll-table" style="width:100%">
        <thead>
        <tr>
          <th></th>
          <th data-priority="1">№ п.п.</th>
          <th data-priority="1">Код</th>
          <th data-priority="7">Наименование</th>
          <th>Период</th>
          <th></th>
          <th>Выборка</th>
          <th data-priority="3">Action</th>
          <th></th>
        </tr>
        </thead>
      </table>
    </div>
  </div>
</div>


<div class="hidden">

  <div id="question-main-template" class="question-wrap">
    <div class="question-content">
      <span class="original-question-order" title="Очередность по ИС 'Барометр'"></span>
      <div class="question-header">
        <h2 class="question-data">
          <span class="question-order"></span>
          <span class="question-number-dot">.</span>
          <span class="question-title"></span>
        </h2>
        <div class="question-service-area">
          <div class="question-hide question-service-btn" title="Скрыть для заполнения" data-id="">
            <svg width="20" height="20" viewBox="0 0 24 24">
              <path fill="none" d="M0 0h24v24H0V0z"></path>
              <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19
                17.59 13.41 12 19 6.41z"></path>
            </svg>
          </div>
          <div class="restore-question answer-service-btn" title="Восстановить вопрос">
            <svg viewBox="0 0 1280 1090" width="20" height="20">
              <g fill="#000" stroke="none" transform="translate(0 1090) scale(0.1 -0.1)">
                <path d="M 3476 10880 c -33 -12 -78 -34 -100 -50 c -62 -43 -3290 -3279 -3320 -3328 c -79 -127 -69 -296 23
          -418 c 65 -86 3170 -3805 3220 -3857 c 149 -155 399 -149 542 12 c 27 30 59 80 72 111 l 22 55 l 3 1076 l 3
          1077 l 22 6 c 58 14 390 57 547 70 c 999 85 2000 -65 2938 -440 c 1300 -519 2464 -1483 3422 -2834 c 426 -601
          805 -1264 1184 -2070 c 71 -149 119 -206 215 -253 c 59 -28 75 -32 156 -32 c 80 0 97 3 155 31 c 117 56 201
          171 216 294 c 10 85 -74 673 -162 1145 c -453 2411 -1401 4256 -2814 5471 c -1061 912 -2367 1466 -3850 1633
          c -312 36 -429 43 -812 48 c -449 7 -755 -8 -1140 -53 l -58 -7 l -2 1029 l -3 1029 l -33 67 c -60 123 -172
          195 -317 204 c -54 4 -83 0 -129 -16 Z"/>
              </g>
            </svg>
          </div>
          <div class="question-options question-service-btn dropdown-toggle" id="question-menu" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="true">
            <svg width="20" height="20" viewBox="0 0 24 24">
              <path fill="none" d="M0 0h24v24H0V0z"></path>
              <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0
              6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path>
            </svg>
          </div>
          <ul class="dropdown-menu" aria-labelledby="question-menu">
            <li><a href="#">Удалить вопрос</a></li>
          </ul>
        </div>
        <input class="question-limit question-service-btn" title="Максимальное количество ответов">
      </div>
      <div class="answers-content">
      </div>
      <div class="answers-content-ex">
      </div>
    </div>
  </div>

  <div id="answer-template" class="list-group-item answer-data">
    <div class="answer-about-area">
      <span class="answer-number"></span>
      <span class="answer-number-dot">.</span>
      <span class="answer-title"></span>
      <span class="answer-code"></span>
      <span class="answer-old-order"></span>
    </div>
    <div class="answer-service-area">
      <span class="answer-hide answer-service-btn" title="Скрыть при заполнении">
        <svg width="20" height="20" viewBox="0 0 24 24">
          <path fill="none" d="M0 0h24v24H0V0z"></path>
          <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19
          17.59 13.41 12 19 6.41z"></path>
        </svg>
      </span>
      <span class="answer-options answer-service-btn dropdown-toggle" id="question-menu" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="true">
        <svg width="20" height="20" viewBox="0 0 24 24">
          <path fill="none" d="M0 0h24v24H0V0z"></path>
          <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0
              6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path>
        </svg>
      </span>
      <ul class="dropdown-menu answer-menu" aria-labelledby="question-menu">
        <li><a class="logic" href="#">Логика</a></li>
        <li role="separator" class="divider"></li>
        <li><a class="delete-answer" href="#">Удалить ответ</a></li>
      </ul>
      <span class="unique-btn answer-service-btn" data-unique="0" title="Уникальный для вопроса">
        <svg width="20" height="20" viewBox="0 0 560.317 560.316">
          <path d="M 207.523 560.316 c 0 0 194.42 -421.925 194.444 -421.986 l 10.79 -23.997 c -41.824 12.02 -135.271
          34.902 -135.57 35.833 C 286.96 122.816 329.017 0 330.829 0 c -39.976 0 -79.952 0 -119.927 0 l -12.167 57.938
          l -51.176 209.995 l 135.191 -36.806 L 207.523 560.316 Z"/>
        </svg>
      </span>
      <span class="restore-btn answer-service-btn" title="Восстановить ответ">
        <svg viewBox="0 0 1280 1090" width="20" height="20">
          <g fill="#000" stroke="none" transform="translate(0 1090) scale(0.1 -0.1)">
            <path d="M 3476 10880 c -33 -12 -78 -34 -100 -50 c -62 -43 -3290 -3279 -3320 -3328 c -79 -127 -69 -296 23
          -418 c 65 -86 3170 -3805 3220 -3857 c 149 -155 399 -149 542 12 c 27 30 59 80 72 111 l 22 55 l 3 1076 l 3
          1077 l 22 6 c 58 14 390 57 547 70 c 999 85 2000 -65 2938 -440 c 1300 -519 2464 -1483 3422 -2834 c 426 -601
          805 -1264 1184 -2070 c 71 -149 119 -206 215 -253 c 59 -28 75 -32 156 -32 c 80 0 97 3 155 31 c 117 56 201
          171 216 294 c 10 85 -74 673 -162 1145 c -453 2411 -1401 4256 -2814 5471 c -1061 912 -2367 1466 -3850 1633
          c -312 36 -429 43 -812 48 c -449 7 -755 -8 -1140 -53 l -58 -7 l -2 1029 l -3 1029 l -33 67 c -60 123 -172
          195 -317 204 c -54 4 -83 0 -129 -16 Z"/>
          </g>
        </svg>
      </span>
    </div>
  </div>

  <svg enable-background="new 0 0 1000 1000" viewBox="0 0 1000 1000" x="0px" y="0px">
    <g>
      <path d="M 609.5 637.3 c -116.8 -6.5 -228.4 11.9 -335.2 57.8 C 166.8 741.3 79.3 812.6 10 907.4 c 1.4 -8.3 2.7
    -16.6 4.1 -24.9 c 1.5 -8.3 3 -16.6 4.8 -24.9 c 22.5 -101.8 58.7 -198.2 115.2 -286.3 c 57.9 -90.3 132.3 -162.7 230.2
    -209 c 63.3 -30 130.1 -45.9 199.8 -50.3 c 14.8 -0.9 29.6 -1.4 44.9 -2.1 c 0 -71.9 0 -143.6 0 -215.2 c 0.9 -0.7 1.8
    -1.4 2.7 -2.1 C 738 219.8 864.1 346.9 990 473.8 c -126.8 126.5 -253.4 253 -380.5 379.8 C 609.5 782 609.5 710.2 609.5
    637.3 Z"/>
    </g>
  </svg>

  <div id="gridview-template" class="grid-item" data-id="">
    <div class="grid-content">
      <span class="question-order"></span><span class="dot">.</span>
      <span class="question-title"></span>
    </div>
  </div>

  <div id="question-batch-template" class="question-wrap">
    <div class="question-content">
      <div class="question-header">
        <h2 class="question-data">
          <span class="question-order"></span>
          <span class="question-number-dot">.</span>
          <span class="question-title"></span>
        </h2>
        <span class="question-limit question-service-btn" title="Максимальное количество ответов">
      </div>
      <div class="answers-content">
      </div>
    </div>
  </div>

  <div id="answer-batch-template" class="list-group-item answer-data">
    <div class="answer-about-area">
      <span class="answer-number"></span>
      <span class="answer-number-dot">.</span>
      <span class="answer-title"></span>
      <span class="answer-code"></span>
    </div>
  </div>

  <ul id="question-tmpl-ex">
    <li>
      <span class="q-order"></span>
      <span class="q-title check-all"> Вопрос</span>
      <ul class="question-content-ex">
      </ul>
    </li>
  </ul>

  <li id="answer-li-tmpl" class="answer-tmpl-ex">
    <label><input class="check-logic" type="checkbox">
      <span class="a-code"></span>
      <span class="a-title"></span>
    </label>
  </li>

</div>

