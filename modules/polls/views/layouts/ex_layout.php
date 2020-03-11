<div id="app-wrap">
  <nav class="navigation navigation-default">
    <div class="container-fluid">
      <ul class="nav navbar-nav" id="left">
        <button id="go-back" type="button" class="btn btn-default btn-circle btn-ml">
          <svg focusable="false" viewBox="0 0 24 24">
            <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"></path>
          </svg>
        </button>
        <li id="app-name" class="app-settings">
          <?= $this->params['title']; ?>
        </li>
        <li id="poll-title" style="padding: 5px 0 0 15px;font-weight: bold; float: left">
        </li>
        <li id="poll-town" style="padding: 5px 0 0 15px;font-weight: bold; float: left">
        </li>
      </ul>
      <ul class="navig navigation-nav navigation-right">
        <li id=ex-right-custom-data-ex">
        </li>
        <li id="ex-right-custom-data">
        </li>
        <li id="apps" class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
             aria-expanded="false">
            <svg width="24" height="24" viewBox="0 0 24 24">
              <path d="M6,8c1.1,0 2,-0.9 2,-2s-0.9,-2 -2,-2 -2,0.9 -2,2 0.9,2 2,2zM12,20c1.1,0 2,-0.9 2,-2s-0.9,-2 -2,-2 -2,0.9 -2,2 0.9,2
              2,2zM6,20c1.1,0 2,-0.9 2,-2s-0.9,-2 -2,-2 -2,0.9 -2,2 0.9,2 2,2zM6,14c1.1,0 2,-0.9 2,-2s-0.9,-2 -2,-2 -2,0.9 -2,2 0.9,2
              2,2zM12,14c1.1,0 2,-0.9 2,-2s-0.9,-2 -2,-2 -2,0.9 -2,2 0.9,2 2,2zM16,6c0,1.1 0.9,2 2,2s2,-0.9 2,-2 -0.9,-2 -2,-2 -2,0.9
              -2,2zM12,8c1.1,0 2,-0.9 2,-2s-0.9,-2 -2,-2 -2,0.9 -2,2 0.9,2 2,2zM18,14c1.1,0 2,-0.9 2,-2s-0.9,-2 -2,-2 -2,0.9 -2,2 0.9,2
              2,2zM18,20c1.1,0 2,-0.9 2,-2s-0.9,-2 -2,-2 -2,0.9 -2,2 0.9,2 2,2z">
              </path>
            </svg>
          </a>
          <ul class="dropdown-menu navig">
            <div class="list-group">
              <a href="/vks" target="_blank" class="list-group-item">
                <h4 class="list-group-item-heading">Журнал ВКС</h4>
                <p class="list-group-item-text">Журнал сеансов видеосвязи</p>
              </a>
            </div>
            <div class="list-group">
              <a href="/scheduler" target="_blank" class="list-group-item">
                <h4 class="list-group-item-heading">Календарь</h4>
                <p class="list-group-item-text">Календарь</p>
              </a>
            </div>
            <div class="list-group">
              <a href="/equipment" target="_blank" class="list-group-item">
                <h4 class="list-group-item-heading">Техника</h4>
                <p class="list-group-item-text">Перечень оборудования</p>
              </a>
            </div>
            <div class="list-group">
              <a href="/to" target="_blank" class="list-group-item">
                <h4 class="list-group-item-heading">ТО</h4>
                <p class="list-group-item-text">Техническое обслуживание</p>
              </a>
            </div>
            <div class="list-group">
              <a href="/polls" target="_blank" class="list-group-item">
                <h4 class="list-group-item-heading">Опрос</h4>
                <p class="list-group-item-text">Социологические опросы</p>
              </a>
            </div>
            <div class="list-group">
              <a href="/maps" target="_blank" class="list-group-item">
                <h4 class="list-group-item-heading">Карты</h4>
                <p class="list-group-item-text">Карта России</p>
              </a>
            </div>
          </ul>
        </li>
      </ul>
    </div><!-- /.container-fluid -->
  </nav>
  <div id="ex-main-wrap" style="background-color: #fff; z-index: 200">
    <div id="main-content" class="container">
      <?= $content ?>
    </div>
  </div>
</div>

<script>
  $(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    $('#go-back').click(function (e) {
      $('body').unbind();
      goBack();
    });

  });

  function goBack(data = null) {
    $('#ex-wrap').detach();
    var url = $(this).data('backUrl');
    window.history.pushState("object or string", "Title", url);
    pollConstruct = null;
    returnCallback(data);
  }
</script>

