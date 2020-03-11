$(document).on('click', '.get-pdf', function (e) {
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
      if (selectedData[i][3] == '' && selectedData[i][4] != '') {
        return selectedData[i][4] + ' / р';
      } else if (selectedData[i][3] != '' && selectedData[i][4] != '') {
        return selectedData[i][3] + ' / т \n' + selectedData[i][4] + ' / р';
      } else if (selectedData[i][4] == '' && selectedData[i][3] != '') {
        return selectedData[i][3] + ' / т';
      } else {
        return '-'
      }
    };
    var obj = {};
    obj['№'] = i + 1;
    obj['Дата'] = selectedData[i][1];
    obj['Время'] = time();
    obj['Тип ВКС'] = selectedData[i][5];
    obj['Студии'] = selectedData[i][6];
    obj['Абонент'] = selectedData[i][7] + ' ' + selectedData[i][8];
    obj['Распоряжение'] = selectedData[i][9];
    vksData.push(obj);
  }

  var dd = {
    header: [
      {text: pdfHeader(), style: 'headerTitle'}
    ],
    content: [
      buildTable(
        vksData,
        ['№', 'Дата', 'Время', 'Тип ВКС', 'Студии', 'Абонент', 'Распоряжение'],
        [20, 60, 60, '*', '*', '*', '*'],
        true,
        [
          {text: '№', style:'tableHeader', cellsStyle: 'impCell'},
          {text: 'Дата', style:'tableHeader', cellsStyle: 'impCell'},
          {text: 'Время', style:'tableHeader', cellsStyle: 'impCell'},
          {text: 'Тип ВКС', style:'tableHeader', cellsStyle: 'commonCell'},
          {text: 'Студии', style:'tableHeader', cellsStyle: 'commonCell'},
          {text: 'Абонент', style:'tableHeader', cellsStyle: 'commonCell'},
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
        alignment: 'center'
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


