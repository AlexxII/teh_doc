let parchaTable, filteredArrayOfParchaSheeets,
  arrayOfParchaSheeets, arrayOfParchaSheeetsEx,
  arrayOfAnswersCodes, parchaSelects = {};

function startParchaAnalyze() {
  let headerNode = document.getElementById('control-header');
  headerNode.innerHTML = '';
  let resultNode = document.getElementById('control-result');
  resultNode.innerHTML = '';
  let footerNode = document.getElementById('control-footer');
  footerNode.innerHTML = '';

  renderParchaHeader();
  renderParchaTbl();
  prepareParchaData();
}

function prepareParchaData() {
  let answers = mainPollConfig.answers;
  arrayOfAnswersCodes = {};
  let length = answers.length;
  for (let i = 0; i < length; i++) {
    let code = answers[i].code;
    arrayOfAnswersCodes[code] = answers[i].title;
  }
}

// основной объект
function parchaSheet(data, aObject) {
  this.id = data.id.value;
  this.user = data.usr_intrv.value;
  this._date = data.date_intrv.value;
  // переменные данные
  this._propertyOneText = data.propertyOneText;
  this._propertyTwoText = data.propertyTwoText;
  this._propertyThreeText = data.propertyThreeText;
  this._propertyFourText = data.propertyFourText;

  this._propertyOne = data.propertyOne;
  this._propertyTwo = data.propertyTwo;
  this._propertyThree = data.propertyThree;
  this._propertyFour = data.propertyFour;
  //
  this.startLt = data['start-lan'].value;
  this.endLt = data['end-lat'].value;
  this.startLn = data['start-lon'].value;
  this.endLn = data['end-lon'].value;
  this._status = +data.status.value;
  this.answers = data.poolOfAnswers;
  this.answersRaw = data.answersRaw;
  this.aObject = aObject;                                             // ссылка на шит в исходном xml объекте
  this.error = data.error;

  this.date = function () {
    return this._date;
  };

  // объект дата
  let dateObj = null;
  Object.defineProperty(this, 'dateObj', {
    get: function () {
      return dateObj;
    },
    set: function (date) {
      let pattern = /(\d{2})\.(\d{2})\.(\d{4})/;
      let dt = new Date(date.replace(pattern,'$3-$2-$1'));
      dateObj = dt;
    },
    enumerable: true,
    configurable: true
  });

  this.propertyOneText = function () {
    if (this._propertyOneText != null) {
      return this._propertyOneText;
    } else {
      return '-';
    }
  };

  this.propertyTwoText = function () {
    if (this._propertyTwoText != null) {
      return this._propertyTwoText;
    } else {
      return '-';
    }
  };

  this.propertyThreeText = function () {
    if (this._propertyThreeText != null) {
      return this._propertyThreeText;
    } else {
      return '-';
    }
  };

  this.propertyFourText = function () {
    if (this._propertyFourText != null) {
      return this._propertyFourText;
    } else {
      return '-';
    }
  };

  this.status = function () {
    if (this._status === 1) {
      return 'Принято';
    } else {
      return 'Отклонено';
    }
  };
  this.propertyOne = function () {
    if (this._propertyOne != null) {
      return this._propertyOne;
    } else {
      return '-';
    }
  };
  this.propertyTwo = function () {
    if (this._propertyTwo != null) {
      return this._propertyTwo;
    } else {
      return '-';
    }
  };
  this.propertyThree = function () {
    if (this._propertyThree != null) {
      return this._propertyThree;
    } else {
      return '-';
    }
  };

  this.propertyFour = function () {
    if (this._propertyFour != null) {
      return this._propertyFour;
    } else {
      return '-';
    }
  };

}

