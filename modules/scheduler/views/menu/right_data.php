<style>
  button {
    margin: 10px 20px 0 20px;
  }
  .checkk {
    left:26px;
    top: 2px;
    width: 6px;
    height: 14px;
    transform: rotate(45deg);
    transform-origin: left;
    display: block;
    border-right: 2px solid #222;
    border-bottom: 2px solid #222;
    opacity: .54;
    position: absolute;
  }

</style>
<div class="dropdown" id="view-selector" title="Месяц">
  <button class="btn btn-default dropdown-toggle" type="button" id="view-menu-btn"
          data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"
          style="max-width: 110px; text-overflow: ellipsis;overflow: hidden;padding: 5px; white-space: nowrap; ">
    <span id="title" style="padding: 0 10px 0 10px"> Месяц</span>
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="view-menu-btn">
    <li value="timeGridDay"><a href="">День</a></li>
    <li value="timeGridWeek"><a href="">Неделя</a></li>
    <li value="dayGridMonth"><a href="">Месяц</a></li>
    <li value="year"><a href="">Год</a></li>
    <li value="list"><a href="">Расписание</a></li>
    <li role="separator" class="divider"></li>
    <li id="holidays-view" value="holidays" style="position: relative">
      <a href="">
        <span class="checkk"></span>
        <span style="margin-left:28px">
          Показывать праздники
        </span>
      </a>
    </li>
    <li id="waste-wiew" value="waste" style="position: relative; display: none;">
      <a href="">
        <span class="checkk"></span>
        <span style="margin-left:28px">
        Показывать отработанные мероприятия
        </span>
      </a>
    </li>
  </ul>
</div>