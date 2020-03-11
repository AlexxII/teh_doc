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

    <div id="construct-wrap" style="position: absolute; top: 70px; right:-60px;display: none;fill: white">
      <a id="del-wrap" class="fab-button" title="Редактор анкет"
         style="cursor: pointer; background-color: gold;padding-left: 10px;padding-top: 7px ">
        <svg viewBox="0 0 535.806 535.807" width="35" height="35">
          <g>
            <path d="M 440.956 373.932 c -11.934 -13.158 -26.315 -19.584 -44.676 -19.584 h -38.686 l -25.398 -24.479 c
            -18.666 15.3 -41.31 24.174 -65.791 24.174 c -22.95 0 -44.676 -7.956 -62.424 -21.726 l -22.645 21.726 h
            -40.262 c -20.502 0 -36.414 7.038 -48.96 21.421 c -36.414 42.227 -30.294 132.498 -27.54 160.344 h 407.592
            C 474.31 507.654 477.982 415.242 440.956 373.932 Z"/>
            <path d="M 160.343 182.376 c -7.344 6.12 -12.24 15.912 -12.24 27.234 c 0 16.83 11.016 30.6 25.092 33.048 c
            3.06 25.398 13.464 47.736 29.07 64.26 c 3.365 3.366 6.731 6.732 10.403 9.486 c 4.591 3.672 9.486 6.732
            14.688 9.486 c 11.628 6.119 24.479 9.485 38.25 9.485 c 13.77 0 26.623 -3.366 38.25 -9.485 c 5.202 -2.754
            10.098 -5.814 14.688 -9.486 c 3.673 -2.754 7.038 -6.12 10.404 -9.486 c 15.3 -16.523 26.01 -38.556 28.764
            -63.954 c 0.307 0 0.612 0 0.918 0 c 16.9219 0 29.07 -14.994 29.07 -33.354 c 0 -11.322 -4.896 -21.114 -12.24
            -27.234H 160.343 L 160.343 182.376 Z"/>
            <path d="M 377.409 118.116 c -9.486 -31.518 -34.578 -63.648 -66.402 -80.172 v 71.91 v 9.792 c 0 0.612 0
            0.918 0 1.224 c -0.306 3.366 -0.918 6.426 -2.447 9.486 c -3.979 7.65 -11.935 13.158 -21.114 13.158 h -4.896
             h -33.66 c -8.568 0 -16.219 -4.59 -20.196 -11.322 c -1.836 -2.754 -2.754 -5.813 -3.366 -9.18 c -0.306
             -1.224 -0.306 -2.142 -0.306 -3.366 v -8.568 v -73.44 c -31.824 16.83 -56.916 48.96 -66.402 80.478 l -2.142
             6.732 h -17.442 v 38.25 h 19.278 h 26.928 h 11.322 h 147.493 h 11.016 h 41.7 v -1.836 v -36.414 h -17.22
             L 377.409 118.116 Z"/>
            <path d="M 248.777 134.028 h 38.25 c 5.508 0 10.098 -3.06 12.546 -7.65 c 1.224 -2.142 1.836 -4.284 1.836
            -6.732 v -2.754 V 105.57 V 33.354 V 22.95 v -3.978 c 0 -2.448 -0.612 -4.59 -1.224 -6.732 C 297.432 5.202
            290.394 0 282.438 0 h -33.661 c -7.344 0 -13.464 5.508 -14.076 12.546 c 0 0.612 -0.306 1.224 -0.306 1.836
            v 8.568 v 10.404 v 73.44 v 11.628 v 1.224 c 0 3.06 0.918 5.814 2.448 8.262 C 239.598 131.58 243.881 134.028
            248.777 134.028 Z"/>
          </g>
        </svg>
      </a>
    </div>

    <div id="poll-control" style="position: absolute; top: 130px; right:-60px;display: none;fill: white">
      <a id="poll-control-a" class="fab-button" title="Результаты опроса"
         style="cursor: pointer; background-color: green;padding-left: 8px;padding-top: 7px ">
        <svg viewBox="0 0 512.008 512.008" width="38" height="38">
          <path d="M 480.032 459.646 v -267.94 h -90.004 v 267.917 l -30.003 -0.008 V 251.71 h -90.004 v 207.882 l
          -30.004 -0.008 V 311.713 h -90.004 v 147.848 l -30.003 -0.008 v -87.836 H 30.006 v 87.813 l -29.998 -0.008
          l -0.008 30 l 512 0.132 l 0.008 -30 L 480.032 459.646 Z M 420.028 221.706 h 30.004 v 237.933 l -30.004
          -0.008 V 221.706 Z M 300.021 281.71 h 30.004 v 177.898 l -30.004 -0.008 V 281.71 Z M 180.013 341.713 h
          30.004 v 117.863 l -30.004 -0.008 V 341.713 Z M 60.006 401.717 H 90.01 v 57.828 l -30.004 -0.008 V 401.717 Z" />
          <polygon points="79.614,279.007 413.235,83.531 407.206,106.358 436.211,114.019 455.364,41.506 382.851,22.353
          375.189,51.358 398.359,57.478 64.448,253.123" />
        </svg>
      </a>
    </div>

    <div id="batch-input" style="position: absolute; top: 190px; right:-60px;display: none;fill: white">
      <a id="batch-input-a" class="fab-button" title="Пакетная загрузка данных"
         style="cursor: pointer; background-color: darkblue;padding-left: 10px;padding-top: 7px ">
        <svg viewBox="0 0 493.845 493.845" width="38" height="38">
          <path d="M 455.913 202.153 h -4.358 l -34.109 -54.961 c -6.153 -9.917 -16.806 -15.836 -28.476 -15.836 h -24.559
           c -10.735 0 -19.476 8.74 -19.476 19.476 v 64.143 v 95.442 h -24.284 l -18.391 -37.107 c 7.656 -2.612 14.25
           -7.512 18.741 -14.398 c 5.31 -8.14 7.129 -17.866 5.125 -27.39 l -27.229 -129.273 c -4.042 -19.209 -23.684
           -32.131 -43.126 -28.123 l -9.148 1.929 c -9.26 -15.002 -27.182 -24.385 -47.157 -22.022 c -3.465 0.392 -6.879
           1.159 -10.219 2.279 c -10.143 -11.887 -27.973 -18.307 -47.582 -16.094 c -4.358 0.501 -8.624 1.427 -12.739
           2.771 c -8.974 -7.905 -21.028 -11.812 -33.541 -10.377 c -11.27 1.285 -21.396 6.703 -28.534 15.251 c -6.486
           7.78 -9.8 17.305 -9.499 27.115 c -7.163 1.677 -13.649 4.79 -18.691 9.023 c -7.354 6.168 -11.036 14.334 -10.109
           22.413 c 0.617 5.418 3.339 10.126 7.37 14.009 l -7.019 1.477 c -10.978 2.32 -20.127 9.416 -25.094 19.442
           c -4.983 10.042 -5.083 21.613 -0.284 31.754 l 45.813 96.769 c -0.183 0.2 -0.333 0.325 -0.526 0.534 c -3.38
           3.665 -7.755 8.918 -12.455 15.503 C 0 336.844 3.889 387.951 14.998 420.04 c 1.688 4.878 6.043 8.497 11.394
           8.164 c 6.102 -0.385 11.033 -5.677 10.36 -11.753 c -2.4 -21.69 -5.332 -50.631 21.345 -84.639 v 33.375 c 0
           20.919 17.013 37.931 37.933 37.931 h 26.888 c 1.419 32.415 28.016 58.41 60.772 58.41 c 32.756 0 59.362 -25.995
           60.781 -58.41 h 62.984 c 1.419 32.415 28.014 58.41 60.771 58.41 c 32.773 0 59.379 -25.995 60.798 -58.41 h
           26.889 c 20.919 0 37.932 -17.012 37.932 -37.931 v -29.134 v -25.636 v -70.331 C 493.845 219.166 476.832
           202.153 455.913 202.153 Z M 468.2 240.086 v 70.322 h -97.62 v -82.611 h 66.699 h 7.138 h 11.496 C 462.69
           227.797 468.2 233.308 468.2 240.086 Z M 370.581 157 h 18.39 c 2.748 0 5.244 1.387 6.687 3.715 l 25.711
           41.438 h -50.788 V 157 Z M 282.495 310.417 h -154.31 l 139.727 -29.435 L 282.495 310.417 Z M 288.672 178.121 l 12.364 58.685 c 0.591 2.812 0.049 5.693 -1.52 8.097 c -1.569 2.404 -3.973 4.057 -6.796 4.65 L 96.781 290.825 c -4.816 0.968 -9.866 -1.52 -11.945 -5.919 L 57.338 226.83 L 288.672 178.121 Z M 49.65 97.096 c 3.864 -3.238 18.006 -6.302 18.006 -6.302 c 2.595 -0.142 4.975 -1.453 6.486 -3.564 c 1.503 -2.113 1.97 -4.8 1.26 -7.296 c -0.325 -1.144 -0.675 -2.288 -0.817 -3.539 c -0.718 -6.303 1.193 -12.547 5.401 -17.58 c 4.298 -5.16 10.459 -8.432 17.346 -9.225 c 8.898 -0.953 17.397 2.362 22.873 8.866 c 2.438 2.871 6.486 3.832 9.935 2.337 c 4.257 -1.845 8.79 -3.055 13.48 -3.599 c 15.561 -1.81 29.785 3.999 35.029 13.708 c 2.204 4.089 7.245 5.668 11.369 3.573 c 3.665 -1.845 7.505 -3.014 11.404 -3.465 c 10.459 -1.144 20.076 2.539 26.554 8.965 c 0 0 -116.839 24.646 -164.367 34.619 c -1.301 0.273 -14.985 2.442 -17.76 -7.782 C 44.204 100.756 48.29 98.24 49.65 97.096 Z M 26.702 162.125 c -1.419 -2.997 -1.387 -6.419 0.083 -9.391 c 1.469 -2.962 4.173 -5.059 7.42 -5.743 l 226.852 -47.775 c 0.742 -0.157 1.494 -0.232 2.245 -0.232 c 5.043 0 9.459 3.588 10.502 8.548 l 4.448 21.119 L 35.1 179.856 L 26.702 162.125 Z M 42.621 195.742 l 239.157 -50.362 l 3.372 16.012 L 49.816 210.944 L 42.621 195.742 Z M 183.691 427.335 c -14.8 0 -26.846 -12.046 -26.846 -26.855 c 0 -14.809 12.046 -26.855 26.846 -26.855 c 14.809 0 26.855 12.046 26.855 26.855 C 210.545 415.289 198.499 427.335 183.691 427.335 Z M 368.226 427.335 c -14.8 0 -26.846 -12.046 -26.846 -26.855 c 0 -14.809 12.046 -26.855 26.846 -26.855 c 14.817 0 26.872 12.046 26.872 26.855 C 395.098 415.289 383.044 427.335 368.226 427.335 Z M 468.2 365.187 c 0 6.777 -5.51 12.287 -12.287 12.287 h -31.197 c -9.116 -22.271 -30.961 -38.041 -56.49 -38.041 c -25.509 0 -47.356 15.77 -56.464 38.041 h -71.599 c -9.116 -22.271 -30.961 -38.041 -56.473 -38.041 c -25.511 0 -47.356 15.77 -56.464 38.041 H 96.031 c -6.778 0 -12.289 -5.51 -12.289 -12.287 v -29.125 H 468.2 V 365.187 Z"/>
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

