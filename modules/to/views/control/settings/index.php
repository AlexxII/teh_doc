<style>
  #main-content {
    width: 100% !important;
  }
  #settings-rightside {
    height: 100%;
    width: 100%
  }
  #settings-leftside h4 {
    font-size: 13px;
    font-weight: 600;
  }
  #settings-leftside h4 a {
    text-decoration: none;
  }
  .panel-body p {
    font-size: 12px;
    cursor: pointer;
  }
  .panel-body a {
    text-decoration: none;
    cursor: pointer;
  }

</style>

<div style="">
  <div class="col-lg-2 col-md-2">
    <div id="settings-leftside">
      <div class="panel-group" id="accordion">
        <!-- 1 панель -->
        <div class="panel panel-default">
          <!-- Заголовок 1 панели -->
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Графики ТО</a>
            </h4>
          </div>
          <div id="collapseOne" class="panel-collapse collapse in">
            <!-- Содержимое 1 панели -->
            <div class="panel-body">
              <p><a class="ext" data-url="/control/schedule/to-equipment">Оборудование</a></p>
              <p><a class="ext" data-url="/control/schedule/to-type">Виды ТО</a></p>
              <p><a class="ext" data-url="/control/schedule/to-admins">Сотрудники</a></p>
              <p><a class="ext" data-url="/control/schedule/to-equipment-trash">Удаленное из графика ТО</a></p>
            </div>
          </div>
        </div>
        <!-- 2 панель -->
        <div class="panel panel-default" id="users-calendars">
          <!-- Заголовок 2 панели -->
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Наработка</a>
            </h4>
          </div>
          <div id="collapseTwo" class="panel-collapse collapse">
            <!-- Содержимое 2 панели -->
            <div class="panel-body">
              <p><a class="ext" data-url="/control/count/equipment">Оборудование</a></p>
              <p><a class="ext" data-url="/control/count/templates">Шаблоны подсчета</a></p>
            </div>
          </div>
        </div>
        <!-- 3 панель -->
        <div class="panel panel-default" style="display: none">
          <!-- Заголовок 3 панели -->
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">Настройки других календарей</a>
            </h4>
          </div>
          <div id="collapseThree" class="panel-collapse collapse">
            <!-- Содержимое 3 панели -->
            <div class="panel-body">
              <p></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-10 col-md-10">
    <div id="settings-rightside">
      <div id="setting-content-wrap">
        <div id="setting-content"></div>
      </div>
    </div>
  </div>
</div>

<script>
  $('.ext').on('click', function () {
    var url = 'to' + $(this).data('url') + '?back-url=/to';
    $.ajax({
      url: url,
      method: 'get'
    }).done(function (response) {
      $('#setting-content').html(response.data.data);
    }).fail(function () {
      console.log('Что-то пошло не так');
    });
  });

  /* For calendar creation */

  $(document).on('click', '.calendar', function (e) {

  });

</script>
