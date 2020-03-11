

<style>
  #main-content {
    width: 100% !important;
  }
  #settings-leftside {
    width: 256px;
    height: 100%;
    padding: 0 10px 0 10px;
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

<div style="display: flex">
  <div id="settings-leftside">
    <div class="panel-group" id="accordion">
      <!-- 1 панель -->
      <div class="panel panel-default">
        <!-- Заголовок 1 панели -->
        <div class="panel-heading">
          <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Добавить календарь</a>
          </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse in">
          <!-- Содержимое 1 панели -->
          <div class="panel-body">
            <p><a class="ext" data-url="/control/settings/calendars-for-subscribe">Подписаться на календарь</a></p>
            <p><a class="ext" data-url="/control/settings/create-calendar">Создать новый календарь</a></p>
          </div>
        </div>
      </div>
      <!-- 2 панель -->
      <div class="panel panel-default" id="users-calendars">
        <!-- Заголовок 2 панели -->
        <div class="panel-heading">
          <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Настройка моих календарей</a>
          </h4>
        </div>
        <div id="collapseTwo" class="panel-collapse collapse">
          <!-- Содержимое 2 панели -->
          <div class="panel-body">
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
  <div id="settings-rightside">
    <div id="setting-content-wrap">
      <div id="setting-content"></div>
    </div>
  </div>
</div>

<script>
  $('.ext').on('click', function () {
    var url = 'scheduler' + $(this).data('url');
    $.ajax({
      url: url,
      method: 'get'
    }).done(function (response) {
      $('#setting-content').html(response.data.data);
    }).fail(function () {
      console.log('Что-то пошло не так');
    });
  });

  $(document).ready(function () {
    var url = 'scheduler/control/settings/user-calendars';
    $.ajax({
      url: url,
      method: 'get'
    }).done(function (response) {
      var calendars = response.data.data;
      for (var key in calendars) {
        $('#users-calendars .panel-body').append('<p class="calendar" data-id="' + key + '">' + calendars[key] + '</p>');
      }
    }).fail(function () {
      console.log('Что-то пошло не так');
    });
  });

  /* For calendar creation */
  $(document).on('click', '#save-new-calendar', function (e) {
    var url = '/scheduler/control/settings/save-calendar';
    var $form = $("#new-calendar"),
      data = $form.data("yiiActiveForm");
    $.each(data.attributes, function () {
      this.status = 3;
    });
    $form.yiiActiveForm("validate");
    if ($("#new-calendar").find(".has-error").length) {
      return false;
    } else {
      $.ajax({
        type: 'POST',
        url: url,
        dataType: 'json',
        data: $form.serialize(),
        success: function (response) {
          $form[0].reset();
          var calendar = response.data.data;
          for (var key in calendar) {
            $('#users-calendars .panel-body').append('<p class="calendar" data-id="' + key + '">' + calendar[key] + '</p>');
          }
          console.log('Calendar created');
        },
        error: function (response) {
          console.log(response.data.data);
        }
      });
    }
  });

  $(document).on('click', '.calendar', function (e) {
    var id = $(this).data('id');
    var url = '/scheduler/control/settings/calendar-settings?id=' + id;
    $.ajax({
      url: url,
      method: 'get'
    }).done(function (response) {
      $('#setting-content').html(response.data.data);
    }).fail(function () {
      console.log('Что-то пошло не так');
    });

  });

</script>
