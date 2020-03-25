class Poll {
  constructor(config) {
      if (this.verifyPollConfigStructure(config)) {
      this.id = config;
      this.title = config;
      this.code = config;
      this.poolOfQuestions = config;
    }
  }

  set id(config) {
    this._id = config.id;
  }

  get id() {
    return this._id;
  }

  set title(config) {
    this._title = config.title;
  }

  get title() {
    return this._title;
  }

  set code(config) {
    this._code = config.code;
  }

  get code() {
    return this._code;
  }

/*
  // устанавливает пул, выстроенный по id
  set poolOfQuestions(config) {
    let temp = {};
    let questions = config.questions;
    let length = questions.length;
    for (let i = 0; i < length; i++) {
      let questionObj = questions[i];
      let id = questionObj.id;
      temp[id] = new Question(questionObj);
    }
    this._poolOfQuestions = temp;
  }

  // возвращается пул, НЕ отсортирован по очередности
  get poolOfQuestions() {
    return this._poolOfQuestions;
  }

  // возвращается массив, отсортированный по очередности следования вопросов
  get arrayOfQuestions() {
    let temp = [];
    let questions = this.poolOfQuestions;
    let index = 0;
    for (let key in this._questions) {
      temp[index] = this._questions[key];
      index++;
    }
    this.sortByOrder(temp);
    return temp;
  }

*/

  // проверка конфигурационного файлы
  verifyPollConfigStructure(val) {
    return val !== null;
  }

  // функция сортировки по порядку
  sortByOrder(arr) {
    arr.sort((a, b) => a.order > b.order ? 1 : -1);
  }
}
