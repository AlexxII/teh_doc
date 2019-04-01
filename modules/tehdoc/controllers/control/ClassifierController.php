<?php

namespace app\modules\tehdoc\controllers\control;

use app\base\MHelper;
use app\modules\admin\models\Classifier;
use app\modules\admin\models\ClassifierTbl;
use yii\base\DynamicModel;
use yii\db\mssql\PDO;
use yii\web\Controller;
use app\modules\tehdoc\modules\equipment\models\SSP;
use Yii;
use yii\widgets\ActiveForm;
use kartik\tree\TreeViewInput;

class ClassifierController extends Controller
{

    // TODO удаление и создание таблиц БД только по POST - создать правило

    public function beforeAction($action)
    {
        if ($action->id === 'files-load') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }


    static function fatal($msg)
    {
        echo json_encode(array(
            "error" => $msg
        ));
        exit(0);
    }


    public function actionIndex()
    {
        $models = Classifier::find()->all();
        return $this->render('index', [
            'model' => $models
        ]);
    }

    public function actionClassifiers()
    {
        $id = Classifier::find()->select('id')->where(['=', 'lvl', 0])->all();
        if (!$id) {
            $data = array();
            $data = [['title' => 'База данных пуста', 'key' => -999]];
            return json_encode($data);
        }
        $roots = Classifier::findOne($id)->tree();
        return json_encode($roots);
    }

    public function actionMove($item, $action, $second)
    {
        $item_model = Classifier::findOne($item);
        $second_model = Classifier::findOne($second);
        switch ($action) {
            case 'after':
                $item_model->insertAfter($second_model);
                break;
            case 'before':
                $item_model->insertBefore($second_model);
                break;
            case 'over':
                $item_model->appendTo($second_model);
                break;
        }
        $parent = Classifier::findOne($second);
        /*    if (!$parent->children()->one()) {
              $parent->disabled = 0;
              $parent->save();
            } else {
              $parent->disabled = 0;
              $parent->save();
            }*/
        if ($item_model->save()) {
            return true;
        }
        return false;
    }

    public function actionCreate($parentId, $title)
    {
        $data = [];
        $category = Classifier::findOne($parentId);
        $newSubcat = new Classifier(['name' => $title]);
        $newSubcat->root = $category->id;
        $newSubcat->appendTo($category);
        if ($parent = $newSubcat->parents(1)->one()) {
            $parent->save();
        }
        $data['acceptedTitle'] = $title;
        return json_encode($data);
    }

    public function actionCreateRoot($title)
    {
        $newRoot = new Classifier(['name' => $title]);
        $newRoot->makeRoot();
        $data['acceptedTitle'] = $title;
        return json_encode($data);
    }

    public function actionUpdate($id, $title)
    {
        $category = Classifier::findOne(['id' => $id]);
        $category->name = $title;
        $category->save();
        return true;
    }

    public function actionDelete()
    {
        if (!empty($_POST)) {
            // TODO: удаление или невидимый !!!!!!!
            $id = $_POST['id'];
            $root = Classifier::findOne(['id' => $id]);
            if ($root->clsf_table_scheme != null) {
                $tableScheme = json_decode($root->clsf_table_scheme);
                $table = $tableScheme->tableName;
                $this->DeleteTables($table);
            }
            $parent = $root->parents(1)->one();
            $root->delete();
            if (!$parent->children()->one()) {
                $parent->disabled = 0;
                $parent->save();
            } else {
                $parent->disabled = 1;
                $parent->save();
            }
        }
    }

    private function DeleteTables($table)
    {
        $sql_details = \Yii::$app->params['sql_details'];
        $db = SSP::sql_connect($sql_details);
        $sql = 'DROP TABLE IF EXISTS ' . $table;
        $stmt = $db->prepare($sql);
        $stmt->execute();
    }

    public function actionDeleteRoot()
    {
        if (!empty($_POST)) {
            $id = $_POST['id'];
            $root = Classifier::findOne(['id' => $id]);
            if ($root->clsf_table_scheme != null) {
                $tableScheme = json_decode($root->clsf_table_scheme);
                $table = $tableScheme->tableName;
                $this->DeleteTables($table);
                $childrens = $root->children()->all();
                foreach ($childrens as $children) {
                    if ($children->clsf_table_scheme != null) {
                        $tableScheme = json_decode($children->clsf_table_scheme);
                        $table = $tableScheme->tableName;
                        $this->DeleteTables($table);
                    }
                }
            }
            $root->deleteWithChildren();
        }
    }

