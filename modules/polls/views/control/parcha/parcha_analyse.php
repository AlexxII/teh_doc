<style>
  body {
    overflow: auto;
  }
</style>

<?php

$xml = simplexml_load_file("opros.xml");

$users = [
  "parcha51-1 parcha51-1 ",
  "parcha51-2 parcha51-2 ",
  "parcha51-3 parcha51-3 ",
  "parcha51-4 parcha51-4 ",
  "parcha51-5 parcha51-5 ",
  "parcha51-6 parcha51-6 ",
  "parcha51-7 parcha51-7 ",
  "parcha51-8 parcha51-8 ",
  "parcha51-9 parcha51-9 ",
  "parcha51-10 parcha51-10 "
];

$timeIntervals = [];
foreach ($xml->opros->a as $answer) {
  if ($answer["usr_intrv"] == $users[1]) {
//    date("G", strtotime($result));
//    $timeIntervals[] = $answer["date_intrv"];
    $timeIntervals[] = date("G", strtotime($answer["date_intrv"]));
    $timeIntervals = array_unique($timeIntervals);
    $timeIntervals = array_values($timeIntervals);
  }
}

foreach ($timeIntervals as $key => $time) {
  $timeIntervals[$key] = 0;
}
$time_2 = $timeIntervals;

foreach ($xml->opros->a as $answer) {
  if ($answer["usr_intrv"] == $users[1]) {
    $timeIntervals[(date("G", strtotime($answer["date_intrv"]))-8)] += 1;
  }
}

foreach ($timeIntervals as $key => $result) {
//  echo ($key + 8) . ' - ' . $result;
  echo $result;
  echo "<br>";
}
echo '<hr>';

foreach ($xml->opros->a as $answer) {
  if ($answer["usr_intrv"] == $users[1] && (string)$answer->v->o["c"]["0"] == '49') {
    $time_2[(date("G", strtotime($answer["date_intrv"]))-8)] += 1;
  }
}

foreach ($time_2 as $key => $result) {
//  echo ($key + 8) . ' - ' . $result;
  echo $result;
  echo "<br>";
}