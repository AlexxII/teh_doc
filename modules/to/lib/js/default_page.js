$(document).on('click', '.get-pdf', function (e) {
  var monthVal = $('.scheduler-info').data('month');
  var schedulerYear = $('.scheduler-info').data('year');

  var tblHeader = {
    nmb: '№ п.п.',
    title: 'Наименование оборудования, средства,\n канал связи',
    serial: 'Заводской номер средства,\n направление канала связи',
    type: 'Вид ТО',
    date: 'Дата\n (план./факт.)',
    admins: 'Ответственный\n за проведение ТО',
    audit: 'Проверка полноты и качества проведения ТО',
    toStart: 'начало ТО',
    toEnd: 'окончание ТО',
    surname: 'фамилия, инициалы',
    signature: 'подпись',
    auditDate: 'дата',
    mark: 'оценка',
    comments: 'Примечание'
  };

  var chief = 'Д.С. Врачев';                                                                                    // выбор при формировании
  var chiefRank = 'подполковник';                                                                               // настройки системы
  var chiefPosition = 'Заместитель начальника центра по информационно-аналитической работе – начальник ';       // нстройки системы

  var viceChief = 'В.Ю. Малышев';                                                                               // выбор при формировании
  var viceChiefRank = 'подполковник';                                                                           // настройки системы
  var viceChiefPosition = 'Заместитель начальника 4 отделения';                                                 // настройки системы

  var sighDate = '«\t» ' + monthNamesGenitive[monthVal].toLowerCase() + ' ' +  schedulerYear + ' г.';                   // дату
  var subunit = '4 отделения';                                                                                  // глобальные настройки
  var scheduleDate = monthNamesPrep[monthVal] + ' ' + schedulerYear + ' года';                                  // из графика
  var subscriptionNumber = '9/4/19/19/5-_______';

  var tableDatePlaceholder = '___.___.' + schedulerYear;
  var tableDatePlaceholderEx = '___.___.' + schedulerYear;                                                      // год устанавливается TODO:!!!!!!!

  var mainHeader = function () {
    return 'УТВЕРЖДАЮ\n' + chiefPosition +
      subunit + ' Центра специальной \n ' +
      'связи и информации Федеральной службы\n ' +
      'охраны Российской Федерации\n' +
      'в Мурманской области\n' +
      '\n ' + chiefRank + '\t\t\t\t\t' + chief + '\n' +
      '\n ' +
      sighDate;
  };
  var mainTitle = function () {
    return 'ПЛАН-ГРАФИК\n' +
      'технического обслуживания оборудования, средств и каналов специальной связи \n' +
      subunit + ' ЦССИ ФСО России в Мурманской области в ' + scheduleDate +
      '\n' + ' ';
  };

  var mainSubFooter = function () {
    return viceChiefPosition + '\n' + ' ЦССИ ФСО России в Мурманской области \n';
  };

  var mainSubFooterRight = function () {
    return viceChief;
  };

  var mainSubFooterLeft = function () {
    return viceChiefRank;
  };

  var mainSubFooterDate = function () {
    return '\n' + sighDate + '\n\n' + '№ ' + subscriptionNumber;
  };

  var executor = 'Лесин Сергей Николаевич';
  var caseNumber = '47';
  var preparationDate = '__' + '';

  var deepFooter = function () {
    return 'Отп. 1 экз.\n' +
      'Экз. № 1 – в дело №' + caseNumber + '\n' +
      'Исп., печ.' + executor + ', ' + subunit + ' ЦССИ ФСО России\n' +
      'в Мурманской области, АТС ОГВ (0815) 30-82\n' +
      'с экрана\n' + '__.04.2019'
  };

  var tableIdAttr = $(e.currentTarget).data('table');
  var mTable = $('#' + tableIdAttr).dataTable();
  var tableData = mTable.fnGetData();

  var mainTable = function () {
    var body = [];
    var header_1 =
      [
        {text: tblHeader.nmb, rowSpan: 2, style: 'tableHeader'},
        {text: tblHeader.title, rowSpan: 2, style: 'tableHeader'},
        {text: tblHeader.serial, rowSpan: 2, style: 'tableHeader'},
        {text: tblHeader.type, rowSpan: 2, style: 'tableHeader'},
        {text: tblHeader.date, colSpan: 2, style: 'tableHeader'},
        {},
        {text: tblHeader.admins, colSpan: 2, style: 'tableHeader'},
        {},
        {text: tblHeader.audit, colSpan: 4, style: 'tableHeader'},
        {}, {}, {},
        {text: tblHeader.comments, rowSpan: 2, style: 'tableHeader'}
      ];
    var header_2 =
      [
        {}, {}, {}, {},
        {text: tblHeader.toStart, style: 'tableHeader'},
        {text: tblHeader.toEnd, style: 'tableHeader'},
        {text: tblHeader.surname, style: 'tableHeader'},
        {text: tblHeader.signature, style: 'tableHeader'},
        {text: tblHeader.auditDate, style: 'tableHeader'},
        {text: tblHeader.mark, style: 'tableHeader'},
        {text: tblHeader.surname, style: 'tableHeader'},
        {text: tblHeader.signature, style: 'tableHeader'},
        {}
      ];
    var header_3 =
      [
        {text: '1', style: 'tableHeaderNum'},
        {text: '2', style: 'tableHeaderNum'},
        {text: '3', style: 'tableHeaderNum'},
        {text: '4', style: 'tableHeaderNum'},
        {text: '5', style: 'tableHeaderNum'},
        {text: '6', style: 'tableHeaderNum'},
        {text: '7', style: 'tableHeaderNum'},
        {text: '8', style: 'tableHeaderNum'},
        {text: '9', style: 'tableHeaderNum'},
        {text: '10', style: 'tableHeaderNum'},
        {text: '11', style: 'tableHeaderNum'},
        {text: '12', style: 'tableHeaderNum'},
        {text: '13', style: 'tableHeaderNum'}
      ];

    body.push(header_1);
    body.push(header_2);
    body.push(header_3);

    var parent = '';
    var parentCount = 0;
    var count = 1;
    var pattern = /(\d{4})-(\d{2})-(\d{2})/;
    tableData.forEach(function (val, i, ar) {
      var tempArray = [];
      if (val.parent !== '' && val.parent !== parent) {
        parentCount++;
        var parentTitle = val.parent + '\n в составе:';
        body.push([
          {text: parentCount, alignment: 'center'},
          {text: parentTitle, colSpan: 12, style: 'tableParent'},
          {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}
        ]);
        parent = val.parent;
        count = 1;
      }
      var date = val.plan_date;
      var planDateSt = date.replace(pattern, '$3.$2.$1') + '\n' + tableDatePlaceholder;
      var planDateEnd = planDateSt;
      body.push([
        {text: parentCount + '.' + count, style: 'commonCell'},
        {text: val.equipment, style: 'commonCell'},
        {text: val["s/n"], style: 'commonCell'},
        {text: val.toType, style: 'commonCell'},
        {text: planDateSt, style: 'commonCell'},
        {text: planDateEnd, style: 'commonCell'},
        {text: val.admin, style: 'commonCell'},
        {text: '', style: 'commonCell'},
        {text: tableDatePlaceholderEx, margin: [0,7,0,0]},
        {text: '', style: 'commonCell'},
        {text: val.auditor, style: 'commonCell'},
        {text: '', style: 'commonCell'},
        {text: '', style: 'commonCell'}
      ]);
      count++;
    });
    return body;
  };

  var dd = {
    pageSize: 'A4',
    pageOrientation: 'landscape',
    content: [
      {
        columns: [
          {width: '*', text: ''},
          {
            width: 300,
            text: mainHeader(),
            style: 'headerTitle'
          }
        ]
      },
      {text: mainTitle(), style: 'mainTitle'},
      {
        style: 'tableExample',
        table: {
          headerRows: 2,
          keepWithHeaderRows: 1,
          dontBreakRows: true,
          body: mainTable()
        }
      },
      {text: mainSubFooter(), style: 'mainFooter'},
      {
        columns: [
          {width: '*', text: mainSubFooterLeft(), style: 'footerLeftSide'},
          {
            width: 'auto',
            text: mainSubFooterRight(),
            style: 'footerRightSide'
          }
        ]
      },
      {text: mainSubFooterDate(), style: 'mainFooter', pageBreak: 'after'},
      {text: deepFooter(), style: 'deepFooter'},

    ],
    styles: {
      footerRightSide: {
        fontSize: 12,
        alignment: 'right'
      },
      footerLeftSide: {
        fontSize: 12,
        alignment: 'left'
      },
      headerTitle: {
        fontSize: 12,
        alignment: 'center',
        margin: [0, 15, 0, 15]
      },
      mainTitle: {
        fontSize: 12,
        alignment: 'center'
      },
      mainFooter: {
        fontSize: 12,
        margin: [0, 15, 0, 0]
      },
      tableHeader: {
        fontSize: 9,
        alignment: 'center',
        bold: 'true',
        margin: [0, 5, 0, 0]
      },
      tableHeaderNum: {
        fontSize: 8,
        bold: true,
        alignment: 'center'
      },
      tableParent: {
        bold: true
      },
      commonCell: {
        alignment: 'center'
      },
      impCell: {
        fontSize: 10,
        alignment: 'center',
        bold: 'true'
      },
      deepFooter: {
        fontSize: 10,
        margin: [0, 380, 0, 0]
      }
    },
    defaultStyle: {
      fontSize: 10
    }
  };
  pdfMake.createPdf(dd).open();
});