$('.pool-dates').datepicker({
  format: 'd MM yyyy г.',
  autoclose: true,
  language: "ru",
  startView: "days",
  minViewMode: "days",
  clearBtn: true,
  todayHighlight: true,
  daysOfWeekHighlighted: [0, 6]
});

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

  let deleteSheetNode = document.createElement('a');
  deleteSheetNode.innerText = 'Удалить';
  deleteSheetNode.id = 'parcha-delete-sheet';
  deleteSheetNode.addEventListener('click', deleteSheetData, false);

  let unloadRawNode = document.createElement('a');
  unloadRawNode.innerText = 'Выгрузить данные (Raw)';
  unloadRawNode.id = 'parcha-unload-data';
  unloadRawNode.addEventListener('click', renderRowAnswers, false);

  let btnsWrap = document.createElement('div');
  btnsWrap.className = 'parcha-btns';

  btnsWrap.appendChild(mapBtn);
  btnsWrap.appendChild(deleteSheetNode);
  btnsWrap.appendChild(unloadXmlNode);
  btnsWrap.appendChild(unloadRawNode);

  let hrNode = document.createElement('hr');
  wrapDiv.appendChild(xmlUploadTmpl());
  wrapDiv.appendChild(blockOfSelects());
  wrapDiv.appendChild(hrNode);
  wrapDiv.appendChild(btnsWrap);
  headerNode.appendChild(wrapDiv);
}

function deleteSheetData() {
  let selectedData = parchaTable.rows({selected: true}).data();
  if (selectedData.length !== 0) {
    let length = selectedData.length;
    for (let i = 0; i < length; i++) {
      let obj = selectedData[i].aObject;
      docOfSheets.documentElement.children[1].removeChild(obj)
    }
  }
}

function unloadData() {
  let testXml = docOfSheets;
  let t = new XMLSerializer();
  let tt = t.serializeToString(testXml);
  // console.log(tt);
  // let jc = $.confirm({
  //   title: 'Raw данные',
  //   columnClass: 'xlarge',
  //   content: '<div id="parcha-raw-data"></div>',
  //   onContentReady: function () {
  //     this.setContentPrepend(tt);
  //   },
  //   buttons: {
  //     cancel: {
  //       text: 'НАЗАД'
  //     }
  //   }
  // });
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
  operatorSelect.addEventListener('change', filterByOperator, false);
  parchaSelects.operatorSelect = operatorSelect;

  let dateInput = document.createElement('input');
  let dateLabel = document.createElement('label');
  dateLabel.innerText = 'Датa:';
  dateLabel.className = 'parcha-select-labels';
  dateInput.readOnly = true;
  dateInput.id = 'parcha-date';
  dateInput.classList = 'form-control';
  dateInput.placeholder = 'Выберите дату';
  // dateInput.disabled = true;
  dateLabel.appendChild(dateInput);
  // dateInput.addEventListener('input', filterByDate, false);
  $(dateInput).datepicker({
    format: 'd MM yyyy г.',
    autoclose: true,
    language: "ru",
    startView: "days",
    minViewMode: "days",
    clearBtn: true,
    todayHighlight: true,
    daysOfWeekHighlighted: [0, 6]
  }).on('changeDate', filterByDate);
  parchaSelects.dataInput = dateInput;

  let townSelect = document.createElement('select');
  let townLabel = document.createElement('label');
  townLabel.className = 'parcha-select-labels';
  townLabel.innerText = 'ТНП:';
  townSelect.id = 'parcha-town';
  townSelect.classList = 'form-control';
  townSelect.disabled = true;
  townLabel.appendChild(townSelect);
  townSelect.addEventListener('change', filterByTown, false);
  parchaSelects.townSelect = townSelect;

  let sexSelect = document.createElement('select');
  let sexLabel = document.createElement('label');
  sexLabel.className = 'parcha-select-labels';
  sexLabel.innerText = 'Пол:';
  sexSelect.id = 'parcha-sex';
  sexSelect.classList = 'form-control';
  sexSelect.disabled = true;
  sexLabel.appendChild(sexSelect);
  sexSelect.addEventListener('change', filterBySex, false);
  parchaSelects.sexSelect = sexSelect;

  let ageSelect = document.createElement('select');
  let ageLabel = document.createElement('label');
  ageLabel.className = 'parcha-select-labels';
  ageLabel.innerText = 'Возраст:';
  ageSelect.id = 'parcha-age';
  ageSelect.classList = 'form-control';
  ageSelect.disabled = true;
  ageLabel.appendChild(ageSelect);
  ageSelect.addEventListener('change', filterByAge, false);
  parchaSelects.ageSelect = ageSelect;

  let statusSelect = document.createElement('select');
  let statusLabel = document.createElement('label');
  statusLabel.className = 'parcha-select-labels';
  statusLabel.innerText = 'Статус:';
  let dOption = document.createElement('option');
  let pOption = document.createElement('option');
  let nOption = document.createElement('option');
  dOption.text = '---';
  dOption.value = null;
  pOption.text = 'Принятые';
  pOption.value = 1;
  nOption.text = 'Отклоненные';
  nOption.value = 0;
  statusSelect.add(dOption);
  statusSelect.add(pOption);
  statusSelect.add(nOption);
  parchaSelects.statusSelect = statusSelect;

  statusSelect.id = 'parcha-status';
  statusSelect.classList = 'form-control';
  // statusSelect.disabled = true;
  statusLabel.appendChild(statusSelect);
  statusSelect.addEventListener('change', filterByStatus, false);

  selectBlock.appendChild(operatorLabel);
  selectBlock.appendChild(dateLabel);
  selectBlock.appendChild(townLabel);
  selectBlock.appendChild(sexLabel);
  selectBlock.appendChild(ageLabel);
  selectBlock.appendChild(statusLabel);

  return selectBlock;
}


