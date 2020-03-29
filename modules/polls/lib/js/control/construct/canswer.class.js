class CAnswer {
  constructor(config, index, pObj) {
    this.parentQuestion = pObj;
    this.parentPoll = pObj;
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
    this.ADD_LOGIC_URL = '/polls/control/construct/add-poll-logic';
    this.SUB_LOGIC_URL = '/polls/control/construct/sub-poll-logic';
  }

  set parentPoll(Obj) {
    this._parentPoll = Obj.parentPoll;
  }

  get parentPoll() {
    return this._parentPoll;
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

  setLogicConflict() {
    this.showLogicMenu();
  }

  showLogicMenu() {
    let Obj = this;
    let questionObj = this.parentQuestion;
    let pollObj = this.parentPoll;
    let menu = $.alert({
      title: pollObj.code + ' ' + 'исключить ответы',
      content: Obj.renderExeptionMenu(),
      columnClass: 'col-md-12',
      animateFromElement: false,
      buttons: {
        ok: {
          text: 'Сохранить',
          btnClass: 'btn-success',
          action: function () {
            Obj.confirmLogic(menu);
          }
        },
        cancel: {
          text: 'Отмена',
          action: function () {
          }
        }
      }
    });
  }

   renderExeptionMenu() {
     let Obj = this;
     let questionObj = this.parentQuestion;
     let pollObj = this.parentPoll;
     let menuDiv = document.createElement('div');
     menuDiv.id = 'logic-menu-content';
     let questions = pollObj.questions;
     let logic = Obj.logicArray;
     for (let qId in questions) {
       let question = questions[qId];
       menuDiv.appendChild(question.questionTmplEx);
       let answers = question.answers;
       let length = answers.length;
       for (let i = 0; i < length; i ++) {
         let answer = answers[i];
         if (logic && logic.includes(answer.id)) {
           answer.tempTmpl.getElementsByTagName('input')[0].checked = true;
         }
       }
     }
     questionObj.tempTmpl.classList.add('selected-question');
     let answers = questionObj.answers;
     questionObj.tempTmpl.querySelector('.q-title').classList.remove('check-all');
     answers.forEach(function (answer, index) {
       answer.tempTmpl.getElementsByTagName('input')[0].disabled = true;
     });
     // Obj.tempTmpl.getElementsByTagName('input')[0].disabled = true;
     Obj.tempTmpl.classList.add('selected-answer');
     return menuDiv;
   }

  confirmLogic(menu) {
    let Obj = this;
    let questionObj = this.parentQuestion;
    let pollObj = this.parentPoll;
    let menuContent = menu.$content[0];
    let inputs = menuContent.getElementsByTagName('input');
    let result = [];
    Array.prototype.map.call(inputs, function (val) {
      if (val.checked) {
        result.push(val.dataset.id);
        val.checked = false;                                          // снимаем checkbox
      }
    });
    let oldLogic = Obj._logicArray;
    let newLogic = result;
    let subbing = oldLogic.filter(x => !newLogic.includes(x));    // удаление
    let adding = newLogic.filter(x => !oldLogic.includes(x));    //  прибавление
    if (adding) {
      this.addLogic(adding, result);
    }
    if (subbing) {
      this.subLogic(subbing, result);
    }
  }

  addLogic(adding, result) {
    let Obj = this;
    let pollObj = this.parentPoll;
    let questionObj = this.parentQuestion;
    if (adding.length !== 0) {
      let url = this.ADD_LOGIC_URL;
      $.ajax({
        url: url,
        method: 'post',
        data: {
          restrict: adding,
          pollId: pollObj.id,
          answer: Obj.id
        }
      }).done(function (response) {
        if (!response.code) {
          var tText = '<span style="font-weight: 600">Что-то пошло не так!</span><br>Изменить логику не удалось';
          initNoty(tText, 'warning');
          console.log(response.data.message + ' ' + response.data.data);
          return;
        }
        Obj._logicArray = result;
        Obj.answerTmpl.append(Obj.renderBranchSymbl());
        var tText = '<span style="font-weight: 600">Успех!</span><br>Логика изменена';
        initNoty(tText, 'success');
      }).fail(function () {
        var tText = '<span style="font-weight: 600">Что-то пошло не так!</span><br>Изменить логику не удалось';
        initNoty(tText, 'warning');
        console.log('Не удалось получить ответ сервера. Примените отладочную панель, оснаска "Сеть"');
      });
    }
  }

  subLogic(subbing, result) {
    let Obj = this;
    let pollObj = this.parentPoll;
    let questionObj = this.parentQuestion;
    if (subbing.length !== 0) {
      let url = this.SUB_LOGIC_URL;
      $.ajax({
        url: url,
        method: 'post',
        data: {
          restrict: subbing,
          answer: Obj.id
        }
      }).done(function (response) {
        if (!response.code) {
          var tText = '<span style="font-weight: 600">Что-то пошло не так!</span><br>Изменить логику не удалось';
          initNoty(tText, 'warning');
          console.log(response.data.message + ' ' + response.data.data);
          return;
        }
        Obj._logicArray = result;
        if (result.length > 0) {
          Obj.answerTmpl.append(Obj.renderBranchSymbl());
        } else {
          Obj.answerTmpl.querySelector('.jump-icon').remove();
        }
        var tText = '<span style="font-weight: 600">Успех!</span><br>Логика изменена';
        initNoty(tText, 'success');
      }).fail(function () {
        var tText = '<span style="font-weight: 600">Что-то пошло не так!</span><br>Изменить логику не удалось';
        initNoty(tText, 'warning');
        console.log('Не удалось получить ответ сервера. Примените отладочную панель, оснаска "Сеть"');
      });
    }
  }

  // ===== настройки автоматики ответа ========
  setTechnologicalKey() {
    let Obj = this;
    let pollObj = this.parentPoll;
    let questionObj = this.parentQuestion;
    let menu = $.alert({
      title: 'Настройка автоматики ответа',
      content: Obj.renderTehnologicalContent(),
      boxWidth: '30%',
      useBootstrap: false,
      // columnClass: 'col-md-6',
      animateFromElement: false,
      buttons: {
        ok: {
          text: 'Сохранить',
          btnClass: 'btn-success',
          action: function () {
            Obj.confirmTehologicalData(menu);
          }
        },
        cancel: {
          text: 'Отмена',
          action: function () {
          }
        }
      }
    });
  }

  renderTehnologicalContent() {
    let Obj = this;
    let pollObj = this.parentPoll;
    let questionObj = this.parentQuestion;
    let divNode = document.createElement('div');
    divNode.className = 'technological-wrap';

    let infoNode = document.createElement('div');
    infoNode.classList = 'alert alert-info';
    infoNode.setAttribute('role', 'alert');

    let textData = 'Внимание! ';
    let strongText = document.createTextNode(textData);
    let strongNode = document.createElement('strong');
    strongNode.appendChild(strongText);

    let infoData = 'Выберите или впишите коды последующих ответов в поле ниже. Они будут сохранены автоматически при выборе данного ответа.';
    let infoText = document.createTextNode(infoData);

    infoNode.appendChild(strongNode);
    infoNode.appendChild(infoText);

    divNode.appendChild(infoNode);

    let serviceDiv = document.createElement('div');
    serviceDiv.className = 'technological-service';

    let input = document.createElement('textarea');
    input.classList = 'form-control technological-input';
    input.maxLength = '5000';
    input.cols = '55';
    input.rows = '5';
    serviceDiv.appendChild(input);

    let chooseBtn = document.createElement('a');
    chooseBtn.className = 'technological-choose';
    chooseBtn.innerHTML = 'Выберите';
    serviceDiv.appendChild(chooseBtn);
    chooseBtn.addEventListener('click', () => { Obj.showTechMenu(); }, false);

    let span = document.createElement('span');
    span.appendChild(document.createTextNode('вводите коды ответов через запятую'));
    span.className = 'technological-area-label';
    serviceDiv.appendChild(span);


    let footerDiv = document.createElement('div');
    footerDiv.className = 'technological-footer';

    let checkbox = document.createElement('input');
    checkbox.classList = 'technological-self-code';
    checkbox.type = 'checkbox';
    checkbox.id = 'technological-self';
    footerDiv.appendChild(checkbox);

    let checkboxLabel = document.createElement('label');
    checkboxLabel.className = 'technological-self-label';
    checkboxLabel.htmlFor = 'technological-self';
    checkboxLabel.appendChild(document.createTextNode('Cохранять собственный код'));
    footerDiv.appendChild(checkboxLabel);

    divNode.appendChild(serviceDiv);
    divNode.appendChild(footerDiv);

    return divNode;
  }


  showTechMenu() {
    let Obj = this;
    let pollObj = this.parentPoll;
    let questionObj = this.parentQuestion;
    let menu = $.alert({
      title: 'Выберите ответы, которые проставяться автоматически',
      content: Obj.renderTechMenu(),
      useBootstrap: false,
      columnClass: 'col-md-12',
      animateFromElement: false,
      buttons: {
        ok: {
          text: 'Очистить',
          // btnClass: 'btn-success',
          action: function () {
            // очитисть чекбоксы;
          }
        },
        cancel: {
          text: 'Назад',
          btnClass: 'btn-success',
          action: function () {
            return;
          }
        }
      }
    });
  }

  renderTechMenu() {
    let Obj = this;
    let questionObj = this.parentQuestion;
    let pollObj = this.parentPoll;
    let menuDiv = document.createElement('div');
    menuDiv.id = 'logic-menu-content';
    let questions = pollObj.questions;
    let logic = Obj.logicArrayEx;

    for (let qId in questions) {
      let question = questions[qId];
      menuDiv.appendChild(question.questionTmplEx);
      let answers = question.answers;
      let length = answers.length;
      for (let i = 0; i < length; i ++) {
        let answer = answers[i];
        let limit = question.limit;
        if (limit > 1) {
          question.tempTmpl.querySelector('.q-title').style.color = 'red';
        }
        question.tempTmpl.querySelector('.q-title').classList.remove('check-all');
        // TODO: выставить логику + подхватывать те коды, которые указал пользователь;
        let count = 0;
        //=====
        let checkboxesPool = answer.tempTmpl.parentElement;
        answer.tempTmpl.getElementsByTagName('input')[0].addEventListener('change', Obj.countCheckInputs.bind(this, checkboxesPool, limit) , false);
        if (logic && logic.includes(answer.id)) {
          answer.tempTmpl.getElementsByTagName('input')[0].checked = true;
        }
      }
    }
    questionObj.tempTmpl.classList.add('selected-question');
    let answers = questionObj.answers;
    questionObj.tempTmpl.querySelector('.q-title').classList.remove('check-all');
    Obj.tempTmpl.getElementsByTagName('input')[0].disabled = true;
    Obj.tempTmpl.classList.add('selected-answer');
    return menuDiv;
  }

  // не дает выбирать больше checkbox-ов, чем лимит, установленный в настройках вопроса.
  countCheckInputs() {
    let checkboxesPool = arguments[0];
    let limit = arguments[1];
    let e = arguments[2];
    let checkedcount = 0;
    let currentInput = e.originalTarget;
    let checkboxgroup  = checkboxesPool.getElementsByTagName("input");
    for (let i = 0; i < checkboxgroup.length; i++) {
      checkedcount += (checkboxgroup[i].checked) ? 1 : 0;
    }
		if (checkedcount > limit) {
			console.log("You can select maximum of " + limit + " checkbox.");
			currentInput.checked = false;
		}
  }

  confirmTehologicalData() {

  }

  // ====== rendering ========
  // основной шаблон вида ответа
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

    let conflictLogicBtn = answerClone.querySelectorAll('.logic-conflict')[0];
    conflictLogicBtn.addEventListener('click', () => { Obj.setLogicConflict(); }, false);

    let technologicalBtn = answerClone.querySelectorAll('.technological')[0];
    technologicalBtn.addEventListener('click', () => { Obj.setTechnologicalKey(); }, false);

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

  renderCAnswer(index) {
    let answer = this.answerTmpl;
    answer.querySelector('.answer-number').innerHTML = index;
    return answer;
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
