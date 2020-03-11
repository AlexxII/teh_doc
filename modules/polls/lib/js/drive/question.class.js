class Question {
  constructor(config, number) {
    this.id = config.id;
    this.title = config.title;
    this.titleEx = config.title_ex;
    this.order = +config.order;
    this.limit = +config.limit;
    this.number = number;
    this.required = 1;                                       // вопрос обязателен
    this.answers = config.visibleAnswers;                    // пул ответов (объектов)
    this.numberOfAnswers = config.visibleAnswers;
  }

  set answers(answers) {
    let answersPool = answers;
    this.sortByOrder(answersPool);
    let tempOutput = [];
    answersPool.forEach(function (val, index) {
      tempOutput[index] = new Answer(val);
    });
    this._answers = tempOutput;
  }

  set numberOfAnswers(answers) {
    this._numberOfAnswers = answers.length
  }

  get numberOfAnswers() {
    return this._numberOfAnswers;
  }

  get answers() {
    return this._answers;
  }

  renderSelect() {
    let selectTemplate = document.createElement('p');
    let select = document.createElement('select');
    select.className = 'js-data-array js-state form-control';
    let label = document.createElement('label');
    let labelText = document.createTextNode('Выберите ответ:');
    label.setAttribute('for', 'js-data-array');
    label.appendChild(labelText);
    selectTemplate.appendChild(label);
    selectTemplate.appendChild(select);
    let selectData = this.prepareData();
    selectData.forEach(function (element, key) {
      select[key + 1] = new Option(element.text, element.id);
    });
    this.selectObj = select;
    return selectTemplate;
  }

  restoreSelectResult(result, select) {
    let respondentAnswers = result.respondentAnswers;
    for (let key in respondentAnswers) {
      $(select).val(respondentAnswers[key].id);
      // $(select).trigger('change');
    }
  }

  // фильтрация и расстановка последовательности отображения
  prepareData() {
    let answers = this.answers;
    let result = [];
    answers.forEach(function (answer, index) {
      let option = {};
      option.id = answer.id;
      option.text = answer.title;
      result[index] = option;
    });
    return result;
  }

  getAnswer(id) {
    let answers = this.answers;
    let result;
    for (let key in answers) {
      if (answers[key].id === id) {
        result = answers[key];
        break;
      }
    }
    return result;
  }

  sortByOrder(arr) {
    arr.sort((a, b) => +a.order > +b.order ? 1 : -1);
  }

}