// событие клавиатуры (keyup, keydown)
// const MAIN_INPUT_TYPE = 'keydown';
const MAIN_INPUT_TYPE = 'keyup';
const MAIN_CLICK_TYPE = 'click';

const TYPE_COMMON_ANSWER = 1;
const TYPE_FREE_ANSWER = 2;
const TYPE_DIFFICULT_ANSWER = 3;

const RESULTS_SAVE_URL = 'polls/drive-in/save-result';

// основные обработчики событий
$(document).on('click', '.answer-p', clickOnTheAnswer)
  .on('keydown', '.previous-btn', moveToPreviousQuestion)
  .on('keydown', '.next-btn', moveToNextQuestion)
  .on('change', '.navigation-select', goToQuestion)
  .on('click', '.confirm-next-btn', confirmAndNextQuestion)
  .on('click', '.mobile-previous-btn', moveToPreviousQuestion)
  .on('click', '.mobile-next-btn', moveToNextQuestion);

// события поля со свободными ответами
$(document).on('focus', '.free-answer', function () {
  let body = document.body;
  body.removeEventListener(MAIN_INPUT_TYPE, keycodeAbstractionLayer);
}).on('blur', '.free-answer', function (event) {
  let body = document.body;
  body.addEventListener(MAIN_INPUT_TYPE, keycodeAbstractionLayer);
  $(document).on('click', '.answer-p', clickOnTheAnswer);
  saveFreeAnswer(this);
});

// события select2
$(document).on('select2:open', '.js-data-array', select2Start)
  .on('change', '.js-data-array', select2SaveChanges)
  .on('select2:close', '.js-data-array', select2Close);

function startDrive() {
  showTownIn(this, letsDrive);
}

function letsDrive(p) {
  let pollId = $(p).data('id');
  let url = '/polls/drive-in?id=';
  loadExContentEx(url, () => loadPollData(pollId, driveIn));
}

function loadPollData(id, callback) {
  let url = '/polls/drive-in/get-poll-info?id=' + id;
  $.ajax({
    url: url,
    method: 'get'
  }).done(function (response) {
    if (response.code) {
      callback(response.data.data[0]);
    } else {
      console.log(response.data.message);
    }
  }).fail(function () {
    console.log('Failed to load poll config');
  });
}

function driveIn(config) {
  NProgress.start();
  let settings = {
    id: 123456789,                                                                // user id
    stepDelay: 100,
    markColor: '#e0e0e0',
    code: 1
  };
  pollUser = new PollUser(settings);
  poll = new Worksheet(config, driveTownId, driveTownTitle);
  initMainEventListener();
  $('#poll-title').append('<h4>' + poll.code + '</h4>');                          // наименование опроса
  $('#poll-town').append('<h4>' + driveTownTitle + '</h4>');                          // наименование опроса
  poll.goToQuestionByNumber(0);
  poll.respondent.startCount();
  NProgress.done();
}

function renderTownsSelect() {
  let towns = pollTowns;
  let wrapDiv = document.createElement('div');
  let divNode = document.createElement('div');
  let label = document.createElement('label');
  label.className = 'control-label';
  label.innerText = 'Укажите город:';
  let townSelect = document.createElement('select');
  townSelect.className = 'form-control drive-town-select';
  let length = towns.length;
  for (let i = 0; i < length; i++) {
    let town = towns[i];
    let option = document.createElement('option');
    option.text = town.name;
    option.value = town.id;
    townSelect.add(option, i);
  }
  wrapDiv.appendChild(townSelect);
  return wrapDiv.outerHTML;
}

function showTownIn(p, callback) {
  let jc = $.confirm({
    escapeKey: 'cancel',
    title: 'Город',
    content: renderTownsSelect(),
    type: 'red',
    closeIcon: false,
    onContentReady: function () {
      let self = this;
      this.buttons.ok.disable();
      this.$content.find('.drive-town-select').change(function () {
        driveTownId = self.$content.find('select :selected').val();
        driveTownTitle = self.$content.find('select :selected').text();
        self.buttons.ok.enable();
      })
    },
    buttons: {
      ok: {
        btnClass: 'btn-success',
        action: function () {
          callback(p);
          jc.close();
        }
      },
      cancel: {
        action: function () {
        }
      }
    }
  });
}

// Основной обработчик запросов
function initMainEventListener() {
  let body = document.body;
  body.addEventListener(MAIN_INPUT_TYPE, keycodeAbstractionLayer);
}

function select2Start() {
  let body = document.body;
  body.removeEventListener(MAIN_INPUT_TYPE, keycodeAbstractionLayer);
}

function select2Close() {
  let body = document.body;
  body.addEventListener(MAIN_INPUT_TYPE, keycodeAbstractionLayer);
}