function filterByOperator() {

}

function filterByDate(e) {
  console.log(e);
  let date = e.date;



}

function filterByTown() {

}

function filterBySex() {

}

function filterByAge() {

}

function filterByStatus() {

}

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
    data: tblData(),
    responsive: true,
    searching: false,
    columns: [
      {title: 'id', data: 'id'},
      {title: 'Планшет', data: 'user'},
      {title: 'Дата', data: 'date'},
      {title: 'Тип населенного пункта', data: 'propertyOneText'},
      {title: 'Пол', data: 'propertyTwoText'},
      {title: 'Возраст', data: 'propertyThreeText'},
      {title: 'Статус', data: 'status'},
      {},
      {}
    ],
    lengthMenu: [[50, 150, 300, 600, -1], [50, 150, 300, 600, 'Все']],
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
      style: 'os',
      selector: 'td:last-child',
    },
    language: {
      url: '/lib/ru.json'
    }
  });
}

function tblData() {
  let tblData;
  if (filteredArrayOfParchaSheeets) {
    tblData = Object.values(filteredArrayOfParchaSheeets);
    return tblData;
  }
  return [];
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

let docOfSheets;

function parseLoadedXml(result) {
  docOfSheets = tryParseXML(result);

  if (docOfSheets) {
    let poll = docOfSheets.getElementsByTagName('opros')[0];
    let sheets = poll.children;                                             // коллекция Шитов
    let length = sheets.length;
    let temp = [], tempEx = {};

    // TODO надо указать в настройках конструктора !!!!!!!!!!!!!!!!!!!!!
    let criteria = [28, 23, 24];
    let properties = ['propertyOne', 'propertyTwo', 'propertyThree', 'propertyFour'];
    let criteriaLength = criteria.length;

    for (let i = 0; i < length; i++) {                                      // xml объект Шита
      let sheetId = sheets[i].attributes.id.value;
      let data = sheets[i].attributes;
      let questions = sheets[i].children;
      let length = questions.length;
      let poolOfAnswers = {}, answersRaw = [];
      for (let i = 0; i < length; i++) {
        let question = questions[i];
        let qNumber = question.attributes[0].value;
        let answers = question.children;
        let aLength = answers.length;
        let allAnswers = [];
        for (let j = 0; j < aLength; j++) {
          let answerCode = answers[j].attributes[0].value;
          allAnswers[j] = answerCode;
          answersRaw.push(answerCode.padStart(3, '0'));
        }
        poolOfAnswers[qNumber] = allAnswers;
        let code = answers[0].attributes[0].value;
        for (let i = 0; i < criteriaLength; i++) {
          if (qNumber == criteria[i]) {
            let name = properties[i];
            let title = properties[i] + 'Text';
            data[name] = code;
            data[title] = arrayOfAnswersCodes[code];
          }
        }
      }
      data.poolOfAnswers = poolOfAnswers;
      data.answersRaw = answersRaw;
      data.error = null;

      let sheet = new parchaSheet(data, sheets[i]);
      sheet.dateObj = data.date_intrv.value;
      temp[i] = sheet;
      tempEx[sheetId] = sheet;
    }
    arrayOfParchaSheeets = temp;
    arrayOfParchaSheeetsEx = tempEx;
    filteredArrayOfParchaSheeets = tempEx;

    // let needQuestions = [23, 24, 28];
    // if (needQuestions.includes(+qNumber)) {
    // }
    // let headerNode = document.getElementById('analytic-header');
    // let mapBtn = document.createElement('a');
    // mapBtn.id = 'maps-me';
    // mapBtn.innerText = 'На карте';
    // headerNode.appendChild(mapBtn);
    // mapBtn.addEventListener('click', mapsMe, false);

    // let t = new XMLSerializer();
    // let tt = t.serializeToString(doc);
    // console.log(tt);

    initFilterOptions();

    console.log(arrayOfParchaSheeetsEx);
    parchaTable
      .clear()
      .rows.add(tblData())
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

function initFilterOptions(data) {
//  parchaSelects
}

function parseSelectedMarkers(markers) {
  let detailData = {};
  renderParchaResults(detailData);
}


function mapsMe() {
  let selectedMarkers, childCount, sheets;
  let selectedData = parchaTable.rows({selected: true}).data();
  if (selectedData.length !== 0) {
    sheets = {};
    let length = selectedData.length;
    for (let i = 0; i < length; i++) {
      let id = selectedData[i].id;
      sheets[id] = selectedData[i];
    }
  } else {
    sheets = filteredArrayOfParchaSheeets;
  }

  // datatables select all rows

  let jc = $.confirm({
    title: ' ',
    columnClass: 'xlarge',
    content: '<div id="map"></div>',
    onContentReady: function () {
      let self = this;
      this.buttons.ok.disable();
      let map = L.map('map').setView([67.959, 33.061], 7);
      L.tileLayer('http://' + tailIp + '/osm_tiles/{z}/{x}/{y}.png', {
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
      if (sheets) {
        // filteredArrayOfParchaSheeets.forEach(function (sheet, i) {
        for (let key in sheets) {
          let sheet = sheets[key];
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
        }
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
  // console.log(e);
}

function filterSelectedMarkers(markers) {
  let sheeetObjs = arrayOfParchaSheeetsEx;
  let result = {};
  markers.forEach(function (marker, index) {
    let id = marker.options.id;
    result[id] = sheeetObjs[id];
  });
  filteredArrayOfParchaSheeets = result;
  parchaTable
    .clear()
    .rows.add(tblData())
    .draw();
}

function renderRowAnswers() {
  let sheets;
  let selectedData = parchaTable.rows({selected: true}).data();
  if (selectedData.length !== 0) {
    sheets = {};
    let length = selectedData.length;
    for (let i = 0; i < length; i++) {
      let id = selectedData[i].id;
      sheets[id] = selectedData[i];
    }
  } else {
    sheets = filteredArrayOfParchaSheeets;
  }
  let jc = $.confirm({
    title: 'Raw данные',
    columnClass: 'xlarge',
    content: '<div id="parcha-raw-data"></div>',
    onContentReady: function () {
      let self = this;
      let divNode = document.createElement('div');
      let result = '';
      for (let key in sheets) {
        let spanNode = document.createElement('span');
        let brNode = document.createElement('br');
        // spanNode.innerText = sheets[key].user + ' - ' + sheets[key].answers.toString();
        // result += sheets[key].answersRaw.toString() + '\n';
        spanNode.innerText = sheets[key].answersRaw.toString() + ',999';
        divNode.appendChild(spanNode);
        divNode.appendChild(brNode);
      }
      var XMLS = new XMLSerializer();
      var divHtml = XMLS.serializeToString(divNode);
      self.setContentPrepend(divHtml);
    },
    buttons: {
      cancel: {
        text: 'НАЗАД'
      }
    }
  });
}

