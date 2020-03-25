class CQuestion {
  constructor(config, pObj) {
    this.mainParent = pObj;
    this.id = +config.id;
    this.title = config.title;
    this.titleEx = config.title_ex;
    this.order = +config.order;                                          // приведение к int
    this.oldOrder = +config.oldOrder;
    this.limit = +config.limit;
    this.visible = +config.visible;
    this.answers = config.answers;
    this.numberOfAnswers = config.answers;
    this.renderQuestionListTmpl();
    this.renderQuestionGridTmpl();
    // this.renderQuestionTmplEx();

    this.HIDE_QUESTION_URL = '/polls/control/construct/hide-to-fill';
    this.RESTORE_ANSWER_URL = '/polls/control/construct/restore-question';
    this.LIMIT_QUESTION_URL = '/polls/control/construct/set-question-limit';
    this.REORDER_ANSWERS_URL = '/polls/control/construct/reorder-answers';
  }

  set answers(answers) {
    let Obj = this;
    let id = this.id;
    let tempAnswersArray = {};
    answers.forEach(function (val, index) {
      tempAnswersArray[val.id] = new CAnswer(val, index, Obj);
    });
    this._answers = tempAnswersArray;
  }

  get answersEx() {
    return this._answers;
  }

  get answers() {
    let tempArray = [];
    let index = 0;
    for (let key in this._answers) {
      tempArray[index] = this._answers[key];
      index++;
    }
    this.sortByOrder(tempArray);
    return tempArray;
  }

  set numberOfAnswers(answers) {
    this._numberOfAnswers = answers.length;
  }

  get numberOfAnswers() {
    return this._numberOfAnswers;
  }

  setQuestionLimit(value) {
    let oldVal = this.limit;                                          // + - приведение к типу number
    let Obj = this;
    if (+value === oldVal) return;
    let url = this.LIMIT_QUESTION_URL;
    let qId = this.id;
    let tmpl = this.questionListTmpl;
    let limitInput = tmpl.querySelector('.question-limit');
    let titleNode = tmpl.querySelector('.question-header');
    $.ajax({
      url: url,
      method: 'post',
      data: {
        id: qId,
        limit: +value
      }
    }).done(function (response) {
      if (!response.code) {
        limitInput.value = oldVal;
        console.log(response.data.message);
        return;
      }
      Obj.limit = +value;
      if (Obj.limit > 1) {
          titleNode.classList.add('be-attention');
      } else {
        titleNode.classList.remove('be-attention');
      }
    }).fail(function () {
      limitInput.value = oldVal;
      console.log('Failed to hide question');
    });
  }

  renderQuestionListTmpl() {
    let Obj = this;
    let mainQuestionDiv = document.getElementById('question-main-template');
    let questionClone = mainQuestionDiv.cloneNode(true);
    questionClone.dataset.id = this.id;
    questionClone.removeAttribute('id');
    if (this.limit > 1 || this.limit === 0) {
      questionClone.querySelector('.question-header').classList.add('be-attention');
    }
    // меню
    let questionMenu = questionClone.querySelectorAll('.question-menu')[0];
    let addNewAnswerNode = questionMenu.querySelector('#add-new-answer');
    addNewAnswerNode.addEventListener('click', () => { Obj.addNewAnswer(); }, false);

    questionClone.querySelector('.original-question-order').innerHTML = this.oldOrder;
    questionClone.querySelector('.question-title').innerHTML = this.title;
    questionClone.querySelector('.question-limit').value = this.limit;

    // лимит ответов
    let limitNode = questionClone.querySelector('.question-limit');
    limitNode.addEventListener('click', Obj.ttt, false);
    limitNode.addEventListener('paste', () => { return; }, false);
    limitNode.addEventListener('blur', (e) => { Obj.setQuestionLimit(e.target.value); }, false);

    // скрыть вопрос
    let hideBtn = questionClone.querySelector('.question-hide');
    hideBtn.addEventListener('click', () => { Obj.hideQuestion(); }, false);

    // восстановить вопрос
    let restoreBtn = questionClone.querySelector('.restore-question');
    restoreBtn.addEventListener('click', () => { Obj.restoreQuestion(); }, false);

    if (this.visible === 0) {
      questionClone.querySelector('.question-hide').style.display = 'none';
      questionClone.querySelector('.restore-question').style.display = 'inline';
    } else {
      questionClone.querySelector('.question-hide').style.display = 'inline';
      questionClone.querySelector('.restore-question').style.display = 'none';
    }

    let answers = this.answers;
    let answerContentNode = questionClone.querySelector('.answers-content');
    let answerContentDelNode = questionClone.querySelector('.answers-content-ex');
    let visCount = 1, skipCount = 1, answerNode;
    answers.forEach(function (answer, index) {
      if (answer.visible === 1) {
        answerNode = answer.renderCAnswer(visCount);
        visCount++;
        answerContentNode.appendChild(answerNode);
      }
    });
    for (let key in answers) {
      if (answers[key].visible === 0) {
        let hr = document.createElement('hr');
        answerContentDelNode.appendChild(hr);
        break;
      }
    }
    answers.forEach(function (answer, index) {
      if (answer.visible === 0) {
        answerNode = answer.renderCAnswer(skipCount);
        skipCount++;
        answerContentDelNode.appendChild(answerNode);
      }
    });
    this.sortable = new Sortable(answerContentNode, {
      multiDrag: true,
      selectedClass: 'selected',
      animation: 150,
      onUpdate: function (evt) {
        NProgress.start();
        let newOrder = Obj.sortable.toArray();
        Obj.saveAnswersReorder(newOrder);
        let items = evt.from.children;
        for (let i = 0, child; child = items[i]; i++) {
          child.querySelector('.answer-number').innerHTML = (i + 1);
        }
      }
    });
    this.hSortable = new Sortable(answerContentDelNode, {
      selectedClass: 'selected',
      animation: 150,
      sort: false
    });
    this._questionListTmpl = questionClone;
  }

  ttt() {
    $.mask.definitions['H'] = '[1-9]';
    $.mask.definitions['h'] = '[0-9]';
    $(this).mask('H?h', {
      placeholder: ' '
    });
  }

  saveAnswersReorder(newOrder) {
    let url = this.REORDER_ANSWERS_URL;
    let Obj = this;
    let sortable = this.sortable;
    $.ajax({
      url: url,
      method: 'post',
      data: {
        answers: newOrder
      }
    }).done(function (response) {
      if (!response.code) {
        let oldOrder = Obj._oldOrder;
        sortable.sort(oldOrder);                                                         // восстанавливаем порядок
        Obj.pasteOldNum(sortable);
        NProgress.done();
        var tText = '<span style="font-weight: 600">Что-то пошло не так!</span><br>Изменить порядок не удалось';
        initNoty(tText, 'warning');
        console.log(response.data.message + ' ' + response.data.data);
        return;
      }
      NProgress.done();
    }).fail(function () {
      let oldOrder = Obj._oldOrder;
      sortable.sort(oldOrder);                                                          // восстанавливаем порядок
      Obj.pasteOldNum(sortable);
      NProgress.done();
      var tText = '<span style="font-weight: 600">Что-то пошло не так!</span><br>Изменить порядок не удалось';
      initNoty(tText, 'warning');
      console.log('Не удалось получить ответ сервера. Примените отладочную панель, оснаска "Сеть"');
    });

  }

  pasteOldNum(obj) {
    let items = obj.el.children;
    for (let i = 0, child; child = items[i]; i++) {
      child.querySelector('.question-order').innerHTML = (i + 1);
    }
  }

  reindex() {
    let sortDiv = this.sortable.el;
    let answersArray = sortDiv.getElementsByClassName('answer-number');
    Array.prototype.map.call(answersArray, function (span, index) {
      span.textContent = ++index + '';
    });
    let hSortDiv = this.hSortable.el;
    let answersArrayEx = hSortDiv.getElementsByClassName('answer-number');
    if (answersArrayEx.length !== 0) {
      Array.prototype.map.call(answersArrayEx, function (span, index) {
        span.textContent = ++index + '';
      });
    } else {
      let hr = hSortDiv.getElementsByTagName('hr')[0];
      hr.remove();
    }
  }

  // пересортировка по коду ответа
  resort() {
    let answers = this.answers;
    let ar = [];
    this.sortByCode(answers);
    answers.forEach(function (answer, index) {
      ar[index] = answer.id;
    });
    let sortable = this.sortable;
    sortable.sort(ar);
    this.reindex();
  }

  renderQuestionTmplEx() {
    let mainQuestionDiv = document.getElementById('question-tmpl-ex');
    let questionClone = mainQuestionDiv.cloneNode(true);
    questionClone.dataset.id = this.id;
    questionClone.removeAttribute('id');
    questionClone.querySelector('.q-title').innerHTML = this.title;
    questionClone.querySelector('.q-order').innerHTML = this.order + '.';

    let answers = this.answers;
    let qNode = questionClone.querySelector('.question-content-ex');
    answers.forEach(function (answer, index) {
      if (answer.visible === 1) {
        let answerNode = answer.answerTmplEx;
        qNode.appendChild(answerNode);
      }
    });
    this.tempTmpl = questionClone;
    return questionClone;
  }

  get questionTmplEx() {
    // return this._questionTmplEx;
    return this.renderQuestionTmplEx();
  }

  renderQuestionGridTmpl() {
    let gridItem = document.getElementById('gridview-template');
    if (this.visible) {
      let gridItemClone = gridItem.cloneNode(true);
      gridItemClone.removeAttribute('id');
      gridItemClone.dataset.id = this.id;
      if (this.limit !== 1) {
        gridItemClone.classList.add('multiple-answers');
      }
      gridItemClone.querySelector('.question-order').innerHTML = this.oldOrder;
      gridItemClone.querySelector('.question-title').innerHTML = this.title;
      this._questionGridTmpl = gridItemClone;
      return;
    }
    this._questionGridTmpl = null;
  }

  addNewAnswer() {
    console.log(this.id);
  }

  hideQuestion() {
    let Obj = this;
    let pObj = this.mainParent;
    let url = this.HIDE_QUESTION_URL;
    let questionId = this.id;
    $.ajax({
      url: url,
      method: 'post',
      data: {
        id: questionId
      }
    }).done(function (response) {
      if (response.code) {
        let hSortDiv = pObj.hSortable.el;
        let sortDiv = pObj.sortable.el;
        if (hSortDiv.getElementsByTagName('hr').length === 0) {
          let hr = document.createElement('hr');
          hSortDiv.appendChild(hr);
        }
        let tmpl = Obj.questionListTmpl;
        tmpl.querySelector('.question-hide').style.display = 'none';
        tmpl.querySelector('.restore-question').style.display = 'inline';
        hSortDiv.appendChild(tmpl);
        setTimeout(() => pObj.reindex(), 300);
      } else {
        console.log(response.data.message + '\n' + response.data.data);
      }
    }).fail(function () {
      console.log('Failed to hide question');
    });
  }

  restoreQuestion() {
    let Obj = this;
    let pObj = this.mainParent;
    let url = this.RESTORE_ANSWER_URL;
    let questionId = this.id;
    $.ajax({
      url: url,
      method: 'post',
      data: {
        id: questionId
      }
    }).done(function (response) {
      if (response.code) {
        let hSortDiv = pObj.hSortable.el;
        let sortDiv = pObj.sortable.el;
        if (hSortDiv.getElementsByTagName('hr').length === 0) {
          let hr = document.createElement('hr');
          hSortDiv.appendChild(hr);
        }
        let tmpl = Obj.questionListTmpl;
        tmpl.querySelector('.restore-question').style.display = 'none';
        tmpl.querySelector('.question-hide').style.display = 'inline';
        sortDiv.appendChild(tmpl);
        setTimeout(() => pObj.resort(), 300);
      } else {
        console.log(response.data.message + '\n' + response.data.data);
      }
    }).fail(function () {
      console.log('Failed to hide question - URL failed');
    });
  }

  findAnswerById(id) {
    let answers = this._answers;
    if (answers[id] !== undefined)
      return answers[id];
    return false;
  }

  get questionListTmpl() {
    return this._questionListTmpl;
  }

  get questionGridTmpl() {
    return this._questionGridTmpl;
  }

  renderCQuestionList(index) {
    let question = this.questionListTmpl;
    question.querySelector('.question-order').innerHTML = index;
    return question;
  }

  renderCQuestionGrid(index) {
    let question = this.questionGridTmpl;
    if (question !== null) {
      question.querySelector('.question-order').innerHTML = index;
      return question;
    }
    return false;
  }
/*
  showTrash() {
    let hiddenAnswers = this._hiddenAnswers;
    let content = document.createElement('div');
    for (let key in hiddenAnswers) {
      content.appendChild(hiddenAnswers[key]._answerTmpl);
    }
    $.dialog({
      title: 'Скрытые ответы',
      content: content,
    });
  }
*/
  sortByOrder(arr) {
    arr.sort((a, b) => a.order > b.order ? 1 : -1);
  }

  sortByCode(arr) {
    arr.sort((a, b) => +a.dCode > +b.dCode ? 1 : -1);
  }
}
