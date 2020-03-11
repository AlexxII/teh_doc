let arrayOfRespondents;

function prepareData(config) {
  arrayOfRespondents = {};
  let respondents = config.respondent;
  let results = config.results;
  for (let key in respondents) {
    let respondent = respondents[key];
    let id = respondent.respondent_id;
    arrayOfRespondents[id] = [];
  }
  results.forEach(function (result, index) {
    let out = result.answer_code;
    if (result.ex_answer !== '') {
      out += ' ' + result.ex_answer;
    }
    arrayOfRespondents[result.respondent_id].push(out);
  });
}

function showArrayOfCodes() {
  let headerNode = document.getElementById('analytic-header');
  let resultNode = document.getElementById('analytic-result');
  let textAreaNode = document.createElement('textarea');
  textAreaNode.id = 'analytic-result-text';
  textAreaNode.cols = '150';
  textAreaNode.rows = '30';
  resultNode.innerHTML = '';
  resultNode.append(textAreaNode);
  headerNode.innerHTML = '';
  for (let key in arrayOfRespondents) {
    let result = arrayOfRespondents[key];
    result += ',999';
    result += '\r\n';
    let textNode = document.createTextNode(result);
    textAreaNode.append(textNode);
  }
}
