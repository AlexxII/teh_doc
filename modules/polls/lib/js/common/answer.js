class Answer {
  constructor(config) {
    this.id = config;
    this.parentQuestionId = config;
    this.title = config;
    this.titleEx = config;
    this.oder = config;
    this.oldOder = config;
    this.limit = config;
    this.visible = config;
  }

  // TODO: Нужно ли вставить ссылку на объект родителя ?!?!?!?

  set id(config) {
    this._id = config.id;
  }

  get id() {
    return this._id;
  }

  set parentQuestionId(config) {
    this._parentQuestionId = config.question_id;
  }

  get parentQuestionId() {
    return this._parentQuestionId;
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


}
