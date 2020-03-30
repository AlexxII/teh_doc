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
          }else{
              value[match[1]] = match[2];
          }
      }else if(regex.section.test(line)){
          let match = line.match(regex.section);
          value[match[1]] = {};
          section = match[1];
      }else if(line.length == 0 && section){
          section = null;
      }
  });
  setLogic(value);
}


function setLogic(config) {
  console.log(config);
  let invisible = config.invisible;
  console.log(invisible.answers.split('-'));

  let regex = {
    range : /\[([0-9]{1,3}-[0-9]{1,3})/
    // singe:
  };

}
