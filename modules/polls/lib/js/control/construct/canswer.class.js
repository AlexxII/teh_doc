class CAnswer {
  constructor(config, index, pObj) {
    this.parentQuestion = pObj;
    this.id = config.id;
    this.titleEx = config.title_ex;
    this.title = config.title;
    this.logicArray = config.logic;
    this.order = +config.order;
    this.oldOrder = +config.oldOrder;
    this.code = config.code;
    this.dCode = +config.code;                                                    // цифровой код ответа
    this.unique = +config.unique;
    // this.jump = +config.jump;
    this.type = +config.input_type;
    this.visible = +config.visible;
    this.answerTmpl = index + 1;
    // this.answerTmplEx = index + 1;
    this.HIDE_ANSWER_URL = '/polls/control/construct/hide-answer';
    this.RESTORE_ANSWER_URL = '/polls/control/construct/restore-answer';
    this.UNIQUE_ANSWER_URL = '/polls/control/construct/unique-answer';
  }

  renderCAnswer(index) {
    let answer = this.answerTmpl;
    answer.querySelector('.answer-number').innerHTML = index;
    return answer;
  }

  get answerTmpl() {
    return this._answerTmpl;
  }

  get answerTmplEx() {
    return this.renderAnswerTmplEx();
  }

  set logicArray(logic) {
    if (logic.length !== 0) {
      let temp = [];
      logic.forEach(function (val, index) {
        temp[index] = val.restrict_id;
      });
      this._logicArray = temp;
      return;
    }
    this._logicArray = [];
  }

  get logicArray() {
    return this._logicArray;
  }

  get logic() {
    return this._logicArray.length !== 0 ? 1 : 0;
  }

  set answerTmpl(index) {
    let Obj = this;
    let answerDiv = document.getElementById('answer-template');
    let answerClone = answerDiv.cloneNode(true);
    answerClone.removeAttribute('id');
    let answerId = this.id;
    answerClone.dataset.id = answerId;
    answerClone.dataset.old = this.oldOrder;
    answerClone.querySelector('.answer-title').innerHTML = this.title;
    let code = this.code.padStart(3, '0');
    answerClone.querySelector('.answer-code').innerHTML = code;

    let restoreBtn = answerClone.querySelector('.restore-btn');
    restoreBtn.addEventListener('click', () => { Obj.restoreAnswerInListView(); }, false);

    let hideBtn = answerClone.querySelector('.answer-hide');
    hideBtn.addEventListener('click', () => { Obj.hideAnswerInListView(); }, false);

    let uniqueBtn = answerClone.querySelector('.unique-btn');
    uniqueBtn.addEventListener('click', () => { Obj.changeUniqueForQuestion(); }, false);

    if (this.visible === 0) {
      answerClone.classList.add('hidden-answer');
      answerClone.querySelector('.answer-hide').style.display = 'none';
      answerClone.querySelector('.answer-options').style.display = 'none';
      answerClone.querySelector('.unique-btn').style.display = 'none';
    } else {
      answerClone.querySelector('.restore-btn').style.display = 'none';
    }
    if (this.unique === 1) {
      answerClone.classList.add('unique-answer');
    }
    if (this.logic === 1) {
      answerClone.appendChild(this.renderBranchSymbl());
    }
    this._answerTmpl = answerClone;
  }

  renderAnswerTmplEx() {
    let answerDiv = document.getElementById('answer-li-tmpl');
    let answerClone = answerDiv.cloneNode(true);
    // answerClone.removeAttribute('id');
    // answerClone.querySelector('.check-logic').id = this.id;
    answerClone.id = this.id;
    answerClone.querySelector('.check-logic').dataset.id = this.id;
    answerClone.querySelector('.a-title').innerHTML = this.title;
    let code = this.code.padStart(3, '0');
    answerClone.querySelector('.a-code').innerHTML = code;
    // if (this.unique === 1) {
    //   answerClone.classList.add('unique-answer');
    // }
    this.tempTmpl = answerClone;
    return answerClone;
  }

  hideAnswerInListView() {
    let Obj = this;
    let pObj = this.parentQuestion;
    let url = this.HIDE_ANSWER_URL;
    let answerId = this.id;
    let hSortDiv = pObj.hSortable.el;
    $.ajax({
      url: url,
      method: 'post',
      data: {
        id: answerId
      }
    }).done(function (response) {
      if (response.code) {
        if (hSortDiv.getElementsByTagName('hr').length === 0) {
          let hr = document.createElement('hr');
          hSortDiv.appendChild(hr);
        }
        let tmpl = Obj.answerTmpl;
        tmpl.querySelector('.answer-hide').style.display = 'none';
        tmpl.querySelector('.answer-options').style.display = 'none';
        tmpl.querySelector('.unique-btn').style.display = 'none';
        tmpl.querySelector('.restore-btn').style.display = 'block';
        tmpl.classList.add('hidden-answer');
        hSortDiv.appendChild(tmpl);
        setTimeout(() => pObj.reindex(), 300);
      } else {
        console.log(response.data.message + '\n' + response.data.data);
      }
    }).fail(function () {
      console.log('Failed to hide question - URL failed');
    });
  }

  restoreAnswerInListView(callback) {
    let Obj = this;
    let pObj = this.parentQuestion;
    let url = this.RESTORE_ANSWER_URL;
    let answerId = this.id;
    let sortable = pObj.sortable;
    let sortDiv = sortable.el;
    $.ajax({
      url: url,
      method: 'post',
      data: {
        id: answerId
      }
    }).done(function (response) {
      if (response.code) {
        let tmpl = Obj.answerTmpl;
        tmpl.querySelector('.answer-hide').style.display = 'inline';
        tmpl.querySelector('.answer-options').style.display = 'inline';
        tmpl.querySelector('.unique-btn').style.display = 'inline';
        tmpl.querySelector('.restore-btn').style.display = 'none';
        tmpl.classList.remove('hidden-answer');
        sortDiv.appendChild(tmpl);
        let ar = sortable.toArray();
        ar.push(Obj.id + '');
        sortable.sort(ar);
        setTimeout(() => pObj.resort(), 300);
      } else {
        console.log(response.data.message + '\n' + response.data.data);
      }
    }).fail(function () {
      console.log('Failed to hide question - URL failed');
    });
  }

  changeUniqueState() {
    let unique = this.unique;
    if (unique === 1) {
      this.unique = 0;
      return this.unique;
    } else {
      this.unique = 1;
      return this.unique;
    }
  }

  changeUniqueForQuestion() {
    let url = this.UNIQUE_ANSWER_URL;
    let tmpl = this.answerTmpl;
    let answerId = this.id;
    let state = this.changeUniqueState();
    $.ajax({
      url: url,
      method: 'post',
      data: {
        id: answerId,
        bool: state
      }
    }).done(function (response) {
      if (response.code) {
        if (state === 1) {
          tmpl.classList.add('unique-answer');
        } else {
          tmpl.classList.remove('unique-answer');
        }
      } else {
        this.changeUniqueState();
        console.log(response.data.message + '\n' + response.data.data);
      }
    }).fail(function () {
      console.log('Failed to hide question - see Network Monitor - "Ctrl+SHift+E "');
    });
  }

  renderBranchSymbl() {
    let jmpNode = document.createElement('div');
    jmpNode.className = 'jump-icon';
    let branchNode = document.createElement('span');
    branchNode.className = '';
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
    jmpNode.appendChild(branchNode);
    return jmpNode;
  }

}
