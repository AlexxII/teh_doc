class Respondent {
  constructor(questions, number) {
    this._id = this.randomIntFromInterval(0, 98446744073709551);
    this.startTime = 0;
    this.endTime = 0;
    this.user = 'User';
    this.complete = 0;
    this.resultPool = questions;
    this._logic = {};
  }

  randomIntFromInterval(min, max) {
    return Math.floor(Math.random() * (max - min + 1) + min);
  }

  get id() {
    return this._id;
  }

  set resultPool(questions) {
    let out = {};
    questions.forEach(function (question, index) {
      out[question.id] = new result(index);
    });
    this._resultPool = out;
  }

  get resultPool() {
    return this._resultPool;
  }

  get results() {
    let tempArray = [];
    let index = 0;
    for (let key in this._resultPool) {
      tempArray[index] = this._resultPool[key];
      index++;
    }
    this.sortByOrder(tempArray);
    return tempArray;
  }

  setLogic(logic, id) {
    if (logic !== undefined) {
      this._logic[id] = logic;
    }
  }

  removeFromLogic(id) {
    let logic = this._logic;
    delete logic[id];
  }

  get logic() {
    let logic = this._logic;
    if (Object.keys(logic).length !== 0) {
      let temp = [];
      for (let key in logic) {
        temp += logic[key];
      }
      return temp;
    } else {
      return false;
    }
  }

  getRespondentResultsOfQuestion(id) {
    return this.resultPool[id];
  }

  startCount() {
    this.startTime = new Date();
  }

  stopCount() {
    this.endTime = new Date();
  }

  checkResults() {
    let results = this.resultPool;
    for (let key in results) {
      if (results[key].entries === 0 && results[key].skip !== 1) {
        return 0;
      }
    }
    return 1;
  }

  findUnansweredQuestion() {
    let results = this.resultPool;
    for (let key in results) {
      if (results[key].entries === 0 && results[key].skip !== 1) {
        return key;
      }
    }
    return false;
  }

  getCodesResults() {
    let results = this.results;
    let data = [];
    let count = 0;
    results.forEach(function (result, index) {
      let temp = result.getCodes();
      temp.forEach(function (val, index) {
        data[count] = val.code;
        count++;
      });
    });
    return data;
  }

  getResultToDb() {
    let results = this.results;
    let data = [];
    let count = 0;
    results.forEach(function (result, index) {
      let temp = result.getResult();
      temp.forEach(function (val, index) {
        data[count] = val;
        count++;
      });
    });
    return data;
  }

  sortByOrder(arr) {
    arr.sort((a, b) => a.order > b.order ? 1 : -1);
  }

  isEmpty(obj) {
    return !obj || Object.keys(obj).length === 0;
  }

}

function result(index) {
  this.typeDuration = 0;
  this.errors = 0;
  this.repair = 0;
  this.entries = 0;
  this.skip = 0;
  this.order = index;
  this.respondentAnswers = {};

  this.saveData = function (data) {
    let aData = new answerData(data);
    this.respondentAnswers[data.id] = aData;
    this.entries += 1;
  };

  this.deleteData = function (id) {
    if (this.respondentAnswers[id] !== undefined) {
      delete this.respondentAnswers[id];
      this.entries -= 1;
    }
  };

  this.deleteAllData = function () {
    this.respondentAnswers = {};
    this.entries = 0;
    this.skip = 1;
  };

  this.hasSavedData = function () {
    return this.entries > 0;
  };

  this.alreadySaved = function (id) {
    return this.respondentAnswers[id] !== undefined
  };

  this.hasUniqueAnswers = function () {
    let out = false;
    let results = this.respondentAnswers;
    if (Object.entries(results).length !== 0 && results.constructor === Object) {
      for (let key in results) {
        if (results[key].unique) {
          out = true;
          break;
        }
      }
    }
    return out;
  };

  this.getCodes = function () {
    let results = this.respondentAnswers;
    let data = [];
    if (Object.entries(results).length !== 0 && results.constructor === Object) {
      for (let key in results) {
        let temp = {};
        let result = results[key];
        temp.code = result.code;
        temp.order = result.order;
        data.push(temp);
      }
    }
    this.sortByOrder(data);
    return data;
  };

  this.getResult = function () {
    let results = this.respondentAnswers;
    let data = [];
    if (Object.entries(results).length !== 0 && results.constructor === Object) {
      for (let key in results) {
        let answer = {};
        let result = results[key];
        answer.id = result.id;
        answer.code = result.code;
        answer.order = result.order;
        answer.exData = result.extData;
        data.push(answer);
      }
    }
    this.sortByOrder(data);
    return data;
  };

  this.sortByOrder = function (arr) {
    arr.sort(function (a, b) {
      return a.order < b.order;
    });
  }

}

function answerData(data) {
  this.id = data.id;
  this.code = data.code;
  this.extData = data.extData;
  this.unique = data.unique;
  this.order = data.order;
}