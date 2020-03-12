let parchaTable, filteredArrayOfParchaSheeets, arrayOfParchaSheeets;

function startParchaAnalyze() {
  let headerNode = document.getElementById('control-header');
  headerNode.innerHTML = '';
  let resultNode = document.getElementById('control-result');
  resultNode.innerHTML = '';
  let footerNode = document.getElementById('control-footer');
  footerNode.innerHTML = '';

  renderParchaHeader();
  renderParchaTbl()
}

// основной объект
function parchaSheet(data, answers, answersRow, aObject) {
  this.id = data.id.value;
  this.user = data.usr_intrv.value;
  this.date = data.date_intrv.value;
  // this.gender = '501 - Женский';
  // this.age = '507 - 50-59 лет';
  // this.townType = '554 - город с численностью до 50 тыс.чел., поселок городского типа';
  this.gender = data.gender;
  this.age = data.age;
  this.townType = data.town;
  this.startLt = data['start-lan'].value;
  this.endLt = data['end-lat'].value;
  this.startLn = data['start-lon'].value;
  this.endLn = data['end-lon'].value;
  this._status = +data.status.value;
  this.answers = answers;
  this.answersRow = answersRow;
  this.aObject = aObject;                                             // ссылка на шит в исходном xml объекте

  this.status = function () {
    return this._status ? 'Принято' : 'Отклонен';
  }
}

function renderParchaHeader() {
  let headerNode = document.getElementById('control-header');

  let wrapDiv = document.createElement('div');
  wrapDiv.className = 'parcha-upload';

  let mapBtn = document.createElement('a');
  mapBtn.innerText = 'Открыть карту';
  mapBtn.id = 'parcha-show-map';
  mapBtn.addEventListener('click', mapsMe, false);

  let unloadXmlNode = document.createElement('a');
  unloadXmlNode.innerText = 'Выгрузить данные (XML)';
  unloadXmlNode.id = 'parcha-unload-data';
  unloadXmlNode.addEventListener('click', unloadData, false);

  let unloadRawNode = document.createElement('a');
  unloadRawNode.innerText = 'Выгрузить данные (Raw)';
  unloadRawNode.id = 'parcha-unload-data';
  unloadRawNode.addEventListener('click', renderRowAnswers, false);

  let btnsWrap = document.createElement('div');
  btnsWrap.className = 'parcha-btns';

  btnsWrap.appendChild(mapBtn);
  btnsWrap.appendChild(unloadXmlNode);
  btnsWrap.appendChild(unloadRawNode);

  let hrNode = document.createElement('hr');
  wrapDiv.appendChild(xmlUploadTmpl());
  wrapDiv.appendChild(blockOfSelects());
  wrapDiv.appendChild(hrNode);
  wrapDiv.appendChild(btnsWrap);
  headerNode.appendChild(wrapDiv);
}

function xmlUploadTmpl() {
  let divForm = document.createElement('div');
  divForm.className = 'form-group';
  let labelNode = document.createElement('label');
  let text = document.createTextNode('Добавить файл XML:');
  labelNode.appendChild(text);
  labelNode.htmlFor = 'parcha-upload';
  divForm.appendChild(labelNode);
  let inputNode = document.createElement('input');
  inputNode.className = 'form-control-file';
  inputNode.id = 'parcha-upload';
  inputNode.type = 'file';
  divForm.appendChild(inputNode);
  inputNode.addEventListener('change', loadXmlFile, false);
  return divForm;
}

