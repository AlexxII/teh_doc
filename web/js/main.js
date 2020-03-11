function initLeftMenu(url) {
  $.ajax({
    type: 'GET',
    url: url,
    success: function (response) {
      $('#left-side').html(response);
      $('#push-it').removeClass('hidden');
    },
    error: function (response) {
      console.log('left menu failed')
    }
  });
}

function initSmallMenu(url) {
  $.ajax({
    type: 'GET',
    url: url,
    success: function (response) {
      $('.mobile-navigation').html(response);
    },
    error: function (response) {
      console.log('small menu failed')
    }
  });
}

function initAppConfig(url) {
  $.ajax({
    type: 'GET',
    url: url,
    success: function (response) {
      $('#app-control-ul').html(response);
      $('#app-control').removeClass('hidden');
    },
    error: function (response) {
      console.log('appConfig menu failed')
    }
  });
}

function initLeftCustomData(url) {
  $.ajax({
    type: 'GET',
    url: url,
    success: function (response) {
      $('#left-custom-data').html(response);
    },
    error: function (response) {
      console.log('leftCustomData load failed')
    }
  });
}

function initRightCustomData(url) {
  $.ajax({
    type: 'GET',
    url: url,
    success: function (response) {
      $('#right-custom-data').html(response);
    },
    error: function (response) {
      console.log('rightCustomData load failed')
    }
  });
}

function clickMenu() {
  if ($(window).width() >= 900) {
    if ($('#left-side').css('left') == '0px') {
      closeSlider();
    } else {
      openSlider();
    }
  }
}

/*
function clickMenu() {
  if ($(window).width() >= 900) {
    if ($('#left-side').css('left') == '0px') {
      closeSlider();
    } else {
      openSlider();
    }
  } else {
    openMenu();
  }
}
*/

function openSlider() {
  $('#add-session-wrap').hide();
  var left = 275 - $('#main-content').offset().left;
  $('#left-side').css('width', '2px');
  $('#left-side').animate({left: '0px'}, {queue: false, duration: 500});
  $('#main-content').animate({paddingLeft: left + 'px'}, {queue: false, duration: 500});
}

function closeSlider() {
  $('#left-side').css('width', '275px');
  $('#left-side').animate({left: '-280px'}, {queue: false, duration: 500});
  $('#main-content').animate({paddingLeft: '0px'}, {queue: false, duration: 500});
  $('#add-session-wrap').show();
}

// Attach function to event
/*
$( controller.events ).on( 'opening', function ( event, id ) {
   console.log( 'Slidebar ' + id + ' is opening.' );
});
*/

var controller;


function openMenu() {
  event.stopPropagation();
  event.preventDefault();
  controller = new slidebars();
  controller.init();
  controller.toggle('main-menu');
  $('#app-wrap').bind('click', closeSmallMenu).addClass('pointer');
}

// $(document).on('click', '#app-wrap', function (e) {
//   closeSmallMenu();
// });

function closeSmallMenu(e) {
  $('#app-wrap').off('click', closeSmallMenu).removeClass('pointer');
  controller.toggle('main-menu');
}

$(window).resize(function () {
    closeSlider();
});

function loadExContent(url, backUrl, jc) {
  $.ajax({
    url: url + '?back-url=' + backUrl,
    method: 'get'
  }).done(function (response) {
    $('#app-wrap').append('<div id="ex-wrap">' + response.data.data + '</div>');
    exData = response.data.message;
    jc.close();
    // window.history.pushState("object or string", "Title", uri);
  }).fail(function () {
    jc.close();
    console.log('Failed to load ex-content. Main.js');
  });
}

function loadExContentEx(url, callback) {
  $.ajax({
    url: url,
    method: 'get'
  }).done(function (response) {
    $('#app-wrap').append('<div id="ex-wrap">' + response.data.data + '</div>');
    callback();
    // window.history.pushState("object or string", "Title", uri);
  }).fail(function () {
    console.log('Failed to load ex-content. Main.js');
  });
}

function loadControls(e) {
  e.preventDefault();
  var url = $(this).data('url');
  var title = $(this).data('title');
  var windowSize = $(this).data('wsize');
  c = $.confirm({
    content: function () {
      var self = this;
      return $.ajax({
        url: url,
        method: 'get'
      }).fail(function () {
        self.setContentAppend('<div>Что-то пошло не так!</div>');
      });
    },
    contentLoaded: function (data, status, xhr) {
      this.setContentAppend('<div>' + data + '</div>');
    },
    columnClass: windowSize,
    title: title,
    buttons: {
      cancel: {
        text: 'НАЗАД'
      }
    },
    onDestroy: function () {
      controlCallback();
    }
  });
}

function initNoty(text, type) {
  new Noty({
    type: type,
    theme: 'mint',
    text: text,
    progressBar: true,
    timeout: '4000',
    closeWith: ['click'],
    killer: true,
    animation: {
      open: 'animated noty_effects_open noty_anim_out', // Animate.css class names
      close: 'animated noty_effects_close noty_anim_in' // Animate.css class names
    }
  }).show();
}

var monthNames = [
  'Январь',
  'Февраль',
  'Март',
  'Апрель',
  'Май',
  'Июнь',
  'Июль',
  'Август',
  'Сентябрь',
  'Октябрь',
  'Ноябрь',
  'Декабрь'
];

var monthNamesGenitive = {
  '01': 'Января',
  '02': 'Февраля',
  '03': 'Марта',
  '04': 'Апреля',
  '05': 'Мая',
  '06': 'Июня',
  '07': 'Июля',
  '08': 'Августа',
  '09': 'Сентября',
  '10': 'Октября',
  '11': 'Ноября',
  '12': 'Декабря'
};

var monthNamesPrep = {
  '01': 'Январе',
  '02': 'Феврале',
  '03': 'Марте',
  '04': 'Апреле',
  '05': 'Мае',
  '06': 'Июне',
  '07': 'Июле',
  '08': 'Августе',
  '09': 'Сентябре',
  '10': 'Октябре',
  '11': 'Ноябре',
  '12': 'Декабре'
};