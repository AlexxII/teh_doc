<?php

$stream = fopen('opros.xml', 'r');
$parser = xml_parser_create();

while (($data = fread($stream, 860449))) {
  echo '1';
  echo '<br>';
  var_dump($data);
  xml_parse($parser, $data);
}

xml_parse($parser, '', true);


?>