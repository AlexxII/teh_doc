<?php

namespace app\modules\tehdoc\modules\equipment\controllers\controlPanel;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use app\modules\tehdoc\modules\to\models\ToEquipment;
use app\modules\tehdoc\modules\equipment\models\Oth;
use app\modules\tehdoc\modules\equipment\models\Special;
use app\modules\tehdoc\modules\equipment\models\Tools;
use app\modules\tehdoc\modules\equipment\models\ToolSettings;

class SettingsController extends Controller
{

  public $defaultAction = 'index';
  public $layout = '@app/modules/tehdoc/modules/equipment/views/layouts/equipment_layout_control.php';

  public function actionIndex()
  {
    $id = $_GET['id'];
    $tool = Tools::findModel($id);
    $toolSettings = ToolSettings::findModel($id);
    $wikiCount = $tool->countWikiPages;
    $imagesCount = $tool->countImages;
    $docsCount = $tool->countDocs;
    return $this->render('header', [
      'tool' => Tools::findModel($id),
      'toolSettings' => $toolSettings,
      'docsCount' => $docsCount,
      'imagesCount' => $imagesCount,
      'wikiCount' => $wikiCount,
    ]);
  }


  public function actionWrapConfig()
  {
    $id = $_GET['id'];
    $toolSettings = ToolSettings::findModel($id);

    return $this->render('wrap_view', [
      'tool' => Tools::findModel($id),
      'toolSettings' => $toolSettings,
    ]);
  }


  public function actionGeneralTable()
  {
    if (isset($_POST['toolId'])) {
      $toolId = $_POST['toolId'];
      $toolSettings = ToolSettings::findModel($toolId);
      if (isset($_POST['bool'])) {
        if ($_POST['bool'] === 'true') {
          $toolSettings->eq_general = 1;
        } else {
          $toolSettings->eq_general = 0;
        }
      } else {
        return false;
      }
      if ($toolSettings->save()) {
        return true;
      }
      return false;
    }
    return false;
  }

  // Пакетная обработка - в разработке
  public function actionGeneralTablePckg()
  {
    return false;
  }

  public function actionOth()
  {
    if (isset($_POST['toolId'])) {
      $toolId = $_POST['toolId'];
      $toolSettings = ToolSettings::findModel($toolId);
      $model = Tools::findModel($toolId);
      if ($model->oth) {
        $oth = $model->oth;
      } else {
        $oth = new Oth();
        $oth->eq_id = $model->id;
      }
      if (isset($_POST['bool'])) {
        if ($_POST['bool'] === 'true') {
          $toolSettings->eq_oth = 1;
          $oth->valid = 1;
        } else {
          $toolSettings->eq_oth = 0;
          $oth->eq_id = $model->id;
          $oth->valid = 0;
        }
      } else {
        return false;
      }
      if ($toolSettings->save()) {
        if ($oth->save()) {
          return true;
        }
        return false;
      }
      return false;
    }
    return false;
  }

  // Пакетная обработка - в разработке
  public function actionOthPckg()
  {
    return false;
  }

  public function actionOthTitle()
  {
    if (isset($_POST['toolId'])) {
      $toolId = $_POST['toolId'];
      $model = Tools::findModel($toolId);
      if ($model->oth) {
        $oth = $model->oth;
      } else {
        return false;
      }
      if (isset($_POST['bool'])) {
        if ($_POST['bool'] === 'true') {
          $oth->eq_oth_title = $_POST['title'];
          $oth->eq_oth_title_on = 1;
        } else {
          $oth->eq_oth_title_on = 0;
        }
      } else {
        return false;
      }
      if ($oth->save()) {
        return true;
      }
      return false;
    }
    return false;
  }

  public function actionComplex()
  {
    if (isset($_POST['toolId'])) {
      $toolId = $_POST['toolId'];
      $toolSettings = ToolSettings::findModel($toolId);
      if (isset($_POST['bool'])) {
        if ($_POST['bool'] === 'true') {
          $toolSettings->eq_complex = 1;
        } else {
          $toolSettings->eq_complex = 0;
        }
      } else {
        return false;
      }
      if ($toolSettings->save()) {
        return true;
      }
      return false;
    }
    return false;
  }

