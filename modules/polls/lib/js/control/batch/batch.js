function startBatchIn() {
  let headerNode = document.getElementById('control-header');
  headerNode.innerHTML = '';
  let resultNode = document.getElementById('control-result');
  resultNode.innerHTML = '';

  renderBatchHeader()
}

function renderBatchHeader() {
  let headerNode = document.getElementById('control-header');
  headerNode.innerHTML = '';
  let wrapDiv = document.createElement('div');
  wrapDiv.className = 'batch-upload';
  wrapDiv.appendChild(batchResultUpload());
  headerNode.appendChild(wrapDiv);
}

function batchResultUpload() {
  let divForm = document.createElement('div');
  divForm.className = 'form-group';
  let labelNode = document.createElement('label');
  let text = document.createTextNode('Добавить файл с результатами:');
  labelNode.appendChild(text);
  labelNode.htmlFor = 'batchupload';
  divForm.appendChild(labelNode);
  let inputNode = document.createElement('input');
  inputNode.className = 'form-control-file';
  inputNode.id = 'batchupload';
  inputNode.type = 'file';
  divForm.appendChild(inputNode);
  inputNode.addEventListener('change', loadAndParseOprFile, false);
  return divForm;
}

function loadAndParseOprFile(e) {
  e.preventDefault();
  var selectedFile = document.getElementById('batchupload').files[0];
  var reader = new FileReader();
  moment.locale('ru');
  reader.onload = function (e) {
    oprData = e.target.result;
    pollBatchIn.parseOprFile(oprData, renderResult);
  };
  reader.readAsText(selectedFile);
  // renderListBatchView();
}

function renderResult() {
  let reposnondetsAnswers = pollBatchIn.respondentsPool;
  let mainDiv = document.createElement('div');
  mainDiv.className = 'batch-grid';
  for (let key in reposnondetsAnswers) {
    let obj = reposnondetsAnswers[key];
    let divNode = document.createElement('div');
    divNode.className = 'sheet';
    divNode.id = key;
    mainDiv.append(divNode);
  }
  let resultNode = document.getElementById('control-result');
  resultNode.innerHTML = '';
  resultNode.appendChild(mainDiv);
}

function renderListBatchView(key) {
  $.alert({
    title: 'Опрос ROS20-03',
    content: pollBatchIn.renderList(key),
    columnClass: 'col-md-12',
    animateFromElement: false,
    buttons: {
      ok: {
        text: 'OK',
        btnClass: 'btn-info',
        action: function () {
          pollBatchIn.clear();
        }
      }
    }
  });
}

$(document).on('click', '.sheet', function (e) {
  let key = this.id;
  renderListBatchView(key);
});
