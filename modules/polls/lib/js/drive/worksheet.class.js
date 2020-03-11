class Worksheet {
  constructor(config, townId, townTitle) {
    if (this.verifyPollConfigStructure(config)) {
      this.currentConfig = config;
      this.pollId = config.id;
      this._code = config.code;
      this._title = config.title;
      this.townId = townId;
      this.townTitle = townTitle;
      this.logic = config.logic;
      // пулл вопросов
      this.totalNumberOfQuestions = config;
      this.currentQuestionNum = 0;
      this.questions = config;
      // пулл респондентов (т.е. ответов !!!!)
      this.respondent = this.questions;
      //rendering
      this.template = this.renderTemplate();
      // this.renderNavigationSelect();
      // this.renderNavigator();
    }
  }

  set pollId(id) {
    if (this.verifyId(id)) {
      this._id = id;
    }
  }

  get pollId() {
    return this._id;
  }

  get code() {
    return this._code;
  }

  get title() {
    return this._title;
  }

  set currentQuestionNum(num) {
    this._currentQuestionNum = num;
  }

  get currentQuestionNum() {
    return this._currentQuestionNum;
  }

  set respondent(questions) {
    return this._respondent = new Respondent(questions);
  }

  get respondent() {
    return this._respondent;
  }

  set questions(config) {
    let questions = config.visibleQuestions;
    let output = [];
    questions.forEach(function (question, index) {
      output[index] = new Question(question, index);
    });
    this.sortByOrder(output);
    this._questions = output;
  }

  get questions() {
    return this._questions;
  }

  set totalNumberOfQuestions(config) {
    this._totalNumberOfQuestions = config.visibleQuestions.length;
  }

  get totalNumberOfQuestions() {
    return this._totalNumberOfQuestions;
  }

  set logic(config) {
    if (config.length !== 0) {
      let temp = {};
      config.forEach(function (answer, index) {
        if (answer.logic.length !== 0) {
          let pool = [];
          let logics = answer.logic;
          logics.forEach(function (l, index) {
            pool[index] = l.restrict_id;
          });
          temp[answer.id] = pool;
        }
      });
      this._logic = temp;
      // console.log(this._logic);
      return;
    }
    this._logic = null;
  }

  get logic() {
    return this._logic;
  }

  isFirstQuestion() {
    return this.currentQuestionNum === 0;
  }

  isLastQuestion() {
    return this.currentQuestionNum === (this.totalNumberOfQuestions - 1);
  }

  getQuestionById(id) {
    let questions = this.questions;
    for (let key in questions) {
      if (questions[key].id === id) return questions[key];
    }
    return false;
  }

  nextQuestion() {
    this.direction = 1;
    this.incCurrentQuestionNum();
    this.goToQuestionByNumber(this.currentQuestionNum);
  }

  getCurrentQuestion() {
    return this.questions[this.currentQuestionNum];
  }

  previousQuestion() {
    this.direction = -1;
    this.decCurrentQuestionNum();
    this.goToQuestionByNumber(this.currentQuestionNum);
  }

  incCurrentQuestionNum() {
    let current = this.currentQuestionNum;
    this.currentQuestionNum = ++current;
  }

  decCurrentQuestionNum() {
    let current = this.currentQuestionNum;
    this.currentQuestionNum = --current;
  }

  goToLastQuestion() {
    this.goToQuestionByNumber(this.totalNumberOfQuestions - 1);
  }

  goToQuestionByNumber(questionNumber) {
    this.currentQuestionNum = questionNumber;
    this.renderQuestion(questionNumber);
  }

  isPollComplete() {
    return this.respondent.checkResults();
  }

  unansweredQuestion() {
    return this.respondent.findUnansweredQuestion();
  }

  nextRespondent() {
    this.currentQuestionNum = 0;
    this.respondent.stopCount();
    this.respondent = this.questions;
    this.goToQuestionByNumber(0);
  }

  renderQuestion(questionNumber) {
    let Obj = this;
    let template = this.template.cloneNode(true);
    let question = this.questions[questionNumber];
    let limit = question.limit;
    this.setThemeColor(template, limit);
    let questionHeader = (questionNumber + 1) + '. ' + question.title;
    template.querySelector('#drive-header').innerHTML = questionHeader;
    let questionBody = template.querySelector('#drive-body');
    let answers = question.answers;
    let answersCounter = 1;
    let maxCodesLength = codes.length;                                          // максимальное кол-во кодов клавиатуры!!
    let result = this.respondent.getRespondentResultsOfQuestion(question.id);
    let logic = this.respondent.logic;
    if (question.numberOfAnswers < maxCodesLength) {
      let count = 0, index = 0;
      for (let key in answers) {
        let answer = answers[key];
        if (logic && logic.includes(answer.id)) {
          count++;
          if (count === question.numberOfAnswers) {
            let qNum = this.currentQuestionNum;
            Obj.skipQuestion(result, qNum);
            return;
          }
          answer.skip(index);
          questionBody.appendChild(answer.visualElement);
          index++;
          continue;
        }
        answer.renderAnswer(index);
        answer.restoreResult(result);
        questionBody.appendChild(answer.visualElement);
        index++;
      }
    } else {
      let select = question.renderSelect();
      questionBody.appendChild(select);
      setTimeout(() => this.loadScript(), 100);                                //TODO  Ооооочень слабое место с ожиданием!!!!!
      question.restoreSelectResult(result, question.selectObj);
    }
    let mainContent = document.getElementById('drive-in');
    mainContent.innerHTML = '';
    mainContent.appendChild(template);
    result.startCount;
  }

  skipQuestion(result, num) {
    result.deleteAllData();
    if (this.currentQuestionNum + 1 >= this.totalNumberOfQuestions) {
      showM();
      return;
    }
    if (this.direction === 1) {
      this.nextQuestion();
    } else {
      this.previousQuestion();
    }
  }

  loadScript() {
    let select2Script = document.createElement('script');
    let text = '$(".js-data-array").select2({' +
      'placeholder: "Выберите ответ",' +
      '});';
    let sText = document.createTextNode(text);
    select2Script.appendChild(sText);
    document.body.appendChild(select2Script);
    setTimeout(() => document.body.removeChild(select2Script), 150);          //TODO слабое место с ожиданием
  }

  // цветовая схема
  setThemeColor(template, limit) {
    if (limit !== 1) {
      template.classList.remove('panel-primary');
      template.classList.add('panel-danger');
    } else {
      template.classList.add('panel-primary');
      template.classList.remove('panel-danger');
    }
  }

  // основной шаблон
  renderTemplate() {
    let template = document.createElement('div');
    template.className = 'panel drive-question-data ';

    let header = document.createElement('div');
    header.className = 'panel-heading';
    header.id = 'drive-header';

    let body = document.createElement('div');
    body.className = 'panel-body';
    body.id = 'drive-body';

    let footer = document.createElement('div');
    footer.className = 'panel-footer panel-primary';

    let agreeBtn = document.createElement('button');
    agreeBtn.innerText = 'Дальше';
    agreeBtn.className = 'btn btn-success btn-sm confirm-next-btn';

    let countDiv = document.createElement('div');
    countDiv.className = 'total-questions';
    countDiv.innerText = 'Всего:';
    template.appendChild(header);
    template.appendChild(body);
    template.appendChild(footer);
    footer.appendChild(agreeBtn);
    footer.appendChild(countDiv);

    countDiv.append(this.totalNumberOfQuestions);
    return template;
  }

  // навигационный select
  renderNavigationSelect() {
    let serviceArea = document.getElementById('drive-service-area');
    let selectTemplate = document.createElement('p');
    let select = document.createElement('select');
    select.className = 'navigation-select form-control';
    let label = document.createElement('label');
    let labelText = document.createTextNode('Перейти:');
    label.setAttribute('for', 'navigation-select');
    label.appendChild(labelText);
    selectTemplate.appendChild(label);
    selectTemplate.appendChild(select);
    let selectData = this.questions;
    selectData.forEach(function (element, key) {
      select[key + 1] = new Option(key + 1, element.id);
      select[key + 1].dataset.key = key;
    });
    select[0] = new Option('Выберите', null);
    select[0].disabled = true;
    select.selectedIndex = 0;
    serviceArea.appendChild(selectTemplate);
  }

  // нивигационная мозайка
  renderNavigator() {
    let serviceArea = document.getElementById('drive-service-area');
    let svgNAv = document.createElementNS('http://www.w3.org/2000/svg', 'svg');

    let textNode = document.createElementNS('http://www.w3.org/2000/svg', 'text');
    textNode.setAttribute('x', 10);
    textNode.setAttribute('y', 10);
    // textNode.setAttributeNS(null, 'font-size', '100');
    let text = document.createTextNode('1');
    textNode.appendChild(text);
    textNode.classList.add('mono');
    svgNAv.appendChild(textNode);

    let y = 15;
    let qCount = this.totalNumberOfQuestions;
    for (let i = 0; i < qCount; i++) {
      let x = 15;
      for (let j = 0; j < 15 && j < qCount; j++) {
        let rect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
        rect.setAttribute('x', x);
        rect.setAttribute('y', y);
        rect.setAttribute('width', 20);
        rect.setAttribute('height', 20);
        rect.setAttribute('fill', '#ebedf0');
        rect.classList = 'question';
        // let titleNode = document.createElement('title');
        // titleNode.innerText = '22';
        // rect.appendChild(titleNode);
        svgNAv.appendChild(rect);
        x += 22;
      }
      qCount -= 15;
      y += 22;
    }
    svgNAv.setAttribute('width', '100%');
    svgNAv.setAttribute('height', y);
    serviceArea.appendChild(svgNAv);
  };

  verifyPollConfigStructure(val) {
    return val !== null;
  }

  verifyId(val) {
    return true;
  }

  sortByOrder(arr) {
    arr.sort(function (a, b) {
      return a.oldOrder < b.oldOrder;
    });
  }

}