<style>
  .app-settings {
    font-weight: 400 !important;
    margin: 12px 0 0 30px !important;
  }
</style>

<div id="app-wrap">
  <nav class="navigation navigation-default">
    <div class="container-fluid">
      <ul class="nav navbar-nav">
        <button id="go-back" type="button" class="btn btn-default btn-circle btn-ml">
          <svg focusable="false" viewBox="0 0 24 24">
            <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"></path>
          </svg>
        </button>
        <li id="app-name" class="app-settings">
          <?= $this->params['title']; ?>
        </li>
      </ul>
      <ul class="navig navigation-nav navigation-right">
        <li id=ex-right-custom-data-ex">
        </li>
        <li id="ex-right-custom-data">
        </li>
        <li id="accounts" class="dropdown hidden">
          <?php if (Yii::$app->user->isGuest): ?>
          <a href="/site/login" role="button" aria-haspopup="true" title="Войти">
            <svg height="24" width="24" viewBox="0 0 1792 1792" style="fill:#000">
              <path d="M1312 896q0 26-19 45l-544 544q-19 19-45 19t-45-19-19-45v-288h-448q-26 0-45-19t-19-45v-384q0-26
            19-45t45-19h448v-288q0-26 19-45t45-19 45 19l544 544q19 19 19 45zm352-352v704q0 119-84.5 203.5t-203.5
            84.5h-320q-13 0-22.5-9.5t-9.5-22.5q0-4-1-20t-.5-26.5 3-23.5 10-19.5 20.5-6.5h320q66 0
            113-47t47-113v-704q0-66-47-113t-113-47h-312l-11.5-1-11.5-3-8-5.5-7-9-2-13.5q0-4-1-20t-.5-26.5 3-23.5 10-19.5
            20.5-6.5h320q119 0 203.5 84.5t84.5 203.5z"/>
              </path>
            </svg>
            <?php else: ?>
              <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                 aria-expanded="false">
                <svg class="rui-ToplineUser-userIcon" viewBox="0 0 16 16" width="24" height="24">
                  <path d="M14 12.197c0-.66-.205-1.311-.614-1.829a7.536 7.536 0 0 0-2.734-2.164 4.482 4.482 0 0 1-6.304 0
              7.536 7.536 0 0 0-2.734 2.164A2.947 2.947 0 0 0 1 12.197V13a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-.803zM4.5 5c0
              1.654 1.346 3 3 3s3-1.346 3-3V3c0-1.654-1.346-3-3-3s-3 1.346-3 3v2zM0 15V0v15zM15 0v15V0z">
                  </path>
                </svg>
              </a>
              <ul class="dropdown-menu">
                <div class="list-group">
                  <a href="" class="list-group-item ex-click" data-url="/admin/user/profile"
                     data-uri="/admin/user/profile">
                    <h4 class="list-group-item-heading">Профиль</h4>
                    <p class="list-group-item-text"><?= Yii::$app->user->identity->username ?></p>
                  </a>
                </div>
                <div class="list-group">
                  <a id="logout" data-href="/site/logout" class="list-group-item">
                    <p class="list-group-item-text">Выход</p>
                  </a>
                </div>
              </ul>
            <?php endif; ?>
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
      goBack();
    });

  });
  function goBack(data = null) {
    $('#ex-wrap').detach();
    var url = $(this).data('backUrl');
    window.history.pushState("object or string", "Title", url);
    returnCallback(data);
  }
</script>

