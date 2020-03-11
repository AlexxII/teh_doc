<div id="vks-period-input">
  <div class="input-group input-daterange" id="vks-daterange">
    <label class="h-title" data-toggle="tooltip" data-placement="left"
           title="Выберите период" style="">
      <svg width="20px" height="20px" viewBox="0 0 24 24" fill="#000">
        <path d="M0 0h24v24H0z" fill="none"></path>
        <path d="M11 17h2v-6h-2v6zm1-15C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12
                    2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zM11 9h2V7h-2v2z"></path>
      </svg>
    </label>
    <input type="text" class="form-control input-sm" id="start-date" readonly>
    <div class="input-group-addon">по</div>
    <input type="text" class="form-control input-sm" id="end-date" readonly>
  </div>
</div>

<script>
  $('.input-daterange').datepicker({
    autoclose: true,
    language: "ru",
    startView: "days",
    minViewMode: "days",
    clearBtn: true,
    todayHighlight: true,
    daysOfWeekHighlighted: [0, 6]
  });
</script>