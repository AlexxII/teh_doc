$(document).on('click', '.get-archive-pdf', function (e) {
  var tableIdAttr = $(e.currentTarget).data('table');
  var pdfHeader = function() {
    return $(e.currentTarget).data('pdfHeader');
  };
  var mTable = $('#' + tableIdAttr).DataTable();
  var selectedData = mTable.rows({selected: true}).data();
  console.log(selectedData);
  var count = selectedData.length;
  var vksData = [];
  for (var i = 0; i < count; i++) {
    var time = function () {
      return selectedData[i][15] + ' - ' + selectedData[i][16] +  '/ т \n' +
        selectedData[i][17] + ' - ' + selectedData[i][18] +  '/ р';
    };
    var obj = {};
    obj['№'] = i + 1;
    obj['Дата'] = selectedData[i][1];
    obj['Время'] = time();
    obj['Тип ВКС'] = selectedData[i][4];
    obj['Студии'] = selectedData[i][5];
    obj['Абонент'] = selectedData[i][6] + '\n' + selectedData[i][14];
    obj['Абонент субъекта'] = selectedData[i][21] + '\n' + selectedData[i][22];
    obj['Распоряжение'] = selectedData[i][7];
    vksData.push(obj);
  }

  var dd = {
    pageMargins: [ 15, 25, 15, 25],
    header: [
      {text: pdfHeader(), style: 'headerTitle'}
    ],
    content: [
      buildTable(
        vksData,
        ['№', 'Дата', 'Время', 'Тип ВКС', 'Студии', 'Абонент', 'Абонент субъекта', 'Распоряжение'],
        [20, 45, 60, 45, 50, '*', '*', '*'],
        true,
        [
          {text: '№', style:'tableHeader', cellsStyle: 'commonCell'},
          {text: 'Дата', style:'tableHeader', cellsStyle: 'commonCell'},
          {text: 'Время', style:'tableHeader', cellsStyle: 'commonCell'},
          {text: 'Тип ВКС', style:'tableHeader', cellsStyle: 'commonCell'},
          {text: 'Студии', style:'tableHeader', cellsStyle: 'commonCell'},
          {text: 'Абонент', style:'tableHeader', cellsStyle: 'commonCell'},
          {text: 'Абонент субъекта', style:'tableHeader', cellsStyle: 'commonCell'},
          {text: 'Распоряжение', style:'tableHeader', cellsStyle: 'commonCell'}
        ],
        '')
    ],
    styles: {
      tableHeader: {
        fontSize: 10,
        fillColor: '#d3d3d3',
        alignment: 'center',
        alignmentChild: 'center',
        bold: 'true',
        margin: [0, 5, 0, 0]
      },
      commonCell: {
        alignment: 'center',
      },
      impCell: {
        fontSize: 10,
        alignment: 'center',
        bold: 'true'
      },
      headerTitle: {
        fontSize: 12,
        alignment: 'center',
        margin: [0, 10, 0, 0]
      }
    },
    defaultStyle: {
      fontSize: 8,
      padding: [0, 10, 0, 0]
    }
  };
  pdfMake.createPdf(dd).open();
});


