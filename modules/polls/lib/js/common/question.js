class Question {
  constructor(config) {
    this.id = config;
    this.title = config;  
    this.titleEx = config;
    this.oder = config;
    this.oldOder = config;
    this.limit = config;
    this.visible = config;
    this.poolOfAnswers = config;
  }

  // TODO: Ссылка на объект родителя ?!?!?!?

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

  set titleEx(config) {
    this._titleEx = config.titleEx;
  }

  get titleEx() {
    return this._titleEx;
  }

  set order(config) {
    this._order = config.order;
  }

  get order() {
    return this._order;
  }

  set limit(config) {
    this._limit = config.limit;
  }

  get limit() {
    return this._limit;
  }

  set visible(config) {
    this._visible = config.visible;
  }

  get visible() {
    return this._visible;
  }

/*
  // устанавливает пул, выстроенный по id
  set poolOfAnswers(config) {
    console.log(config);
    let temp = {};
    let answers = config.answers;
    let length = answers.length;
    for (let i = 0; i < length; i++) {
      let answerObj = answers[i];
      let id = answerObj.id;
      temp[id] = new Answer(answerObj);
    }
    this._poolOfQuestions = temp;
  }

  get poolOfAnswers() {
    return this._poolOfQuestions;
  }

  // получение объекта Answer по id
  get answer() {
    let answers = this.poolOfAnswers;
    if (answers[id] !== undefined)
      return answers[id];
    return false;
  }
*/
  // функция сортировки по порядку
  sortByOrder(arr) {
    arr.sort((a, b) => a.order > b.order ? 1 : -1);
  }

  //======= render =========




}
