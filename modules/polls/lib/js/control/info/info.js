function showPollInfo() {
  let infoNode = document.createElement('div');
  let idNode = document.createElement('div');
  idNode.innerText = 'ID: ' + pollCounstructor.id;
  let titleNode = document.createElement('div');
  titleNode.innerText = 'Наименование: ' + pollCounstructor.title;
  let qNum = document.createElement('div');
  qNum.innerText = 'Количество вопросов: ' + pollCounstructor.numberOfQuestions;
  let aNUm = document.createElement('div');
  aNUm.innerText = 'Количество ответов: ' + pollCounstructor.numberOfAnswers;
  infoNode.appendChild(idNode);
  infoNode.appendChild(titleNode);
  infoNode.appendChild(qNum);
  infoNode.appendChild(aNUm);
  $.alert({
    title: 'Инфо ' + pollCounstructor.code,
    content: infoNode,
    columnClass: 'medium',
    animateFromElement: false
  });
}