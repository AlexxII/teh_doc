function startConstruct(view) {
  let headerNode = document.getElementById('control-header');
  headerNode.innerHTML = '';
  let resultNode = document.getElementById('control-result');
  resultNode.innerHTML = '';

  if (view === 'list-view') {
    renderListView();
  } else {
    renderGridView();
  }
  NProgress.done();
}

$(document)
  // .on('click', '.question-trash', showQTrash)
  .on('click', '.logic', setLogic)
  .on('click', '.check-all', checkboxLogicEx);


function renderListView() {
  $('#control-result').html('').append(pollCounstructor.renderListView());
}

function renderGridView() {
  $('#control-result').html('').append(pollCounstructor.renderGridView());
}

/*
function hideQuestion() {
  let questionId = $(this).data('id');
  pollCounstructor.hideQuestionInListView(questionId);
}

function restoreQuestion() {
  let questionId = $(this).data('id');
  pollCounstructor.restoreQuestionInListView(questionId);
}

function hideAnswer() {
  let answerId = $(this).data('id');
  let questionId = $(this).data('questionId');
  let question = pollCounstructor.findQuestionById(questionId);
  if (question) {
    question.hideAnswer(answerId);
  }
}

function restoreAnswer() {
  let answerId = $(this).data('id');
  let questionId = $(this).data('questionId');
  let question = pollCounstructor.findQuestionById(questionId);
  if (question) {
    question.restoreAnswer(answerId);
  }
}

function setAnswerUnique() {
  let id = $(this).data('id');
  let answerId = $(this).data('id');
  let questionId = $(this).data('questionId');
  let question = pollCounstructor.findQuestionById(questionId);
  if (question) {
    let answer = question.findAnswerById(answerId);
    answer.changeUniqueForQuestion();
  }
}


function showQTrash() {
  let questionId = $(this).data('id');
  let question = pollCounstructor.findQuestionById(questionId);
  question.showTrash();
}

function saveQuestionLimit(input) {
  let value = input.value;
  let questionId = input.dataset.id;
  let question = pollCounstructor.findQuestionById(+questionId);
  question.setQuestionLimit(value);
}
*/


function setLogic() {
  let questionId = this.dataset.question;
  let answerId = this.dataset.id;
  pollCounstructor.showLogicMenu(questionId, answerId);
}

function checkInAllCheckboxes() {
  let inputs = menu.getElementsByTagName('input');
  for (let key in inputs) {
    inputs[key].checked = true;
  }
}

function checkboxLogicEx() {
  let li = this.parentNode;
  let inputs = li.getElementsByTagName('input');
  for (let key in inputs) {
    inputs[key].checked = !inputs[key].checked;
  }
}

// отключение сортировки
function switchOrder() {
  let sortable = pollCounstructor.sortable;
  sortable.option('sort', false);
}
