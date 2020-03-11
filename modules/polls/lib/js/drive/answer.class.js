class Answer {
  constructor(config)
  {
    this.id = config.id;
    this.title = config.title;
    this.code = config.code;
    this.titleEx = config.title_ex;
    this.logic = config.logic;
    this.order = +config.order;
    this.unique = +config.unique;
    this.type = +config.input_type;
  }

  set logic(logic) {
    if (logic.length !== 0) {
      this._logic = 1;
      return;
    }
    this._logic = 0;
  }

  get logic() {
    return this._logic;
  }

  set code(code) {
    this._code = code.padStart(3, '0');
  }

  get code() {
    return this._code;
  }

  set unique(bool) {
    this._unique = bool;
  }

  get unique() {
    return this.type === 3 || this._unique === 1
  }

  renderAnswer(index) {
    let answerTemplate = document.createElement('div');
    let answerOrederNode = document.createElement('span');
    answerOrederNode.className = 'drive-answer-order';
    answerTemplate.dataset.id = this.id;
    answerTemplate.dataset.mark = 0;
    answerTemplate.id = codes[index][1];
    answerTemplate.className = 'answer-p';
    let counterNode = document.createTextNode(codes[index][0] + '. ');
    answerOrederNode.appendChild(counterNode);
    answerTemplate.appendChild(answerOrederNode);

    // вкючить отображение кода или нет
    if (pollUser.code === 1) {
      let codeNode = document.createElement('span');
      codeNode.className = 'drive-answer-code titleNode';
      codeNode.innerText = this.code;
      answerTemplate.append(codeNode);
    }

    let titleNode = document.createElement('span');
    titleNode.className = 'drive-answer-title titleNode';
    titleNode.innerText = this.title;
    // let titleNode = document.createTextNode(this.title);
    answerTemplate.append(titleNode);
    // свободный ответ
    if (this.type === TYPE_FREE_ANSWER) {
      answerTemplate.appendChild(this.renderFreeSymbl());
    }
    // уникальный ответ
    if (this.unique) {
      answerTemplate.appendChild(this.renderUniqueSymbl());
    }
    // затрудняюсь ответить
    if (this.type === 3) {
      answerTemplate.appendChild(this.renderDifficultSymbol());
    }
    // ветвление
    if (this.logic === 1) {
      answerTemplate.appendChild(this.renderBranchSymbl());
    }
    this.visualElement = answerTemplate;
    return answerTemplate;
  };

  restoreResult(result) {
    let respondentAnswers = result.respondentAnswers;
    if (respondentAnswers[this.id] !== undefined) {
      if (this.type === TYPE_FREE_ANSWER) {
        let savedData = respondentAnswers[this.id];
        this.showInput();
        this.input.value = savedData.extData;
      }
      this.mark();
    }
  };

  mark() {
    let element = this.visualElement;
    element.style.cssText = 'background-color: ' + pollUser.markColor;
    element.dataset.mark = 1;
  };

  unmark() {
    let element = this.visualElement;
    element.style.cssText = 'background-color: #fff';
    element.dataset.mark = 0;
  };

  skip(index) {
    let element = this.renderAnswer(index);
    element.dataset.skip = 1;
    element.classList.add('skipped')
  }

  insertInput() {
    let input = document.createElement('input');
    input.className = 'form-control free-answer';
    input.dataset.id = this.id;
    let span = document.createElement('span');
    span.className = 'free-answer-wrap';
    let label = document.createElement('label');
    label.className = 'w3-label-under';
    let text = 'Введите ответ.';
    let textLabel = document.createTextNode(text);
    label.appendChild(textLabel);
    span.appendChild(input);
    span.appendChild(label);
    span.dataset.show = 1;
    this.inputSpan = span;
    this.input = input;
    this.visualElement.append(span);
  };

  showInput() {
    this.inputSpan.dataset.show = 1;
    this.input.value = '';
    this.visualElement.append(this.inputSpan);
  };

  hideInput() {
    this.inputSpan.dataset.show = 0;
    this.inputSpan.remove();
  }

  renderUniqueSymbl() {
    let uniqueNode = document.createElement('span');
    uniqueNode.className = 'drive-unique-answer';
    let uniqueSvg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    uniqueSvg.setAttribute('width', 15);
    uniqueSvg.setAttribute('height', 15);
    uniqueSvg.setAttribute('viewBox', '0 0 560.317 560.316');
    let path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    path.setAttributeNS(null, 'd', 'M 207.523 560.316 c 0 0 194.42 -421.925 194.444 -421.986 l 10.79 -23.997 c -41.824 12.02 -135.271' +
      '34.902 -135.57 35.833 C 286.96 122.816 329.017 0 330.829 0 c -39.976 0 -79.952 0 -119.927 0 l -12.167 57.938' +
      'l -51.176 209.995 l 135.191 -36.806 L 207.523 560.316 Z');
    uniqueSvg.appendChild(path);
    uniqueNode.appendChild(uniqueSvg);
    return uniqueNode;
  };

  renderBranchSymbl() {
    let branchNode = document.createElement('span');
    branchNode.className = 'drive-branch-answer';
    let branchSvg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    branchSvg.setAttribute('width', 25);
    branchSvg.setAttribute('height', 25);
    branchSvg.setAttribute('viewBox', '0 0 1356 640');
    let path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    path.setAttributeNS(null, 'd', '' +
      'M512 192c-71 0-128 57-128 128 0 47 26 88 64 110v18c0 64-64 128-128 128-53 0-95 11-128 29v-303c38-22 64-63 ' +
      '64-110 0-71-57-128-128-128s-128 57-128 128c0 47 26 88 64 110v419c-38 22-64 63-64 110 0 71 57 128 128 128s128-57 ' +
      '128-128c0-34-13-64-34-87 19-23 49-41 98-41 128 0 256-128 256-256v-18c38-22 64-63 64-110 0-71-57-128-128-128z ' +
      'm-384-64c35 0 64 29 64 64s-29 64-64 64-64-29-64-64 29-64 64-64z m0 768c-35 0-64-29-64-64s29-64 64-64 64 29 64' +
      ' 64-29 64-64 64z m384-512c-35 0-64-29-64-64s29-64 64-64 64 29 64 64-29 64-64 64z');
    branchSvg.appendChild(path);
    branchNode.appendChild(branchSvg);
    return branchNode;
  };

  renderFreeSymbl() {
    let freeNode = document.createElement('span');
    freeNode.className = 'drive-free-answer';
    let editSvg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    editSvg.setAttribute('width', 15);
    editSvg.setAttribute('height', 15);
    editSvg.setAttribute('viewBox', '0 0 20 20');
    let path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    path.setAttributeNS(null, 'd', 'M16.77 8l1.94-2a1 1 0 0 0 0-1.41l-3.34-3.3a1 1 0 0 0-1.41 0L12 3.23zm-5.81-3.71L1 ' +
      '14.25V19h4.75l9.96-9.96-4.75-4.75z');
    editSvg.appendChild(path);
    freeNode.appendChild(editSvg);
    return freeNode;
  };

  renderDifficultSymbol() {
    let difficultNode = document.createElement('span');
    difficultNode.className = 'drive-difficult-answer';
    let difficultSvg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    difficultSvg.setAttribute('width', 15);
    difficultSvg.setAttribute('height', 15);
    difficultSvg.setAttribute('viewBox', '0 0 281.232 281.232');
    let path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    path.setAttributeNS(null, 'd', 'M 231.634 79.976 v -0.751 C 231.634 30.181 192.772 0 137.32 0 c -31.987 0 -57.415 ' +
      '9.018 -77.784 22.98 c -11.841 8.115 -12.907 25.906 -4.232 37.355 l 6.326 8.349 c 8.675 11.444 24.209 12.532 ' +
      '36.784 5.586 c 11.46 -6.331 23.083 -9.758 34 -9.758 c 18.107 0 28.294 7.919 28.294 20.75 v 0.375 c 0 16.225 ' +
      '-15.469 39.411 -59.231 43.181 l -1.507 1.697 c -0.832 0.936 0.218 13.212 2.339 27.413 l 1.741 11.58 c 2.121' +
      ' 14.201 14.065 25.71 26.668 25.71 s 23.839 -5.406 25.08 -12.069 c 1.256 -6.668 2.268 -12.075 2.268 -12.075 ' +
      'C 199.935 160.882 231.634 127.513 231.634 79.976 Z');
    let pathEx = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    pathEx.setAttributeNS(null, 'd', 'M 118.42 217.095 c -14.359 0 -25.993 11.64 -25.993 25.999 v 12.14 c 0 14.359 ' +
      '11.64 25.999 25.993 25.999 h 22.322 c 14.359 0 25.999 -11.64 25.999 -25.999 v -12.14 c 0 -14.359 -11.645 ' +
      '-25.999 -25.999 -25.999 H 118.42 Z');
    difficultSvg.appendChild(path);
    difficultSvg.appendChild(pathEx);
    difficultNode.appendChild(difficultSvg);
    return difficultNode;
  }

}