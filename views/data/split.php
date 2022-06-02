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
            <div class="card">
                <div class="card-header">
                    <h4>Pilih Data yang Akan di Tampilkan</h4>
                </div>
                <div class="card-body mb-4">
                    <?= Html::dropDownList('code', Yii::$app->request->get('code'), ['0.1'=>'10:90', '0.2'=>'20:80', '0.3'=>'30:70', '0.4'=>'40:60',], ['prompt'=>'--pilih--', 'class'=>'form-control select2', 'id'=>'code']) ?>
                    <?php if($code): ?>
                    <button id="split" class="mt-4 btn btn-warning float-right btn-sm">Pembagian Data
                        Baru</button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4> <?=  $this->title ?></h4>
                    <div class="card-header-action">
                        <?php if($code!=NULL): ?>
                        <button type="button" id="training"
                            class="<?= $type=="training"?"btn btn-primary":"btn btn-highlight" ?>">Data Latih</button>
                        <button type="button" id="testing"
                            class="<?= $type=="testing"?"btn btn-primary":"btn btn-highlight" ?>">Data Uji</button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <?php if($code!=NULL): ?>
                        <table class="table table-striped table-bordered table-md mb-4">
                            <tr>
                                <td style="width:300px"><strong>Nama</strong></td>
                                <td><?= $type=="training"?"Data Latih":"Data Uji" ?></td>
                            </tr>
                            <tr>
                                <td><strong>Total</strong></td>
                                <td><?= $dataProvider->getTotalCount() ?></td>
                            </tr>
                            <tr>
                                <td><strong>Persentase</strong></td>
                                <td><?= $dataProvider->getTotalCount()?($type=="training"?100-$code*100:$code*100)."%":"-" ?>
                                </td>
                            </tr>
                        </table>
                        <?php endif; ?>
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
                                            return $model['sentence'];
                                        }
                                    ],
                                    [
                                        'attribute' => 'label',
                                        'label' => 'Label',
                                        'format' => 'raw',
                                        'value' => function($model){
                                            return $model['label'];
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
function processData(for_, code){
    let url;
    switch(for_){
        case "split":
            url= baseUrl+controller+'/split-new?code='+code;
            break;
    }
    swal({
        title: 'Bagi Data?',
        text: "Data lama pada pembagian yang sama akan tertimpa",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Iya',
        cancelButtonText: 'Batalkan',
        buttonsStyling: true,
        showLoaderOnConfirm: true,
        preConfirm: function (data) {
            return new Promise(function (resolve, reject) {
                $.ajax({
                    url: url,
                    data: {
                        code: code,
                        _csrf: _csrf
                    },
                    type: 'POST',
                    dataType: 'JSON',
                    success:function(result){
                        resolve(result);
                    },
                });

            })
        },
    }).then(function (data) {
        if(data==1){
            swal(
                'Berhasil',
                'Data berhasil di bagi :)',
                'success'
            ).then(function () {
                window.location.reload();
            });
        }
        else{
            swal(
                'Oups Galat!!!',
                'Sepertinya ada yang salah, coba ulangi',
                'error'
            ).then(function () {
                window.location.reload();
            });
        }
    }, function (dismiss) {
        if (dismiss === 'cancel') {
            swal(
            'Cancelled',
            'Your imaginary file is safe :)',
            'error'
            )
        }
    });
}
function init(){
    $('#code').change(function() {
        let val = $(this).val();
        window.location = '?code='+val+'&type=training';
    });

    $('#training').click(function() {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        window.location = '?code='+urlParams.get('code')+'&type=training';
    });

    $('#testing').click(function() {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        window.location = '?code='+urlParams.get('code')+'&type=testing';
    })

    $('#split').click(function() {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        processData('split', urlParams.get('code'), urlParams.get('type'));
    })

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