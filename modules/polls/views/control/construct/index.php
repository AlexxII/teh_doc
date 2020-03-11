<div class="construct-content">
  <div class="poll-construct-wrap">
    <div class="poll-construct-header">
      <div class="service-info">
        <span id="poll-info">
          <svg viewBox="0 0 512 512" x="0px" y="0px" width="30px" height="28px">
            <path d="M 256 0 C 114.844 0 0 114.844 0 256 s 114.844 256 256 256 s 256 -114.844 256 -256 S 397.156 0 256
            0 Z M 298.667 416 c 0 5.896 -4.771 10.667 -10.667 10.667 h -64 c -5.896 0 -10.667 -4.771 -10.667 -10.667 V
            256 h -10.667 c -5.896 0 -10.667 -4.771 -10.667 -10.667 v -42.667 c 0 -5.896 4.771 -10.667 10.667 -10.667
            H 288 c 5.896 0 10.667 4.771 10.667 10.667 V 416 Z M 256 170.667 c -23.531 0 -42.667 -19.135 -42.667 -42.667
            S 232.469 85.333 256 85.333 s 42.667 19.135 42.667 42.667 S 279.531 170.667 256 170.667 Z"/>
          </svg>
        </span>
      </div>
      <div class="undo-changes" style="display: none">
        <a id="del-wrap" class="fab-button btn-sml" title="Отменить изменение очередности">
          <svg viewBox="0 0 1280 1090" width="20px" height="20px">
            <g fill="#fff" stroke="none" transform="translate(0 1090) scale(0.1 -0.1)">
              <path d="M 3476 10880 c -33 -12 -78 -34 -100 -50 c -62 -43 -3290 -3279 -3320 -3328 c -79 -127 -69 -296 23
            -418 c 65 -86 3170 -3805 3220 -3857 c 149 -155 399 -149 542 12 c 27 30 59 80 72 111 l 22 55 l 3 1076 l 3
            1077 l 22 6 c 58 14 390 57 547 70 c 999 85 2000 -65 2938 -440 c 1300 -519 2464 -1483 3422 -2834 c 426 -601
            805 -1264 1184 -2070 c 71 -149 119 -206 215 -253 c 59 -28 75 -32 156 -32 c 80 0 97 3 155 31 c 117 56 201
            171 216 294 c 10 85 -74 673 -162 1145 c -453 2411 -1401 4256 -2814 5471 c -1061 912 -2367 1466 -3850 1633
            c -312 36 -429 43 -812 48 c -449 7 -755 -8 -1140 -53 l -58 -7 l -2 1029 l -3 1029 l -33 67 c -60 123 -172
            195 -317 204 c -54 4 -83 0 -129 -16 Z"/>
            </g>
          </svg>
        </a>
      </div>
      <div class="construct-range-btn">
        <label class="range-label">Масштаб:</label>
        <input type="range" id="myRange" step="1" min="0" max="100" value="0">
      </div>
      <div class="construct-control-btns">
        <button id="btn-switch-view" title="В виде сетки" type="button"
                class="btn btn-default btn-circle btn-ml btn-active" data-mode='1'>
          <div class="poll-grid-view">
            <svg viewBox="0 0 24 24">
              <path d="M 4 4 L 4 8 L 8 8 L 8 4 L 4 4 z M 10 4 L 10 8 L 14 8 L 14 4 L 10 4 z M 16 4 L 16 8 L 20 8 L 20 4 L 16 4
        z M 4 10 L 4 14 L 8 14 L 8 10 L 4 10 z M 10 10 L 10 14 L 14 14 L 14 10 L 10 10 z M 16 10 L 16 14 L 20 14 L 20 10 L
        16 10 z M 4 16 L 4 20 L 8 20 L 8 16 L 4 16 z M 10 16 L 10 20 L 14 20 L 14 16 L 10 16 z M 16 16 L 16 20 L 20 20 L 20
        16 L 16 16 z"/>
            </svg>
          </div>
          <div class="poll-list-view" style="display: none">
            <svg viewBox="0 0 24 24">
              <path d="M 23.244 17.009 H 0.75 c -0.413 0 -0.75 0.36 -0.75 0.801 v 3.421 C 0 21.654 0.337 22 0.75 22 h
            22.494 c 0.414 0 0.75 -0.346 0.75 -0.77 V 17.81 C 23.994 17.369 23.658 17.009 23.244 17.009 Z M 23.244
            9.009 H 0.75 C 0.337 9.009 0 9.369 0 9.81 v 3.421 c 0 0.424 0.337 0.769 0.75 0.769 h 22.494 c 0.414 0 0.75
            -0.345 0.75 -0.769 V 9.81 C 23.994 9.369 23.658 9.009 23.244 9.009 Z M 23.244 1.009 H 0.75 C 0.337 1.009 0
            1.369 0 1.81 V 5.23 c 0 0.423 0.337 0.769 0.75 0.769 h 22.494 c 0.414 0 0.75 -0.346 0.75 -0.769 V 1.81 C
            23.994 1.369 23.658 1.009 23.244 1.009 Z"/>
            </svg>
          </div>
        </button>
      </div>
      <!--      <button id="btn-switch-view" type="button" class="btn btn-default btn-circle btn-ml btn-active">-->
      <!--      </button>-->
    </div>
    <div id="poll-construct">

    </div>
  </div>

  <div class="hidden">

    <div id="question-extension-menu">
      <!--      <div class="dde-menu-item">Добавить ответ</div>-->
      <div class="dde-menu-item question-trash">Удаленные ответы</div>
      <div class="dde-menu-item">Удалить вопрос</div>
    </div>

    <div id="answer-extension-menu">
      <div class="dde-menu-item">Удалить ответ</div>
      <!--      <div class="dde-menu-item">Изменить тип</div>-->
      <!--      <div class="dde-menu-item">Задать логику</div>-->
    </div>
  </div>
</div>
