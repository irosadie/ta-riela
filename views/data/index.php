<?php

use yii\helpers\{
    Html,
    Url,
    HtmlPurifier
};
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Data (Skunder)';

?>
<?php Pjax::begin(); ?>
<?= $this->render('@app/views/site/_message') ?>
<div class="index">
    <div class="row">
        <div class="col-12">
            <p>
                <?= Html::a('<i class="fa fa-undo"></i> Perbarui Data', ['#'], ['class' => 'btn btn-info m-1', 'id' => 'show-upload']) ?>
            </p>
            <div class="card show-field" style="display:none;">
                <div class="card-header">
                    <h4>Perbarui Data Riset</h4>
                </div>
                <div class="card-body">
                    <?= Html::input('file', 'data', null, [
                        'class' => 'filepond',
                        'data-allow-reorder' => true,
                        'data-max-file-size' => '3MB',
                        'data-max-files' => '1'
                    ]) ?>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4> <?= $this->title ?></h4>
                    <div class="card-header-action">
                        <a target="_blank" href="<?= Yii::$app->homeUrl ?>data/tmp-file/import.xlsx"
                            data-pjax="0">download template/ data</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'tableOptions' => ['class' => 'table table-striped'],
                            'summaryOptions' => ['class' => 'badge badge-light m-2'],
                            'columns' => [
                                [
                                    'class' => 'yii\grid\SerialColumn',
                                    'header' => 'No.'
                                ],
                                [
                                    'attribute' => 'download_at',
                                    'label' => 'Tanggal (Unduh)',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return date('d/m/Y', strtotime($model[4]));
                                    }
                                ],
                                [
                                    'attribute' => 'username',
                                    'label' => 'Nama Pengguna',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return "@" . $model[2];
                                    }
                                ],
                                [
                                    'attribute' => 'ori',
                                    'label' => 'Data Asli',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $model[3];
                                    }
                                ],
                                [
                                    'attribute' => 'clean',
                                    'label' => 'Data Bersih',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $model[5];
                                    }
                                ],
                                [
                                    'attribute' => 'label',
                                    'label' => 'Label',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $model[7];
                                    }
                                ]
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$js = <<< JS
function init(){
    $('#show-upload').click(function(e){
        e.preventDefault();
        if($('.show-field').is(':visible')){
            $('.show-field').hide('slow');
            $('#show-upload').html('<i class="fa fa-undo"></i> Perbarui Data');
        }else{
            $('.show-field').show('slow');
            $('#show-upload').html('<i class="fa fa-eye"></i> Sembunyikan');

        }
    })

    FilePond.registerPlugin(FilePondPluginFileValidateType);
    const inputElement = document.querySelector('.filepond');

    const pond = FilePond.create(inputElement);
    pond.setOptions({
        acceptedFileTypes:"application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, .xlsx",
        server:'handle-file',
    });

    pond.on('processfile', (error, file) => {
        if (error) {
            return;
        }
        window.location.reload();
    });

    return;
};
// call function
init();
$('.lazy').Lazy()
$(document).on('pjax:popstate', function(){
    $.pjax.reload({container: '#p0', timeout: false});
});

JS;
$this->registerJs($js);
?>
<?php Pjax::end(); ?>