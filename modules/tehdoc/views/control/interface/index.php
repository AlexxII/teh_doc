<?php

use yii\bootstrap\Nav;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use yii\web\JsExpression;

$this->title = 'Панель управления интерфейсом';
$this->params['breadcrumbs'][] = ['label' => 'Тех.документация', 'url' => ['/tehdoc']];
$this->params['breadcrumbs'][] = 'Интерфейс';

$about = "Панель улучшения интерфейса. Заполните представленные ниже формы
 известными производителями и моделями оборудования. При добавлении оборудования, соответствующие 
 поля формы заполняться автоматически";

?>

<style>
    .h-title {
        font-size: 18px;
        color: #1e6887;
    }
</style>

<div class="interface-pannel">

    <h3><?= Html::encode($this->title) ?>
        <sup class="h-title fa fa-question-circle-o" aria-hidden="true"
             data-toggle="tooltip" data-placement="right" title="<?php echo $about ?>"></sup>
    </h3>

    <div class="col-lg-6 col-md-6 about">
        <div class="manufact-info"></div>
        <form action="create" method="post" class="input-add">
            <div class="about-main">
                <input id="manufact-id" style="display: none" readonly value="<?php echo $modelMan->id ?>">
                <label>Наименования производителей:</label>
                <textarea type="text" id="manufact" class="form-control" name="manufact"
                          style="resize: vertical" rows=5><?php echo $modelMan->text ?></textarea>
                <label style="font-weight:400;font-size: 10px">Производители перечиляются через ";". Например: Intel;
                    Epson</label>
            </div>
            <div class="about-footer"></div>
            <button type="submit" onclick="saveClick(event)" id="manufact" class="btn btn-primary manufact-btn" disabled>Сохранить
            </button>
        </form>
    </div>

    <div class="col-lg-6 col-md-6 about">
        <div class="models-info"></div>
        <form action="create" method="post" class="input-add">
            <div class="about-main">
                <input id="models-id" style="display: none" readonly value="<?php echo $modelMod->id ?>">
                <label>Наименования моделей:</label>
                <textarea type="text" id="models" class="form-control" name="models"
                          style="resize: vertical" rows=5><?php echo $modelMod->text ?></textarea>
                <label style="font-weight:400;font-size: 10px">Модели оборудования перечиляются через ";". Например: Core i7;
                    EPL-N7000</label>
            </div>
            <div class="about-footer"></div>
            <button type="submit" onclick="saveClick(event)" id="models" class="btn btn-primary models-btn" disabled>Сохранить
            </button>
        </form>
    </div>

</div>

<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    function goodAlert(text) {
        var div = ''+
            '<div id="w3-success-0" class="alert-success alert fade in">'+
            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+
            text +
            '</div>';
        return div;
    }

    function badAlert(text) {
        var div = ''+
            '<div id="w3-success-0" class="alert-danger alert fade in">'+
            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+
            text +
            '</div>';
        return div;
    }

    $(document).ready(function () {
        $("textarea").on('keyup mouseclick', function () {
            var id = $(this).attr('id');
            $("."+id+"-btn").prop("disabled", this.value.length == "" ? true : false);
        });
    });

    function saveClick(e) {
        e.preventDefault();
        var csrf = $('meta[name=csrf-token]').attr("content");
        var id = e.target.id;
        var url = '/tehdoc/control/interface/create';
        var mId = $('#'+id+'-id').val();
        var data = $('#'+id).val();
        $.ajax({
            url: url,
            type: "post",
            data: {Data: data, _csrf: csrf, id: mId},
            success: function (result) {
                if (result){
                    $('.'+id+'-info').hide().html(goodAlert('Записи добавлены в БД.')).fadeIn('slow');
                    $("."+id+"-btn").prop("disabled", true);
                } else {
                    $('.'+id+'-info').hide().html(badAlert('Записи не сохранены в БД. Попробуйте перезагрузить страницу и попробовап' +
                        'снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
                }
            },
            error: function () {
                $('.'+id+'-info').hide().html(badAlert('Записи не сохранены в БД. Попробуйте перезагрузить страницу и попробовап' +
                    'снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
            }
        });
    }

</script>