    public function actionDeleteFromTable()
    {
        // TODO: try {catch}
        if (!empty($_POST)) {
            $dellArray = array();
            $dellArray = $_POST['dellArray'];
            $tableName = $_POST['tableName'];
            $sql_details = \Yii::$app->params['sql_details'];
            $db = SSP::sql_connect($sql_details);
            $sql = 'DELETE FROM ' . $tableName . ' WHERE clsf_id = ?';
            $stmt = $db->prepare($sql);
            foreach ($dellArray as $id) {
                $stmt->execute(array($id));
            }
            return 1;
        }
    }


    public function actionGetLeaves()
    {
        $array = array();
        $leaves = Classifier::find()->select('id')->leaves()->orderBy('lft')->asArray()->all();
        $categories = Classifier::find()->select('id')->where(['!=', 'lvl', '0'])->orderBy('lft')->asArray()->all();
        $array['leaves'] = $leaves;
        $array['cat'] = $categories;
        return json_encode($array);
    }

    public function actionClassifierDisplay($id)
    {
        $clsf = Classifier::findOne($id);
        if ($clsf) {
            $tableScheme = json_decode($clsf->clsf_table_scheme, true);
            $table = json_encode($tableScheme);
            return $table;
        }
        return false;
    }

//============================== Расширенный режим ======================================

// создание таблицы для классификатора.

    public function actionCreateTable()
    {
        if ($_POST['Data']['0'] != '') {
            $sql_details = \Yii::$app->params['sql_details'];
            $tableScheme = array(
                'tableName' => '',
                'columns' => [
                ]
            );
            $data = $_POST['Data'];
            $classifier = Classifier::find()->where(['id' => $data[0]])->all();
            $clmnsArray = '';
            for ($i = 1; $i < count($data); $i++) {
                $clmn = preg_replace('%[^A-Za-z0-9_]%', '_', MHelper::translit($data[$i]['label']));
                $tableScheme['columns'][$i - 1]['name'] = $clmn;
                $tableScheme['columns'][$i - 1]['label'] = $data[$i]['label'];
                $tableScheme['columns'][$i - 1]['typeName'] = $data[$i]['typeName'];
                $tableScheme['columns'][$i - 1]['id'] = $data[$i]['id'];
                $tableScheme['columns'][$i - 1]['order'] = $data[$i]['order'];
                switch ($data[$i]['typeName']) {
                    case "input":
                        $clmnsArray .= ', ' . $clmn . ' VARCHAR(255)';
                        $tableScheme['columns'][$i - 1]['type'] = 'VARCHAR(255)';
                        break;
                    case "floatinput":
                        $clmnsArray .= ', ' . $clmn . ' FLOAT';
                        $tableScheme['columns'][$i - 1]['type'] = 'FLOAT';
                        break;
                    case "dateinput":
                        $clmnsArray .= ', ' . $clmn . ' DATE';
                        $tableScheme['columns'][$i - 1]['type'] = 'DATE';
                        break;
                    case "textarea":
                        $clmnsArray .= ', ' . $clmn . ' MEDIUMTEXT';
                        $tableScheme['columns'][$i - 1]['type'] = 'MEDIUMTEXT';
                        break;
                    case "radio":
                        $clmnsArray .= ', ' . $clmn . ' TINYINT DEFAULT 0';
                        $tableScheme['columns'][$i - 1]['type'] = 'TINYINT DEFAULT 0';
                        break;
                    case "checkbox":
                        $clmnsArray .= ', ' . $clmn . ' TINYINT DEFAULT 0';
                        $tableScheme['columns'][$i - 1]['type'] = 'TINYINT DEFAULT 0';
                        break;
                    case "fileinput":
                        $clmnsArray .= ', ' . $clmn . ' VARCHAR(255)';
                        $tableScheme['columns'][$i - 1]['type'] = 'VARCHAR(255)';
                        break;
                }
            }
            // TODO try {} catch
            $tbl_name = "clsf_" . rand() . "_tbl";
            $sql = 'CREATE TABLE ' . $tbl_name . ' (
            clsf_id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, 
            eq_id INT(11) NOT NULL' .
                $clmnsArray .
                ' ) ENGINE=INNODB';
            $tableScheme['tableName'] = $tbl_name;
            $db = SSP::sql_connect($sql_details);
            $stmt = $db->prepare($sql);

            if ($stmt->execute()) {
                $classifier[0]->clsf_table_scheme = json_encode($tableScheme);
                $classifier[0]->save();
                return true;
            }
            return false;
        }
        return false;
    }

