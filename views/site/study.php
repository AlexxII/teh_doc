<?php


?>

<script>
  var intVal = function (i) {
    return typeof i === 'string' ?
      i.match(/(\d+)/g) * 1 :
      typeof i === 'number' ?
        i : 0;
  };

  console.log(intVal('(120) /т.'));

</script>