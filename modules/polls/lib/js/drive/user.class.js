class PollUser {
  constructor(settings) {
    this._id = settings.id;
    this.stepDelay = settings.stepDelay;                                      // задержка при переходе на другой вопрос
    this.markColor = settings.markColor;                                       // цвет выделение при ответе
    this.code = settings.code;
  }

  get id() {
    return this._id;
  }

}