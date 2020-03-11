<?php

use app\assets\AppAsset;

AppAsset::register($this);            // регистрация ресурсов всего приложения

?>
<div>
  <button id="test-it" class="btn btn-sm btm-success">Старт</button>
  <div id="result">

  </div>
</div>
<script>

  var to = {
    'title': {                          // Тип шаблона (int)
      '1': 'Круглосуточный'
    },
    'days': {                           // день недели
      '1': [1, 2, 3, 4, 5, 6, 7]
    },
    'hours': {                          // Сколько часов прибавлять (продолжительность рабочего дня)
      '1': 24
    },
    'holidays': {                       // периоды или точки не подсчета
      '1': {
        'start': '12.12.2012',
        'end': '13.01.2013'
      },
      '2': {
        'start': '10.12.2012',
        'end': '10.12.2012'
      }
    },
    'sessions': {                       // id оборудования, которое участвует в сеансах связи
      '1': {
        'eq_id': '231242342143'
      }
    },
    'to': {                             // Проведение ТО
      'date': {
        'eq_id': '12.03.2019'           // автоматически из графика ТО
      },
      'on': {
        'duration': 2		                // продолжительность включения
      },
      'off': {
        'duration': 1		                // продолжительность отключения
      }
    }
  };

  var toAllday = {
    'title': {                          // Тип шаблона (int)
      '1': 'Круглосуточный'
    },
    'days': {                           // день недели
      '1': [1, 2, 3, 4, 5, 6, 7]
    },
    'hours': {                          // Сколько часов прибавлять (продолжительность рабочего дня)
      '1': 24
    },
    'to': {                             // Проведение ТО
      'date': {
        'eq_id': '12.03.2019'           // автоматически из графика ТО
      },
      'off': {
        'duration': 1		                // продолжительность отключения
      }
    }
  };

  var toAlldayEx = {
    'days': [1, 2, 3, 4, 5, 6, 7],
    'hours': 24,
    'to': {
      'eq_id': 1234123412313,
      'off': 1
    },
  };

  var toWorkDay = {
    'days': [1, 2, 3, 4, 5],
    'hours': 8,
    'holidays': {
      1: ['2019-11-12', '2019-12-13'],
      2: ['2019-10-24', '2019-10-24']
    },
    'to': {
      'eq_id': 34346245234,
      'on': 2
    }
  };

  var toSessions = {
    'to': {
      'eq_id': 34346245234,
      'on': 1
    },
    'sessions': '231242342143'
  };




  $(document).on('click', '#test-it', function (e) {
    testIt(toAlldayEx);
    testIt(toWorkDay);
    testIt(toSessions);
    $('#result').html('');
  });


  function testIt(data) {
    var url = '/study/default/count';
    var csrf = $('meta[name=csrf-token]').attr("content");
    $.ajax({
      type: 'POST',
      url: url,
      dataType: 'json',
      data: {
        _csrf: csrf,
        data: data
      },
      success: function (response) {
        $('#result').append(response.data.data);
        $('#result').append('<br>');
      },
      error: function (response) {
        var nTest = ' Что-то пошло не так';
        console.log(response.data.data);
        initNoty(nTest, 'error');
      }
    });
  }


</script>