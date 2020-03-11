$(document).on('change', '#batchupload', function (e) {
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
});

function renderResult() {
  let reposnondetsAnswers = pollBatchIn.respondentsPool;
  $('.poll-batch-wrap').html('');
  let mainDiv = document.createElement('div');
  mainDiv.className = 'batch-grid';
  for (let key in reposnondetsAnswers) {
    let obj = reposnondetsAnswers[key];
    let divNode = document.createElement('div');
    divNode.className = 'sheet';
    divNode.id = key;
    mainDiv.append(divNode);
  }
  $('.poll-batch-wrap').append(mainDiv);
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
