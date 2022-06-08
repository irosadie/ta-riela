<?php

use yii\helpers\{
    Html,
    Url,
    HtmlPurifier
};
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Hasil Pengujian';

?>
<?php Pjax::begin(); ?>
<?= $this->render('@app/views/site/_message') ?>
<div class="index">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Model</h4>
                </div>
                <div class="card-body mb-4">
                    <table class="table table-striped table-md">
                        <tr>
                            <td><strong>Nama Pengujian</strong></td>
                            <td>:</td>
                            <td><?= $infoModel['name'] ?? "-" ?></td>
                            <td><strong>Persentase Data</strong></td>
                            <td>:</td>
                            <td><?= (100 - ($infoModel['code'] ?? 1) * 100) . " /" . (($infoModel['code'] ?? 1) * 100) ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Pembobotan</strong></td>
                            <td>:</td>
                            <td><?= $type == "tfidf" ? "TF.IDF" : "TF.ABS" ?></td>
                            <td><strong>Nilai C</strong></td>
                            <td>:</td>
                            <td><?= $infoModel['info']['params']['C'] ?? "-" ?></td>
                        </tr>
                        <tr>
                            <td><strong>Nilai Degree</strong></td>
                            <td>:</td>
                            <td><?= $infoModel['info']['params']['degree'] ?></td>
                            <td><strong>Lama Pelatihan</strong></td>
                            <td>:</td>
                            <td><?= $type == "tfidf" ? round($infoModel['info']['execution_time']['time']['tfidf'], 3) : round($infoModel['info']['execution_time']['time']['tfabs'], 3) ?>
                                seconds
                            </td>
                        </tr>
                    </table>

                    <button data-value="<?= $infoModel['info']['model_name'] ?>" id="testing"
                        class="btn btn-primary float-right btn-sm">Pengujian
                        Baru</button>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4> <?= $this->title ?>
                    </h4>
                </div>
                <div class="card-body">
                    <?php if ($dataProvider->getTotalCount()) : ?>
                    <div class="table-responsive">

                        <div class="alert alert-info alert-has-icon alert-dismissible show fade">
                            <div class="alert-icon"><i class="fas fa-bell"></i></div>
                            <div class="alert-body">
                                <p>Akurasi klasifikasi pada model ini sebesar
                                    <?= round(($infoTest['accuracy'] * 100), 2) . "%" ?>
                                    dengan waktu eksekusi <?= round($infoTest['time_execute'], 3) ?> seconds</p>
                            </div>
                        </div>

                        <table class="table table-bordered table-sm mt-4">
                            <tr>
                                <td align="center" colspan="<?= count($infoTest['cm']['labels']) + 1 ?>">
                                    <strong>Confusion Matrix</strong>
                                </td>
                            </tr>
                            <tr>
                                <td align="center"><strong>LABEL</strong></td>
                                <?php foreach ($infoTest['cm']['labels'] as $key => $value) : ?>
                                <td align="center"><?= $value ?></td>
                                <?php endforeach; ?>
                            </tr>
                            <?php foreach ($infoTest['cm']['labels'] as $key => $value) : ?>
                            <tr>
                                <td style="width:150px;" align="center"><strong><?= $value;  ?></strong></td>
                                <?php foreach ($infoTest['cm']['matrix'][$key] as $k => $v) : ?>
                                <td style="<?= $key == $k ? "background-color:#e2fbff;" : "" ?>" align="center">
                                    <?= $v; ?></td>
                                <?php endforeach; ?>
                            </tr>
                            <?php endforeach; ?>
                        </table>
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
                                        'label' => 'Tweet',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model['term'];
                                        }
                                    ],
                                    [
                                        'label' => 'Label Asli',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model['label']['ori'];
                                        }
                                    ],
                                    [
                                        'label' => 'Label Prediksi',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model['label']['predict'];
                                        }
                                    ],
                                    [
                                        'label' => 'Keterangan',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model['label']['ori'] === $model['label']['predict'] ? "<span style='color:blue'>Benar</span>" : "<span style='color:red'>Salah</span>";
                                        }
                                    ]
                                ],
                            ]); ?>
                    </div>
                    <?php else : ?>
                    <div class="alert alert-primary alert-has-icon alert-dismissible show fade">
                        <button class="close" data-dismiss="alert">
                            <span>×</span>
                        </button>
                        <div class="alert-icon"><i class="fas fa-bell"></i></div>
                        <div class="alert-body">
                            <div class="alert-title">Oups!</div>
                            <p>Pengujian pada model ini belum ada, buat pengujian baru dulu
                                yuk!</p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$js = <<< JS
function processData(for_, model){
    let url;
    switch(for_){
        case "testing":
            url= baseUrl+controller+'/testing-new';
            break;
    }
    swal({
        title: 'Pengujian',
        text: "Lakukan pengujian pada variable yang diinput?",
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
                        model: model,
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
                'Data berhasil di latih :)',
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
function deleteData(for_, id){
    let url;
    switch(for_){
        case "delete":
            url= baseUrl+controller+'/delete';
            break;
    }
    swal({
        title: 'Hapus Data',
        text: "Yakin ingin menghapus pelatihan ini?",
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
                        id: id,
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
                'Data berhasil di hapus :)',
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
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);

        let val = $(this).val();
        window.location = '?code='+val;
    });

    $('#testing').click(function(){
        let model = $('#testing').data('value');        
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        processData('testing', model);
    })

    $('.delete').click(function(e){
        e.preventDefault();
        let id = $(this).attr('data-id');
        deleteData('delete', id);
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