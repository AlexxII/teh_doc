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

var pollCounstructor, pollBatchIn, mainPollConfig;

function mainInit(config) {
  mainPollConfig = config;
  console.log(mainPollConfig);
  pollCounstructor = new PollConstructor(config);
  pollBatchIn = new Batch(config);
  renderPollTitle(config.code);
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

}

function initParchaModule() {
  startParchaAnalyze();
}

function initConstructModule(e) {
  let view = e.target.id;
  startConstruct(view);
}

function initBatchInModule() {

}

function initStatisticModule() {

}

function intiTestsModule() {

}