function select2SaveChanges(e) {
  let sOption = e.target.selectedOptions;
  let question = poll.getCurrentQuestion();
  let selectedAnswerId = sOption[0].value;
  let respondentResult = poll.respondent.getRespondentResultsOfQuestion(question.id);
  let selectedAnswerObject = question.getAnswer(selectedAnswerId);
  let data = {
    id: 0,
    code: null,
    extData: null,
    unique: 0,
    order: null
  };
  data.id = selectedAnswerId;
  data.unique = selectedAnswerObject.unique;
  data.code = selectedAnswerObject.code;
  data.order = selectedAnswerObject.order;
  respondentResult.saveData(data);
  stepLogic(respondentResult, question);
}

// приведение кодов дополнительной клавиатуры к кодам освновной
function cast(keyCode) {
  if (castCodes[keyCode] !== undefined) return castCodes[keyCode];
  return keyCode;
}

function keycodeAbstractionLayer(event) {
  let keyCode = event.keyCode;
  keyCode = cast(keyCode);                                               // приведение к одому коду
  if (document.getElementById(keyCode)) {
    let element = document.getElementById(keyCode);
    chooseAnAnswer(element);
  } else if (isInArray(keyCode, confirmBtns)) {                          // нажат 0 - как ДАЛЕЕ
    confirmAndNextQuestion();
  } else if (isInArray(keyCode, serviceBtns)) {
    if (keyCode === 37) {
      moveToPreviousQuestion();
    } else if (keyCode === 39) {
      moveToNextQuestion();
    } else {
      return;
    }
  } else {
    beep();
  }
}

function stepLogic(result, question) {
  if (result.entries >= question.limit) {
    setTimeout(() => confirmAndNextQuestion(), pollUser.stepDelay);
  }
}

function chooseAnAnswer(element) {
  // если ответ запрещен для выбора по логике
  if (element.dataset.skip == 1) {
    beep();
    return;
  }
  let question = poll.getCurrentQuestion();
  let respondentResult = poll.respondent.getRespondentResultsOfQuestion(question.id);
  let results = respondentResult.respondentAnswers;
  let selectedAnswerId = element.dataset.id;
  let selectedAnswerObject = question.getAnswer(selectedAnswerId);
  let data = {
    id: selectedAnswerId,
    code: selectedAnswerObject.code,
    extData: null,
    unique: selectedAnswerObject.unique,
    order: selectedAnswerObject.order
  };

  if (respondentResult.hasSavedData()) {
    // проверка уникальности
    console.log(selectedAnswerObject.unique);
    if (selectedAnswerObject.unique || respondentResult.hasUniqueAnswers()) {
      if (!respondentResult.alreadySaved(selectedAnswerObject.id)) {
        let audio = new Audio('lib/chord.mp3');
        audio.play();
        return;
      }
    }

    //проверка превышения лимита
    if (respondentResult.entries === +question.limit) {
      if (results[selectedAnswerId] === undefined) {
        beep();
        return;
      }
    }
  }

  if (selectedAnswerObject.type === TYPE_FREE_ANSWER) {
    // отключить событие
    $(document).off('click', '.answer-p', clickOnTheAnswer);
    if (selectedAnswerObject.inputSpan === undefined) {
      selectedAnswerObject.mark();
      selectedAnswerObject.insertInput();
    } else if (selectedAnswerObject.inputSpan.dataset.show == 0) {
      selectedAnswerObject.mark();
      selectedAnswerObject.showInput();
    } else {
      selectedAnswerObject.hideInput();
      selectedAnswerObject.unmark();
      respondentResult.deleteData(selectedAnswerId);
    }
    return;
  }

  if (respondentResult.respondentAnswers[selectedAnswerId] !== undefined) {
    // let logic = poll.respondent.logic;
    // delete logic[selectedAnswerId];

    // очень прлохой момент!!

    poll.respondent.removeFromLogic(selectedAnswerId);
    selectedAnswerObject.unmark();
    respondentResult.deleteData(selectedAnswerId);
  } else {
    let logicTmpl = poll.logic;
    poll.respondent.setLogic(logicTmpl[selectedAnswerId], selectedAnswerId);
    selectedAnswerObject.mark();
    respondentResult.saveData(data);
    stepLogic(respondentResult, question);
  }
}

function saveFreeAnswer(input) {
  let question = poll.getCurrentQuestion();
  let selectedAnswerId = input.dataset.id;
  let selectedAnswerObject = question.getAnswer(selectedAnswerId);
  let respondentResult = poll.respondent.getRespondentResultsOfQuestion(question.id);
  let data = {
    id: selectedAnswerId,
    code: null,
    extData: null,
    unique: 0,
    order: null
  };
  if (input.value) {
    data.id = selectedAnswerId;
    data.extData = input.value;
    data.unique = selectedAnswerObject.unique;
    data.code = selectedAnswerObject.code;
    data.order = selectedAnswerObject.order;
    respondentResult.saveData(data);
    stepLogic(respondentResult, question);
  } else {
    selectedAnswerObject.hideInput();
    selectedAnswerObject.unmark();
    respondentResult.deleteData(selectedAnswerId);
  }
}