    public function AddColumns($columns, $tableName)
    {
        $sql_details = \Yii::$app->params['sql_details'];
        $data = $columns;
        $outScheme = array();
        for ($i = 0; $i < count($data); $i++) {
            $clmn = preg_replace('%[^A-Za-z0-9_]%', '_', MHelper::translit($data[$i]['label']));
            $outScheme[$i]['name'] = $clmn;
            $outScheme[$i]['label'] = $data[$i]['label'];
            $outScheme[$i]['typeName'] = $data[$i]['typeName'];
            $outScheme[$i]['id'] = $data[$i]['id'];
            $outScheme[$i]['order'] = $data[$i]['order'];
            $addColumn = '';
            switch ($data[$i]['typeName']) {
                case "input":
                    $addColumn .= $clmn . ' VARCHAR(255)';
                    $outScheme[$i]['type'] = 'VARCHAR(255)';
                    break;
                case "floatinput":
                    $addColumn .= $clmn . ' FLOAT';
                    $outScheme[$i]['type'] = 'FLOAT';
                    break;
                case "dateinput":
                    $addColumn .= $clmn . ' DATE';
                    $outScheme[$i]['type'] = 'DATE';
                    break;
                case "textarea":
                    $addColumn .= $clmn . ' MEDIUMTEXT';
                    $outScheme[$i]['type'] = 'MEDIUMTEXT';
                    break;
                case "radio":
                    $addColumn .= $clmn . ' TINYINT DEFAULT 0';
                    $outScheme[$i]['type'] = 'TINYINT DEFAULT 0';
                    break;
                case "checkbox":
                    $addColumn .= $clmn . ' TINYINT DEFAULT 0';
                    $outScheme[$i]['type'] = 'TINYINT DEFAULT 0';
                    break;
                case "fileinput":
                    $addColumn .= $clmn . ' VARCHAR(255)';
                    $outScheme[$i]['type'] = 'VARCHAR(255)';
                    break;
            }
            $sql = 'ALTER TABLE ' . $tableName . ' ADD ' . $addColumn;
            $db = SSP::sql_connect($sql_details);
            $stmt = $db->prepare($sql);
            try {
                $stmt->execute();
            }                                           // TODO обработчик ошибок
            catch (\PDOException $e) {
                self::fatal("Ошибка в SQL запросе: " . $e->getMessage() . 'sql-запрос: ' . $sql);
            }
        }
        return $outScheme;
    }

//Обновление таблицы БД классификатора
    public function actionUpdateTable()
    {
        $sql_details = \Yii::$app->params['sql_details'];
        if (!empty($_POST['id'])) {
            $id = $_POST['id'];
            $clsf = Classifier::findOne($id);
            $tableSchemeOld = json_decode($clsf->clsf_table_scheme);
            $tableSchemeNew = json_decode($clsf->clsf_table_scheme, true);
            $tableName = $tableSchemeOld->tableName;
            $oldColumns = $tableSchemeNew['columns'];
            usort($oldColumns, function ($a, $b) {             // Сортировка массива по id
                return $a['id'] > $b['id'] ? 1 : -1;
            });
            if (!empty($_POST['old'])) {
                $editColumns = $_POST['old'];
                usort($editColumns, function ($a, $b) {          // Сортировка массива по id
                    return $a['id'] > $b['id'] ? 1 : -1;
                });
                for ($i = 0; $i < count($editColumns); $i++) {
                    if ($editColumns[$i]['id'] == $oldColumns[$i]['id']) {
                        if ($editColumns[$i]['condition'] == 'old') {
                            $tableSchemeNew['columns'][$i]['label'] = $editColumns[$i]['label'];
                            $tableSchemeNew['columns'][$i]['order'] = $editColumns[$i]['order'];
                        } else {
                            $sql = 'ALTER TABLE ' . $tableName . ' DROP COLUMN ' . $tableSchemeNew['columns'][$i]['name'];
                            $db = SSP::sql_connect($sql_details);
                            $stmt = $db->prepare($sql);
                            try {
                                $stmt->execute();                         // TODO try {} catch
                            } catch (\PDOException $e) {
                                self::fatal("Ошибка в SQL запросе: " . $e->getMessage() . 'sql-запрос: ' . $sql);
                            }
                            unset($tableSchemeNew['columns'][$i]);
                        }
                    } else {
                        return 'Something wrong!';
                    }
                }
            }
            if (!empty($_POST['create'])) {
                $newColumns = $_POST['create'];
                $newSchemes = $this->AddColumns($newColumns, $tableName);
                foreach ($newSchemes as $newScheme) {
                    $tableSchemeNew['columns'][] = $newScheme;
                }
            }
            $tableSchemeNew['columns'] = array_values($tableSchemeNew['columns']);  // переиндексация массива 'columns'
            $clsf->clsf_table_scheme = json_encode($tableSchemeNew);
            $clsf->save();
        }
        return true;
    }


