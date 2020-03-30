function initControlModule(pollId) {
  let url = '/polls/control/control';
  let dataUrl = '/polls/control/control/get-poll-data?id=' + pollId;
  loadExContentEx(url, () => loadPollConfig(pollId, mainInit, dataUrl));
}

function loadPollConfig(id, callback, dataUrl) {
  $.ajax({
    url: dataUrl,
    method: 'get'
  }).done(function (response) {
    if (response.code) {
      callback(response.data.data[0]);
    } else {
      console.log(response.data.message);
    }
  }).fail(function () {
    console.log('Failed to load poll config');
  });
}

$(document).on('click', '.control-poll-info', initInfoModule)
  .on('click', '.control-poll-results', initResultModule)
  .on('click', '.control-poll-parcha', initParchaModule)
  .on('click', '.control-poll-construct', initConstructModule)
  .on('click', '.control-batch-input', initBatchInModule)
  .on('click', '.control-statistic', initStatisticModule)
  .on('click', '.control-poll-tests', intiTestsModule);

var pollCounstructor, pollBatchIn, mainPollConfig, tailIp;

function mainInit(config) {
  mainPollConfig = config;
  console.log(config);
  // глобальные объекты
  pollCounstructor = new PollConstructor(config);
  pollBatchIn = new Batch(config);
  prepareData(config);
  renderPollTitle(config.code);
  tailIp = config.tailIp;
  NProgress.done();
}

function renderPollTitle(code) {
  let titlePlacement = document.getElementById('poll-title');
  let hNode = document.createElement('h4');
  let title = document.createTextNode(code);
  hNode.appendChild(title);
  titlePlacement.appendChild(hNode);
}

function initInfoModule(config) {

}

function initResultModule() {
  startResultAnalyze();
}

function initParchaModule() {
  startParchaAnalyze();
}

function initConstructModule(e) {
  let view = e.target.id;
  startConstruct(view);
}

function initBatchInModule() {
  startBatchIn();
}

function initStatisticModule() {

}

function intiTestsModule() {
  testIniParser();
}

function testIniParser() {
  let headerNode = document.getElementById('control-header');
  headerNode.innerHTML = '';
  let resultNode = document.getElementById('control-result');
  resultNode.innerHTML = '';
  let footerNode = document.getElementById('control-footer');
  footerNode.innerHTML = '';

  let wrapDiv = document.createElement('div');
  wrapDiv.style['margin-top'] = '20px';

  let iniArea = document.createElement('textarea');
  iniArea.className = 'ini-editor';
  iniArea.style.resize = 'vertical';
  iniArea.maxLength = '50000';
  iniArea.cols = '150';
  iniArea.rows = '20';
  wrapDiv.appendChild(iniArea);

  let saveBtn = document.createElement('button');
  saveBtn.classList = 'btn btn-sm btn-success';
  saveBtn.innerHTML = 'Сохранить';
  saveBtn.addEventListener('click', () => { parseIni(iniArea); } , false);

  let loadBtn = document.createElement('button');
  loadBtn.style['margin-left'] = '10px';
  loadBtn.classList = 'btn btn-sm btn-info';
  loadBtn.innerHTML = 'Скрипт';
  loadBtn.addEventListener('click', loadScript , false);

  headerNode.appendChild(saveBtn);
  headerNode.appendChild(loadBtn);
  resultNode.appendChild(wrapDiv);
}

function loadScript() {
  let sScript = document.createElement('script');
  sScript.src = 'js/iniparser.js';
  // let text = '$(".js-data-array").select2({' +
  //   'placeholder: "Выберите ответ",' +
  //   '});';
  // let sText = document.createTextNode(text);
  // sScript.appendChild(sText);
  document.head.append(sScript);
  // setTimeout(() => document.body.removeChild(sScript), 150);          //TODO слабое место с ожиданием
}
