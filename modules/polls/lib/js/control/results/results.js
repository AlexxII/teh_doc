let arrayOfRespondents;





// TODO: 222222222222222222

function startResultAnalyze() {
  let headerNode = document.getElementById('control-header');
  headerNode.innerHTML = '';
  let resultNode = document.getElementById('control-result');
  resultNode.innerHTML = '';

  let footerNode = document.getElementById('control-footer');
  footerNode.innerHTML = '';

  renderResultHeader();
  renderResultTbl();
  footerNode.appendChild(showArrayOfCodes());
}

function renderResultHeader() {
  let headerNode = document.getElementById('control-header');
  let wrapDiv = document.createElement('div');
  wrapDiv.className = 'result-header-wrap';

/*  let mapBtn = document.createElement('a');
  mapBtn.innerText = 'Открыть карту';
  mapBtn.id = 'result-show-map';
  mapBtn.addEventListener('click', mapsMe, false);
*/
  let unloadNode = document.createElement('a');
  unloadNode.innerText = 'Выгрузить данные';
  unloadNode.id = 'result-unload-data';
  unloadNode.addEventListener('click', unloadData, false);

  let btnsWrap = document.createElement('div');
  btnsWrap.className = 'result-btns';

  // btnsWrap.appendChild(mapBtn);
  btnsWrap.appendChild(unloadNode);

  let hrNode = document.createElement('hr');
  wrapDiv.appendChild(resultBlockOfSelectsTmpl());
  wrapDiv.appendChild(hrNode);
  wrapDiv.appendChild(btnsWrap);
  headerNode.appendChild(wrapDiv);
}

function renderTblOfResults() {
  let wrapDiv = document.createElement('div');
  wrapDiv.id = 'result-wrap';
  let tableNode = document.createElement('table');
  tableNode.id = 'result-table';
  tableNode.className = 'display';
  tableNode.width = '100%';
  wrapDiv.appendChild(tableNode);
  return wrapDiv;
}

var resultDataSet = [
  ['1123','Иванов С.Г.', '10.03.2020 13:40', 'Мурманск', '501 - Женский', '507 - 50-59 лет', 'Выгружен'],
  ['23123','Петров А.И.', '09.03.2020 14:15', 'Никель', '502 - Мужской', '506 - 30-49 лет', 'Не выгружен']
];

var resultDataSetEx = [];


function renderResultTbl() {
  let resultNode = document.getElementById('control-result');
  resultNode.appendChild(renderTblOfResults());

  $('#result-table').DataTable({
    data: resultDataSet,
    responsive: true,
    searching: false,
    columns: [
      {title: 'id', data: 'id'},
      {title: 'Оператор', data: 'user'},
      {title: 'Дата', data: 'date'},
      {title: 'Населеный пункт', data: 'town'},
      {title: 'Пол', data: 'gender'},
      {title: 'Возраст', data: 'age'},
      {title: 'Статус', data: 'status'},
      {},
      {}
    ],
    columnDefs: [
      {
        'targets': 0,
        'visible': false
      },
      {
        'targets': -2,
        'render': function (data, type, row) {
          return '<a class="result-detail" data-id="' + row[0] + '">Данные</a>';
        },
        'width': '70px',
      }, {
        'targets': -1,                    // последний столбец
        'orderable': false,
        'className': 'select-checkbox',
        'defaultContent': ''
      }
    ],
    select: {
      style: 'multi',
      selector: 'td:last-child',
    },
    language: {
      url: '/lib/ru.json'
    }
  });
}

function resultBlockOfSelectsTmpl() {
  let selectBlock = document.createElement('div');
  selectBlock.className = 'result-select-block';

  let operatorSelect = document.createElement('select');
  let operatorLabel = document.createElement('label');
  operatorLabel.innerText = 'Операторы:';
  operatorLabel.className = 'result-select-labels';
  operatorSelect.id = 'result-operators';
  operatorSelect.classList = 'form-control';
  operatorSelect.disabled = true;
  operatorLabel.appendChild(operatorSelect);

  let townSelect = document.createElement('select');
  let townLabel = document.createElement('label');
  townLabel.className = 'result-select-labels';
  townLabel.innerText = 'ТНП:';
  townSelect.id = 'result-town';
  townSelect.classList = 'form-control';
  townSelect.disabled = true;
  townLabel.appendChild(townSelect);

  let sexSelect = document.createElement('select');
  let sexLabel = document.createElement('label');
  sexLabel.className = 'result-select-labels';
  sexLabel.innerText = 'Пол:';
  sexSelect.id = 'result-sex';
  sexSelect.classList = 'form-control';
  sexSelect.disabled = true;
  sexLabel.appendChild(sexSelect);

  let ageSelect = document.createElement('select');
  let ageLabel = document.createElement('label');
  ageLabel.className = 'result-select-labels';
  ageLabel.innerText = 'Возраст:';
  ageSelect.id = 'result-age';
  ageSelect.classList = 'form-control';
  ageSelect.disabled = true;
  ageLabel.appendChild(ageSelect);

  let statusSelect = document.createElement('select');
  let statusLabel = document.createElement('label');
  statusLabel.className = 'result-select-labels';
  statusLabel.innerText = 'Статус:';
  statusSelect.id = 'result-status';
  statusSelect.classList = 'form-control';
  statusSelect.disabled = true;
  statusLabel.appendChild(statusSelect);

  selectBlock.appendChild(operatorLabel);
  selectBlock.appendChild(townLabel);
  selectBlock.appendChild(sexLabel);
  selectBlock.appendChild(ageLabel);
  selectBlock.appendChild(statusLabel);

  return selectBlock;
}

function prepareData(config) {
  arrayOfRespondents = {};
  let respondents = config.respondent;
  let results = config.results;
  for (let key in respondents) {
    let respondent = respondents[key];
    let id = respondent.respondent_id;
    arrayOfRespondents[id] = [];
  }
  console.log(results);
  results.forEach(function (result, index) {
    let out = result.answer_code;
    if (result.ex_answer !== '') {
      out += ' ' + result.ex_answer;
    }
    arrayOfRespondents[result.respondent_id].push(out);
  });
  console.log(arrayOfRespondents);
}

function showArrayOfCodes() {
  let textAreaNode = document.createElement('textarea');
  textAreaNode.id = 'analytic-result-text';
  textAreaNode.cols = '150';
  textAreaNode.rows = '30';
  for (let key in arrayOfRespondents) {
    let result = arrayOfRespondents[key];
    result += ',999';
    result += '\r\n';
    let textNode = document.createTextNode(result);
    textAreaNode.append(textNode);
  }
  return textAreaNode;
}

function resultSheet(data) {
  this.id = data.id.value;
  this.user = data.user;
  this.town = data.town;
  this.gender = data.gender;
  this.age = data.age;
  this.status = data.status;
}