    public function actionExtendedData()
    {
        $model = new Classifier;
        return $this->renderPartial('classifier_select_input', [
            'model' => $model
        ]);
    }


    public function actionExtendedDataForm($id) // формиррование формы для присваивания классификатора
    {
        $classifier = Classifier::findOne($id);
        $tableScheme = json_decode($classifier->clsf_table_scheme);
        if (empty($tableScheme)) {
            return 'Error_01';
        }

        //TODO: если нет таблицы сообщить, что нет возможности использовать классификатор!

        $form = '<input id="tbl-name" hidden readonly value="' . $tableScheme->tableName . '" name="tbl-name">';

        if (empty($tableScheme->columns)) {
            return $form .= '<div>Простой классификатор. Просто нажмите <strong>Присвоить</strong>.</div>';
        }
        $columns = $tableScheme->columns;
        usort($columns, function ($a, $b) {          // Сортировка массива
            return $a->order > $b->order ? 1 : -1;
        });

        $parents = $classifier->parents()->all();
        foreach ($parents as $parent) {
            if (!empty($parent->clsf_table_scheme)) {
                $tableSch = json_decode($parent->clsf_table_scheme);
                if (!empty($tableSch->columns)) {
                    foreach ($tableSch->columns as $clmn)
                        array_unshift($columns, $clmn);
                }
            }
        }

        foreach ($columns as $column) {
            switch ($column->typeName) {
                case 'input':
                    $form .=
                        '<label>' . $column->label . '</label>' .
                        '<input class="form-control" name="' . $column->name . '">' .
                        '<p></p>';
                    break;
                case 'floatinput':
                    $form .=
                        '<label>' . $column->label . '</label>' .
                        '<input class="form-control" name="' . $column->name . '">' .
                        '<p></p>';
                    break;
                case 'dateinput':
                    $form .=
                        '<label>' . $column->label . '</label>' .
                        '<input type="date" placehoder="ДД.ММ.ГГГГ" class="form-control" name="' . $column->name . '">' .
                        '<p></p>';
                    break;
                case 'textarea':
                    $form .=
                        '<label>' . $column->label . '</label>' .
                        '<textarea class="form-control" name="' . $column->name . '" style="resize: vertical"></textarea>' .
                        '<p></p>';
                    break;
                case 'radio':
                    $form .=
                        '<input type="radio" name="' . $column->name . '" >' .
                        '<label>' . $column->label . '</label>' .
                        '<p></p>';
                    break;
                case 'checkbox':
                    $form .=
                        '<input type="checkbox" name="' . $column->name . '" >' .
                        '<label style="padding-left:10px">' . $column->label . '</label>' .
                        '<p></p>';
                    break;
                case 'fileinput':
                    $form .=
                        '<label>' . $column->label . '</label>' .
                        '<input type="file" multiple class="files-up" name="' . $column->name . '" style="wax-width:200px;width:180px; padding-bottom:15px">' .
                        '<p></p>';
                    break;
                default:
                    $form .=
                        '<div>Простой классификатор. Просто нажмите <strong>Присвоить</strong>.</div>';
            }
        }
        return $form;
    }