function nextRespondent() {
  poll.respondent.stopCount();
}

function clickOnTheAnswer(event) {
  let element = event.currentTarget;
  if (element.classList.contains('free-answer')) return;
  chooseAnAnswer(element);
}

function confirmAndNextQuestion() {
  let question = poll.getCurrentQuestion();
  let respondentResult = poll.respondent.getRespondentResultsOfQuestion(question.id);
  if (respondentResult.entries >= 1) {
    if (poll.isPollComplete()) {
      showM();
      return;
    }
    if (poll.isLastQuestion()) {
      let qId = poll.unansweredQuestion();
      let question = poll.getQuestionById(qId);
      if (question) {
        console.log(question.number);
        poll.goToQuestionByNumber(question.number);
      } else {
        alert('Есть вероятность потери данных при вносе результатов. Обратитесь к разработчику');
        return;
      }
    }
    poll.nextQuestion();
  } else {
    beep();
  }
}

function showM() {
  document.body.removeEventListener(MAIN_INPUT_TYPE, keycodeAbstractionLayer);
  let jc = $.confirm({
    icon: 'fa fa-question',
    escapeKey: 'cancel',
    title: 'Анкета завершена',
    content: 'Прейти к следующей?',
    type: 'red',
    closeIcon: false,
    autoClose: 'cancel|9000',
    buttons: {
      ok: {
        btnClass: 'btn-danger',
        action: function () {
          jc.close();
          saveDataToDb();
          poll.nextRespondent();
          document.body.addEventListener(MAIN_INPUT_TYPE, keycodeAbstractionLayer);
        }
      },
      cancel: {
        action: function () {
          document.body.addEventListener(MAIN_INPUT_TYPE, keycodeAbstractionLayer);
          return;
        }
      }
    }
  });
}

function saveDataToDb() {
  // console.log(poll.respondent.resultPool);
  // console.log(poll.respondent.getCodesResults());
  // console.log(poll.respondent.getResultToDb());

  let result = {};
  result.pollId = poll.pollId;
  result.respId = poll.respondent.id;
  let data = poll.respondent.getResultToDb();
  console.log(poll.respondent.getResultToDb());
  console.log(poll.respondent.getCodesResults());

  let url = RESULTS_SAVE_URL;
  $.ajax({
    url: url,
    method: 'post',
    data: {
      pollId: poll.pollId,
      respId: poll.respondent.id,
      townId: poll.townId,
      data: data
    }
  }).done(function (response) {
    if (!response.code) {
      var tText = '<span style="font-weight: 600">Что-то пошло не так!</span><br>Сохранить результат удалось';
      initNoty(tText, 'warning');
      console.log(response.data.message + ' ' + response.data.data);
      return;
    }

    var tText = '<span style="font-weight: 600">Успех!</span><br>Результат сохранен';
    initNoty(tText, 'success');
  }).fail(function () {
    var tText = '<span style="font-weight: 600">Что-то пошло не так!</span><br>Сохранить результат удалось';
    initNoty(tText, 'warning');
    console.log('Не удалось получить ответ сервера. Примените отладочную панель, оснаска "Сеть"');
  });


}

function moveToPreviousQuestion() {
  if (poll.isFirstQuestion()) return;
  poll.previousQuestion();
}

function moveToNextQuestion() {
  if (poll.isLastQuestion()) return;
  poll.nextQuestion();
}

function respondentFinish() {
  let finishNotice = '<p>КОНЕЦ!!!</p>';
  $('.drive-content .panel').append(finishNotice);
}

function goToQuestion(event) {
  let option = event.currentTarget.selectedOptions[0];
  let questionNum = $(option).data('key');
  if (questionNum === -1) {
    poll.goToLastQuestion();
    return;
  }
  poll.goToQuestionByNumber(questionNum);
}

// =================== вспомогательные функции =====================

function isInArray(value, array) {
  return array.indexOf(value) > -1;
}

function beep(config) {
  let audioCtx = new (window.AudioContext || window.webkitAudioContext)();
  config = Object.assign({
    volume: 25 / 100,
    frequency: 3020,
    duration: 150,
    type: 3
  }, config);

  let oscillator = audioCtx.createOscillator();
  let gainNode = audioCtx.createGain();
  oscillator.connect(gainNode);
  gainNode.connect(audioCtx.destination);
  gainNode.gain.value = config.volume;
  oscillator.frequency.value = config.frequency;
  oscillator.type = config.type;
  oscillator.start();
  setTimeout(
    function () {
      oscillator.stop();
    },
    config.duration
  );
}

function play_single_sound() {
  document.getElementById('audiotag1').play();
}