function blockOfSelects() {
  let selectBlock = document.createElement('div');
  selectBlock.className = 'parcha-select-block';

  let operatorSelect = document.createElement('select');
  let operatorLabel = document.createElement('label');
  operatorLabel.innerText = 'Планшет:';
  operatorLabel.className = 'parcha-select-labels';
  operatorSelect.id = 'parcha-operators';
  operatorSelect.classList = 'form-control';
  operatorSelect.disabled = true;
  operatorLabel.appendChild(operatorSelect);

  let dateSelect = document.createElement('select');
  let dateLabel = document.createElement('label');
  dateLabel.innerText = 'Датa:';
  dateLabel.className = 'parcha-select-labels';
  dateSelect.id = 'parcha-date';
  dateSelect.classList = 'form-control';
  dateSelect.disabled = true;
  dateLabel.appendChild(dateSelect);

  let townSelect = document.createElement('select');
  let townLabel = document.createElement('label');
  townLabel.className = 'parcha-select-labels';
  townLabel.innerText = 'ТНП:';
  townSelect.id = 'parcha-town';
  townSelect.classList = 'form-control';
  townSelect.disabled = true;
  townLabel.appendChild(townSelect);

  let sexSelect = document.createElement('select');
  let sexLabel = document.createElement('label');
  sexLabel.className = 'parcha-select-labels';
  sexLabel.innerText = 'Пол:';
  sexSelect.id = 'parcha-sex';
  sexSelect.classList = 'form-control';
  sexSelect.disabled = true;
  sexLabel.appendChild(sexSelect);

  let ageSelect = document.createElement('select');
  let ageLabel = document.createElement('label');
  ageLabel.className = 'parcha-select-labels';
  ageLabel.innerText = 'Возраст:';
  ageSelect.id = 'parcha-age';
  ageSelect.classList = 'form-control';
  ageSelect.disabled = true;
  ageLabel.appendChild(ageSelect);

  let statusSelect = document.createElement('select');
  let statusLabel = document.createElement('label');
  statusLabel.className = 'parcha-select-labels';
  statusLabel.innerText = 'Статус:';
  statusSelect.id = 'parcha-status';
  statusSelect.classList = 'form-control';
  statusSelect.disabled = true;
  statusLabel.appendChild(statusSelect);

  selectBlock.appendChild(operatorLabel);
  selectBlock.appendChild(dateLabel);
  selectBlock.appendChild(townLabel);
  selectBlock.appendChild(sexLabel);
  selectBlock.appendChild(ageLabel);
  selectBlock.appendChild(statusLabel);

  return selectBlock;

}
/*
var dataSet = [
  ['parcha51-3', '10.03.2020', '554 - город с численностью до 50 тыс.чел. пгт', '501 - Женский', '507 - 50-59 лет', 'Принято'],
  ['parcha51-4', '09.03.2020', '554 - город с численностью до 50 тыс.чел. пгт', '502 - Мужской', '506 - 30-49 лет', 'Принято']
];
*/

function renderTbl() {
  let wrapDiv = document.createElement('div');
  wrapDiv.id = 'parcha-wrap';
  let tableNode = document.createElement('table');
  tableNode.id = 'parcha-table';
  tableNode.className = 'display';
  tableNode.width = '100%';
  wrapDiv.appendChild(tableNode);
  return wrapDiv;
}