    public function actionExtendedDataUpdate($id, $tableName)
    {
        $sql_details = \Yii::$app->params['sql_details'];
        $sql = 'SELECT * FROM ' . $tableName . ' WHERE clsf_id = ' . $id;
        $db = SSP::sql_connect($sql_details);
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    }

    public function actionAssignClassifier()  // часть обработки запроса на присваивание классификатора
    {
        $sql_details = \Yii::$app->params['sql_details'];
        $result = false;
        $data = $_POST['data'];
        $tblName = $data[0]['value'];
        $ids = $_POST['id'];
        $sql = 'INSERT INTO ' . $tblName . ' (eq_id';
        $columns = '';
        $values = '';
        if (!empty($_POST['data'][1])) {
            for ($i = 1; $i < count($data); $i++) {
                if ($data[$i]['value'] == 'on') {                   // для checkbox или radiobutton
                    $data[$i]['value'] = 1;
                }
                $columns .= ', ' . $data[$i]['name'];
                $values .= ', "' . $data[$i]['value'] . '"';
            }
        }
        $db = SSP::sql_connect($sql_details);
        $sql .= $columns . ') VALUES (?' . $values . ')';
        for ($i = 0; $i < count($ids); $i++) {
            $stmt = $db->prepare($sql);
            if ($stmt->execute(array($ids[$i]))) {
                $result = true;
            } else {
                $result = false;
            }
        }
        return $result;
    }

    public function actionFilesLoad()
    {
        $sql_details = \Yii::$app->params['sql_details'];
        $result = false;
        $keyPost = $_POST;
        $files = $_FILES;
        $tblName = $keyPost['tbl-name'];
        $idArray = array();
        $idArray[] = explode(',', $keyPost['ids']);
        $clmnNames = array();
        $sql = 'INSERT INTO ' . $tblName . ' (eq_id';
        $clmns = array();
        $done_files = array();
        $uploaddir = \Yii::$app->params['uploadPath'];
        $tmpClmnName = '';
        $pattern = '/([A-Za-z_]+)(\|[0-9]+)/i';                     // Регулярное для выделения имени колонки
        $replacement = '${1.php}';
        $columns = '';
        $values = '';
        $subvalues = '';
        foreach ($files as $key => $file) {
            $file_name = $file['name'];
            $keyName = preg_replace('(\.)', '_', $file_name);
            if (in_array($keyName, $keyPost)) {
                $done_files[] = $keyPost[$keyName];
            }
            if ($tmpClmnName == '') {
                $tmpClmnName = preg_replace($pattern, $replacement, $key);
            }
            if (move_uploaded_file($file['tmp_name'], "$uploaddir/$file_name")) {
//                    $done_files[] = realpath("$uploaddir/$file_name");
                $clmnName = preg_replace($pattern, $replacement, $key);
//                    $done_files[] = \Yii::$app->security->generateRandomString() . "_files";
                if ($tmpClmnName == preg_replace($pattern, $replacement, $key)) {
                    $subvalues .= ' ' . $file_name;
                } else {
                    $tmpClmnName = preg_replace($pattern, $replacement, $key);
                    $columns .= ', ' . $clmnName;
                    $values = ', "' . $subvalues . '"';
                }
            }
//            return var_dump($keyName);
//            return var_dump($keyName);
        }
        $db = SSP::sql_connect($sql_details);
        $sql .= $columns . ') VALUES (?' . $values . ')';

        return var_dump($done_files);

        for ($i = 0; $i < count($idArray[0]); $i++) {
            $stmt = $db->prepare($sql);                             //TODO: try->catch
            if ($stmt->execute(array($idArray[0][$i]))) {
                $result = true;
            } else {
                $result = false;
            }
        }
        return $result;
    }


    public function actionSendDataExtTable()
    {
        return 1;
    }
}