  public function actionWrap()
  {
    if (isset($_POST['toolId'])) {
      $toolId = $_POST['toolId'];
      $toolSettings = ToolSettings::findModel($toolId);
      $toolModel = Tools::findModel($toolId);
      if (isset($_POST['bool'])) {
        if ($_POST['bool'] === 'true') {
          $toolSettings->eq_wrap = 1;
          $toolModel->icon = 't fa fa-clone';
        } else {
          $toolSettings->eq_wrap = 0;
          $toolModel->icon = 't fa fa-file-o';
        }
      } else {
        return false;
      }
      if ($toolSettings->save() && $toolModel->save()) {
        return true;
      }
      return false;
    }
    return false;
  }

  public function actionSpecialWorks()
  {
    if (isset($_POST['toolId'])) {
      $toolId = $_POST['toolId'];
      $toolSettings = ToolSettings::findModel($toolId);
      $model = Tools::findModel($toolId);
      if ($model->special) {
        $special = $model->special;
      } else {
        $special = new Special();
        $special->eq_id = $model->id;
      }
      if (isset($_POST['bool'])) {
        if ($_POST['bool'] === 'true') {
          $toolSettings->eq_special = 1;
          $special->valid = 1;
        } else {
          $toolSettings->eq_special = 0;
          $special->valid = 0;
        }
      } else {
        return false;
      }
      if ($toolSettings->save()) {
        if ($special->save()) {
          return true;
        }
        return false;
      }
      return false;
    }
    return false;
  }

  public function actionSpecialStickerNumber()
  {
    if (isset($_POST['toolId'])) {
      $toolId = $_POST['toolId'];
      $model = Tools::findModel($toolId);
      if ($model->special) {
        $special = $model->special;
      } else {
        return false;
      }
      if (isset($_POST['title'])) {
        $special->sticker_number = $_POST['title'];
      } else {
        return false;
      }
      if ($special->save()) {
        return true;
      }
      return false;
    }
    return false;
  }

  // серверная часть установки флажка "В задании на обновление"
  public function actionTaskSet()
  {
    if (isset($_POST['toolId'])) {
      $toolId = $_POST['toolId'];
      $model = ToolSettings::findModel($toolId);
      if (isset($_POST['bool'])) {
        if ($_POST['bool'] === 'true') {
          $model->eq_task = 1;
        } else {
          $model->eq_task = 0;
        }
      } else {
        return false;
      }
      if ($model->save()) {
        return true;
      }
      return false;
    }
    return false;
  }

// серверная часть установки флажка "В задании на обновление" - пакетная обработка
  public function actionTaskSetPckg()
  {
    sleep(1);
    if (isset($_POST['jsonData']) && isset($_POST['bool'])) {
      if ($_POST['bool'] === 'true') {
        $bool = 1;
      } else {
        $bool = 0;
      }
      $result = false;
      foreach ($_POST['jsonData'] as $toolId) {
        $model = ToolSettings::findModel($toolId);
        $model->eq_task = $bool;
        $result = $model->save();
      }
      return $result;
    }
    return false;
  }

  // ТО
  public function actionMaintenance()
  {
    if (isset($_POST['toolId'])) {
      $toolId = $_POST['toolId'];
      $toolSettings = ToolSettings::findModel($toolId);
      $model = Tools::findModel($toolId);
      if ($model->to) {
        $to = $model->to;
      } else {
        $to = new ToEquipment();
        $to->eq_id = $model->id;
        $to->name = $model->eq_title;
        $parent = ToEquipment::findOne(['lvl' => 0]);            // TODO !!есть вероятность ошибки
        $to->parent_id = $parent->id;
        $to->appendTo($parent);
      }
      if (isset($_POST['bool'])) {
        if ($_POST['bool'] === 'true') {
          $toolSettings->eq_to = 1;
          $to->valid = 1;
        } else {
          $toolSettings->eq_to = 0;
          $to->valid = 0;
        }
      } else {
        return false;
      }
      if ($toolSettings->save()) {
        if ($to->save()) {
          return true;
        }
        return false;
      }
      return false;
    }
    return false;
  }

}