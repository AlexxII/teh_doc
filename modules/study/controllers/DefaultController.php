<?php

namespace app\modules\study\controllers;

use Yii;
use yii\web\Controller;
use app\modules\to\models\schedule\ToSchedule;
use app\modules\vks\models\VksSessions;
use app\modules\scheduler\models\Holiday;


class DefaultController extends Controller
{
//  public $layout = 'study_layout.php';

  public function actionIndex()
  {

    return $this->render('index');
  }

  public function actionCount()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $dayOfWeek = date("N");
    $today = date('Y-m-d');

    //***** подготовка массива оборудования, которое участвует в ТО в момент подсчета *****
    $toData = ToSchedule::find()
      ->select(['eq_id'])
      ->with(['toEq'])
      ->where(['plan_date' => $today])
      ->asArray()
      ->all();

    $toArray = [];
    foreach ($toData as $to) {
      $toArray[] = $to['toEq']['eq_id'];
    }

    // ***** *****
    //***** подготовка массива оборудования, которое участвовало в Сеансах связи в момент подсчета *****
    $vksData = VksSessions::find()
      ->select([''])
      ->where(['vks_date' => $today])
      ->with(['vksTools'])
      ->andWhere(['vks_upcoming_session' => 1])
      ->andWhere(['vks_cancel' => 0])
      ->asArray()->all();

    // ***** *****
    //***** подготовка даты календаря (праздничный день, предпраздничный)*****
    $holiday = Holiday::find()
      ->where('start_date' > $today)
      ->andWhere(['<=', 'end_date', $today])
      ->all();
    // ***** *****

    // "Select eq_id from to_schedule_tbl where plan_date = $today"; => if (in_array) => count ["to"]
    // "Select From calendar_holidays_tbl where data = $today";
    // "Select eq_id, vks_duration_teh, vks_duration_work From vks_session where vks_date = $today and eq_id = id and vks_upcoming_session != 1";

    return [
      "data" => [
        "success" => true,
        "data" => $holiday,
        "message" => "Page load",
      ],
      "code" => 1,
    ];

    $template = $_POST["data"];
    $hours = 0;
    if (array_key_exists("days", $template)){
      if (in_array($dayOfWeek, $template["days"])){
        $hours = $template["hours"];
        if (array_key_exists("holidays", $template)){
          $ranges = $template["holidays"];
          foreach ($ranges as $range) {
            $start = $range["0"];
            $end = $range["1"];
            if ($this::check_in_range($start, $end, $today)){
              $hours = 0;
            }
          }
        }
      }
    } elseif (array_key_exists("sessions", $template)) {
      $toolId = $template["sessions"];
      $hours = $toolId;
    }
    if ($template["to"]) {
      if (array_key_exists("on", $template["to"])){
        $hours += $template["to"]["on"];
      } else {
        $hours -= intval($template["to"]["off"]);
      }
    }
    return [
      "data" => [
        "success" => true,
        "data" => $hours,
        "message" => "Page load",
      ],
      "code" => 1,
    ];
  }

  public static function check_in_range($start_date, $end_date, $date_from_user)
  {
    $start_ts = strtotime($start_date);
    $end_ts = strtotime($end_date);
    $user_ts = strtotime($date_from_user);

    return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
  }


  public function actionParseIk()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $url = "http://www.murmansk.vybory.izbirkom.ru/region/murmansk?action=ikTree&region=51&vrn=2512000478077&id=%23";

    $temp = json_decode($this->parseData($url));
    $tikPrefix = "http://www.murmansk.vybory.izbirkom.ru/region/murmansk?action=ikTree&region=51&vrn=";
    $tikPostfix = "&onlyChildren=true&id=";

    $ikData = $temp[0];
    $tiks = $ikData->children;
    $result = [];
    foreach ($tiks as $key => $tik) {
      $url = $tikPrefix . $tik->id . $tikPostfix . $tik->id;
      $temp = json_decode($this->parseData($url));
      foreach ($temp as $key => $t) {
        $result[$tik->text][$t->id] = $t->text;
      }
    }
    return $result;
  }


  public function parseData($url)
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); //Url together with parameters
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Return data instead printing directly in Browser
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
    curl_setopt($ch, CURLOPT_HEADER, 0);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {  //catch if curl error exists and show it
      $error = curl_error($ch);
      curl_close($ch);
      return 'Curl error: ' . $error;
    } else {
      curl_close($ch);
      return iconv("windows-1251", "utf-8", $result);
    }
  }

}
