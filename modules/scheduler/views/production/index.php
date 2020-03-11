<div id="full-calendar"></div>

<script>
  $(document).ready(function () {
    var todayAll = new Date();

    todayAll.setHours(0, 0, 0, 0);
    var today = todayAll.getTime();

    $('#full-calendar').calendar({
      language: 'ru',
      style: 'custom',
      enableContextMenu: true,
      displayWeekNumber: true,
      enableRangeSelection: true,
      customDayRenderer: function (element, date) {
        if (date.getTime() == today) {
          $(element).css('background-color', 'red');
          $(element).css('color', 'white');
          $(element).css('border-radius', '15px');
        }
      },
      mouseOnDay: function (e) {
        if (e.events.length > 0) {
          var content = '';
          for (var i in e.events) {
            content += '<div class="event-tooltip-content">'
              + '<div class="event-name" style="color:' + e.events[i].color + '">' + e.events[i].name + '</div>'
              + '<div class="event-location">' + e.events[i].location + ' ' + e.events[i].duration + '</div>'
              + '</div>';
          }
          $(e.element).popover({
            trigger: 'manual',
            container: 'body',
            html: true,
            content: content
          });

          $(e.element).popover('show');
        }
      },
      mouseOutDay: function (e) {
        if (e.events.length > 0) {
          $(e.element).popover('hide');
        }
      },
      selectRange: function (e) {
        console.log(e);
        var day = 24 * 60 * 60 * 1000;
        var diff = ((e.endDate - e.startDate) / day) + 1;
        $('#info-panel').html('<div style="font-size: 20px">' + diff + '</div>')


      },
      yearChanged: function (e) {
        e.preventRendering = true;
        $(e.target).append('<div style="text-align:center"><img src="/lib/3.gif" /></div>');
        var currentYear = e.currentYear;
        $.ajax({
          url: "scheduler/holidays/holidays",
          type: 'GET',
          data: {
            year: currentYear
          },
          success: function (dataSource) {
            if (dataSource != '') {
              var data = JSON.parse(dataSource);
              data instanceof Array ? data : [];
              if (data instanceof Array) {
                data.forEach(function (el, index, theArray) {
                  theArray[index].startDate = new Date(el.sYear, el.sMonth, el.sDay);
                  theArray[index].endDate = new Date(el.eYear, el.eMonth, el.eDay);
                });
              } else {
                data = [];
              }
            }
            $(e.target).data('calendar').setDataSource(data);
          }
        });
      },
      customDataSourceRenderer: function (elt, date, events) {
        var weight = 0;
        if (events.length == 1) {
          weight = 4;
        }
        else if (events.length <= 3) {
          weight = 2;
        }
        else {
          elt.parent().css('box-shadow', 'inset 0 -4px 0 0 black');
        }
        if (weight > 0) {
          var boxShadow = '';
          for (var i = 0; i < events.length; i++) {
            if (boxShadow != '') {
              boxShadow += ",";
            }
            boxShadow += 'inset 0 -' + (parseInt(i) + 1) * weight + 'px 0 0 ' + events[i].color;
            if (events[i].hType == '3') {
              elt.parent().css('font-weight', 'bold');
              elt.parent().css('font-size', '14px');
              elt.parent().css('color', 'red');
            }
          }
          elt.parent().css('box-shadow', boxShadow);
        }
      }
    });
  });

</script>