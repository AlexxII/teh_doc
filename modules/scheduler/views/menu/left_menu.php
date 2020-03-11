<div id="left-menu">
  <div class="menu-list">
    <div id="datepicker" style="margin-left: 20px"></div>
  </div>
  <div style="padding-left: 25px">
    <p>Мои календари</p>
  </div>
  <?php
/*  TODO Таблица с календарями:
  - id календаря
  - title наименование календаря
  - user_id пользователь, который создал
  - created дата создания
  - color цвет календаря
*/

?>
</div>

<script>
    $('#datepicker').datepicker({
        language: 'ru',
        todayHighlight: true
    }).on('changeDate', function (info) {
        console.log(info.date);
        calendar.gotoDate(info.date)
    });
</script>

