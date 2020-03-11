class BQuestion {
  constructor(config) {
    this.id = +config.id;
    this.title = config.title;
    this.order = +config.order;                                          // приведение к int
    this.limit = +config.limit;
    this.visible = +config.visible;
    this.answers = config.answers;
    this.renderQuestionListTmpl();
  }

  set answers(answers) {
    let id = this.id;
    let tempAnswersArray = {};
    answers.forEach(function (val, index) {
      tempAnswersArray[val.code] = new BAnswer(val, index, id);
    });
    this._answers = tempAnswersArray;
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

  renderQuestionListTmpl() {
    let mainQuestionDiv = document.getElementById('question-batch-template');
    let questionClone = mainQuestionDiv.cloneNode(true);
    questionClone.dataset.id = this.id;
    questionClone.removeAttribute('id');
    questionClone.querySelector('.question-order').innerHTML = this.order;
    if (this.limit > 1 || this.limit === 0) {
      questionClone.querySelector('.question-header').classList.add('be-attention');
    }
    questionClone.querySelector('.question-title').innerHTML = this.title;
    questionClone.querySelector('.question-limit').innerHTML = this.limit;

    let answers = this.answers;
    let answerContentNode = questionClone.querySelector('.answers-content');
    let answerContentDelNode = questionClone.querySelector('.answers-content-ex');
    let visCount = 1, skipCount = 1, answerNode;
    answers.forEach(function (answer, index) {
      if (answer.visible === 1) {
        answerNode = answer.renderBAnswer(visCount);
        visCount++;
        answerContentNode.appendChild(answerNode);
      }
    });
    this._questionListTmpl = questionClone;
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

  renderBQuestionList() {
    return this.questionListTmpl;
  }

  sortByOrder(arr) {
    arr.sort((a, b) => +a.order > +b.order ? 1 : -1);
  }

}
