function parseIni(area) {
  let configData = area.value;
  let regex = {
      section: /^\s*\[\s*([^\]]*)\s*\]\s*$/,
      param: /^\s*([^=]+?)\s*=\s*(.*?)\s*$/,
      comment: /^\s*#.*$/
  };
  let value = {};
  let lines = configData.split(/[\r\n]+/);
  let section = null;
  lines.forEach(function(line){
      if(regex.comment.test(line)){
          return;
      }else if(regex.param.test(line)){
          let match = line.match(regex.param);
          if(section){
              value[section][match[1]] = match[2];
              value[section][match[1]] = parseParams(match[2]);
          }else{
              value[match[1]] = match[2];
              value[match[1]] = parseParams(match[2]);
          }
      }else if(regex.section.test(line)){
          let match = line.match(regex.section);
          value[match[1]] = {};
          section = match[1];
      }else if(line.length == 0 && section){
          section = null;
      }
  });
  console.log(value);
}


function parseParams(data) {
  // console.log(config);
  // let macr = config.invisible;
  // let answers = macr.answers;

  let regex = {
    range : /\[(.+?)\]/gm,
    single: /([0-9]{1,3})/gm
  };

  let range = data.matchAll(regex.range);
  let rangeData = Array.from(range);
  let length = rangeData.length;
  let multipleData = {};
  for (let i = 0; i < length; i++) {
    multipleData[i] = rangeData[i][1];
  }

  let single = data.replace(regex.range, '');
  let sDat = single.matchAll(regex.single);
  let sData = Array.from(sDat);
  let sLength = sData.length;
  let singleData = {};
  for (let i = 0; i < sLength; i++) {
    singleData[i] = sData[i][1];
  }
  // console.log(multipleData + '|' + singleData);
  console.log(multipleData);
  console.log(singleData);
}
