class CQuestion {
  constructor(config) {
    this.id = +config.id;
    this.title = config.title;
    this.titleEx = config.title_ex;
    this.newOrder = +config.order;                                          // приведение к int
    this.oldOrder = +config.order;
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
  }

  set answers(answers) {
    let id = this.id;
    let tempAnswersArray = {};
    answers.forEach(function (val, index) {
      tempAnswersArray[val.id] = new CAnswer(val, index, id);
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
    this._numberOfAnswers = answers.length
  }

  get numberOfAnswers() {
    return this._numberOfAnswers;
  }

  hideQuestion(callback) {
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
        callback();
      } else {
        console.log(response.data.message + '\n' + response.data.data);
      }
    }).fail(function () {
      console.log('Failed to hide question');
    });
  }

  restoreQuestion(callback) {
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
        callback();
      } else {
        console.log(response.data.message + '\n' + response.data.data);
      }
    }).fail(function () {
      console.log('Failed to hide question - URL failed');
    });
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
    let mainQuestionDiv = document.getElementById('question-main-template');
    let questionClone = mainQuestionDiv.cloneNode(true);
    questionClone.dataset.id = this.id;
    questionClone.removeAttribute('id');
    if (this.limit > 1 || this.limit === 0) {
      questionClone.querySelector('.question-header').classList.add('be-attention');
    }

    questionClone.querySelector('.original-question-order').innerHTML = this.newOrder;
    questionClone.querySelector('.question-title').innerHTML = this.title;
    questionClone.querySelector('.question-limit').value = this.limit;
    questionClone.querySelector('.question-limit').dataset.id = this.id;
    questionClone.querySelector('.question-limit').dataset.old = this.limit;
    questionClone.querySelector('.question-hide').dataset.id = this.id;
    questionClone.querySelector('.restore-question').dataset.id = this.id;
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
        break
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
      onEnd: function (evt) {
        let from = evt.from;
        let currentItem = evt.item;
        let items = from.children;
        for (let i = 0, child; child = items[i]; i++) {
          let oldOrder = child.dataset.old;
          child.querySelector('.answer-number').innerHTML = (i + 1);
          child.querySelector('.answer-old-order').innerHTML = oldOrder;
        }
        var tText = '<span style="font-weight: 600">Порядок не изменен!</span><br>Эта опция приостановлена';
        initNoty(tText, 'warning');
        return;
      }
    });
    this.hSortable = new Sortable(answerContentDelNode, {
      selectedClass: 'selected',
      animation: 150,
      sort: false
    });
    this._questionListTmpl = questionClone;
  }

  hideAnswer(id) {
    let Obj = this;
    let answer = this.findAnswerById(id);
    let qTmpl = this._questionListTmpl;
    let hSortDiv = Obj.hSortable.el;
    let sortDiv = Obj.sortable.el;
    if (answer) {
      answer.hideAnswerInListView(function () {
        if (hSortDiv.getElementsByTagName('hr').length === 0) {
          let hr = document.createElement('hr');
          hSortDiv.appendChild(hr);
        }
        let tmpl = answer.answerTmpl;
        tmpl.querySelector('.answer-hide').style.display = 'none';
        tmpl.querySelector('.answer-options').style.display = 'none';
        tmpl.querySelector('.unique-btn').style.display = 'none';
        tmpl.querySelector('.restore-btn').style.display = 'block';
        tmpl.querySelector('.restore-btn').dataset.id = answer.id;
        tmpl.querySelector('.restore-btn').dataset.questionId = Obj.id;
        tmpl.classList.add('hidden-answer');
        hSortDiv.appendChild(tmpl);
        setTimeout(() => Obj.reindex(), 300);
      });
    }
  }

  restoreAnswer(id) {
    let Obj = this;
    let answer = this.findAnswerById(id);
    if (answer) {
      answer.restoreAnswerInListView(function () {
        let sortable = Obj.sortable;
        let sortDiv = sortable.el;
        let tmpl = answer.answerTmpl;

        tmpl.querySelector('.answer-hide').style.display = 'inline';
        tmpl.querySelector('.answer-options').style.display = 'inline';
        tmpl.querySelector('.unique-btn').style.display = 'inline';
        tmpl.querySelector('.restore-btn').style.display = 'none';
        tmpl.classList.remove('hidden-answer');

        sortDiv.appendChild(tmpl);
        let ar = sortable.toArray();
        ar.push(answer.id + '');
        sortable.sort(ar);
      });
      setTimeout(() => Obj.resort(), 300);
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
    questionClone.querySelector('.q-order').innerHTML = this.newOrder + '.';

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

  sortByOrder(arr) {
    arr.sort((a, b) => +a.oldOrder > +b.oldOrder ? 1 : -1);
  }

  sortByCode(arr) {
    arr.sort((a, b) => +a.dCode > +b.dCode ? 1 : -1);
  }

}