function renderParchaTbl() {
  let resultNode = document.getElementById('control-result');
  resultNode.appendChild(renderTbl());

  parchaTable = $('#parcha-table').DataTable({
    data: filteredArrayOfParchaSheeets,
    responsive: true,
    searching: false,
    columns: [
      {title: 'id', data: 'id'},
      {title: 'Планшет', data: 'user'},
      {title: 'Дата', data: 'date'},
      {title: 'Тип населенного пункта', data: 'townType'},
      {title: 'Пол', data: 'gender'},
      {title: 'Возраст', data: 'age'},
      {title: 'Статус', data: 'status'},
      {},
      {}
    ],
    columnDefs: [
      {
        'targets': -2,                    // предпоследний столбец
        'orderable': false,
        'data': null,
        'width': '70px',
        'defaultContent': 'Просмотр/Карта'
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

// функция обработки события добавления xml
function loadXmlFile() {
  let xmlFile = this.files[0];
  let reader = new FileReader();
  reader.onload = function (e) {
    let result = reader.result;
    parseLoadedXml(result);
  };
  reader.readAsText(xmlFile);
}

function parseLoadedXml(result) {
  let
    doc = tryParseXML(result);
  if (doc) {
    let poll = doc.getElementsByTagName('opros')[0];
    let sheets = poll.children;                                             // коллекция Шитов
    let length = sheets.length;
    let temp = [];
    for (let i = 0; i < length; i++) {                                      // xml объект Шита
      let sheetId = sheets[i].attributes.id.value;
      let data = sheets[i].attributes;
      let questions = sheets[i].children;
      let length = questions.length;
      let poolOfQuestions = {}, answersRow = [];
      for (let i = 0; i < length; i++) {
        let question = questions[i];
        let qNumber = question.attributes[0].value;
        let answers = question.children;
        let aLength = answers.length;
        let poolOfAnswers = [];
        for (let j = 0; j < aLength; j++) {
          let answerCode = answers[j].attributes[0].value;
          poolOfAnswers[j] = answerCode;
          answersRow.push(answerCode);
        }
        poolOfQuestions[qNumber] = poolOfAnswers;
      }
      // TODO надо указать в настройках конструктора !!!!!!!!!!!!!!!!!!!!!
      data.gender = questions[23].children[0].attributes[0].value;
      data.age = questions[24].children[0].attributes[0].value;
      data.town = questions[28].children[0].attributes[0].value;
      temp[i] = new parchaSheet(data, poolOfQuestions, answersRow, sheets[i]);
      // let needQuestions = [23, 24, 28];
      // if (needQuestions.includes(+qNumber)) {
      // }
    }
    arrayOfParchaSheeets = temp;
    filteredArrayOfParchaSheeets = temp;

    // let headerNode = document.getElementById('analytic-header');
    // let mapBtn = document.createElement('a');
    // mapBtn.id = 'maps-me';
    // mapBtn.innerText = 'На карте';
    // headerNode.appendChild(mapBtn);
    // mapBtn.addEventListener('click', mapsMe, false);

    // let t = new XMLSerializer();
    // let tt = t.serializeToString(doc);
    // console.log(tt);

    parchaTable
      .clear()
      .rows.add(filteredArrayOfParchaSheeets)
      .draw();
    return;
  }
  let errorText = '<span style="font-weight: 600">Что-то пошло не так!</span><br>Ошибка при анализе XML. См. консоль';
  initNoty(errorText, 'warning');

}

// функия проверки корректности XML
function tryParseXML(xmlString) {
  var parser = new DOMParser();
  var parsererrorNS = parser.parseFromString('INVALID', 'text/xml').getElementsByTagName("parsererror")[0].namespaceURI;
  var dom = parser.parseFromString(xmlString, 'text/xml');
  if (dom.getElementsByTagNameNS(parsererrorNS, 'parsererror').length > 0) {
    console.log(dom.childNodes[1].innerHTML);
    return false;
  }
  return dom;
}

function initFilterSelects() {

}

function parseSelectedMarkers(markers) {
  let detailData = {};
  renderParchaResults(detailData);
}


function mapsMe() {
  let selectedMarkers, childCount;
  let jc = $.confirm({
    title: ' ',
    columnClass: 'xlarge',
    content: '<div id="map"></div>',
    onContentReady: function () {
      let self = this;
      this.buttons.ok.disable();
      let map = L.map('map').setView([67.959, 33.061], 7);
      L.tileLayer('http://182.11.57.17/osm_tiles/{z}/{x}/{y}.png', {
        attribution: '&copy; ' + 'СпецСвязь ФСО России',
        maxZoom: 18
      }).addTo(map);
      // setTimeout(function() {
      //   map.invalidateSize();
      // }, 100);
      let m = new L.MarkerClusterGroup({
        zoomToBoundsOnClick: false
      });

      m.on('clusterclick', function (a) {
        // let latLngBounds = a.layer.getBounds();
        if (a.originalEvent.ctrlKey) {
          selectedMarkers = selectedMarkers.concat(a.layer.getAllChildMarkers());
          childCount += a.layer.getChildCount();
        } else {
          selectedMarkers = a.layer.getAllChildMarkers();
          childCount = a.layer.getChildCount();
        }
        self.$title[0].textContent = 'Выбрано: ' + childCount + ' объектов';
        self.buttons.ok.enable();
      });

      if (filteredArrayOfParchaSheeets) {
        filteredArrayOfParchaSheeets.forEach(function (sheet, i) {
          let marker = L.marker([sheet.endLt, sheet.endLn], {
            id: sheet.id,
            title: sheet.user
          }).on('click', markerClick);
          marker.bindPopup(
            '<strong>ID: </strong>' + sheet.id + '<br>' +
            '<strong>Планшет: </strong>' + sheet.user + '<br>' +
            '<strong>Дата: </strong>' + sheet.date + '<br>' +
            '<strong> Координаты: </strong>' + sheet.endLt + ' с.ш. | ' + sheet.endLn + ' в.д.' + '<br>'
          );
          m.addLayer(marker);
          // marker.addTo(map);
        });
        map.addLayer(m);
      }
    },
    buttons: {
      ok: {
        btnClass: 'btn-blue',
        text: 'Выбрать',
        action: function () {
          jc.close();
          filterSelectedMarkers(selectedMarkers);
        }
      },
      cancel: {
        text: 'НАЗАД'
      }
    }

  });
}

function markerClick(e) {
  console.log(e);
}

function filterSelectedMarkers(markers) {
  markers.forEach(function (marker, index) {
    let id = marker.options.id;
    let sheet = sheeetObjs[id];
    let questions = sheet.children;
    let length = questions.length;
    let data = sheet.attributes;
    let pollOfAnswers = [];
    for (let i = 0; i < length; i++) {
      let question = questions[i];
      let qNumber = question.attributes[0].value;
      let answer = question.children[0].attributes[0].value;
      pollOfAnswers.push(answer);
    }
  });
}

function renderRowAnswers() {
  let jc = $.confirm({
    title: 'Raw данные',
    columnClass: 'xlarge',
    content: '<div id="parcha-raw-data"></div>',
    onContentReady: function () {
      let self = this;


    },
    buttons: {
      cancel: {
        text: 'НАЗАД'
      }
    }

  });




  let divNode = document.createElement('div');
  for (let key in sheets) {
    let spanNode = document.createElement('span');
    let brNode = document.createElement('br');
    // spanNode.innerText = sheets[key].user + ' - ' + sheets[key].answers.toString();
    spanNode.innerText = sheets[key].answers.toString();
    divNode.appendChild(spanNode);
    divNode.appendChild(brNode);
  }
}

function unloadData() {

}

