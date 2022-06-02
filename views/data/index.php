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
                <?= Html::a('<i class="fa fa-undo"></i> Perbarui Data', ['#'], ['class' => 'btn btn-info m-1']) ?>
            </p>
            <div class="card">
                <div class="card-header">
                    <h4>Perbarui Data Riset</h4>
                </div>
                <div class="card-body">
                    UPLOAD
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4> <?=  $this->title ?></h4>
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
                                        'value' => function($model){
                                            return date('d/m/Y',strtotime($model[4]));
                                        }
                                    ],
                                    [
                                        'attribute' => 'username',
                                        'label' => 'Nama Pengguna',
                                        'format' => 'raw',
                                        'value' => function($model){
                                            return "@".$model[2];
                                        }
                                    ],
                                    [
                                        'attribute' => 'ori',
                                        'label' => 'Data Asli',
                                        'format' => 'raw',
                                        'value' => function($model){
                                            return $model[3];
                                        }
                                    ],
                                    [
                                        'attribute' => 'clean',
                                        'label' => 'Data Bersih',
                                        'format' => 'raw',
                                        'value' => function($model){
                                            return $model[5];
                                        }
                                    ],
                                    [
                                        'attribute' => 'label',
                                        'label' => 'Label',
                                        'format' => 'raw',
                                        'value' => function($model){
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