<?php

use yii\helpers\{
    Html,
    Url,
    HtmlPurifier
};
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Data Pembobotan';

?>
<?php Pjax::begin(); ?>
<?= $this->render('@app/views/site/_message') ?>
<div class="index">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Pilih Bobot yang Akan di Tampilkan</h4>
                </div>
                <div class="card-body mb-4">
                    <?= Html::dropDownList('code', Yii::$app->request->get('code'), ['0.1' => '10:90', '0.2' => '20:80', '0.3' => '30:70', '0.4' => '40:60',], ['prompt' => '--pilih--', 'class' => 'form-control select2', 'id' => 'code']) ?>
                    <?php if ($code) : ?>
                    <button id="weighting" class="mt-4 btn btn-warning float-right btn-sm">Pembobotan Baru</button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4> <?= $this->title ?></h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <?php if ($code != NULL && $data && $data['data']) : ?>
                        <table class="table table-striped table-bordered table-md mb-4">
                            <tr>
                                <td style="width:300px"><strong>Metode pembobotan</strong></td>
                                <td><strong><?= $type == "tfidf" ? "TF IDF" : "TF ABS" ?></strong></td>
                            </tr>
                            <tr>
                                <td><strong>Total Fitur</strong></td>
                                <td><?= $data['data']['info']['feature'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Total Data</strong></td>
                                <td><?= count($data['data']['weight']) . "/ " . $data['data']['info']['length'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Persentase</strong></td>
                                <td><?= (100 - $code * 100) . ":" . ($code * 100) ?>
                                </td>
                            </tr>
                        </table>
                        <?php endif; ?>
                        <?php if ($data && $data['data']) : ?>
                        <div class="table-responsive" style="max-height: 500px;">
                            <table class="table table-bordered table-md">
                                <tr>
                                    <?php foreach ($data['data']['indexing'] as $key => $value) : ?>
                                    <th style="position:sticky;top: 0px; background-color:white;"><?= $value; ?></th>
                                    <?php endforeach; ?>
                                </tr>
                                <?php foreach ($data['data']['weight'] as $key => $value) : ?>
                                <tr>
                                    <?php foreach ($value as $k => $v) : ?>
                                    <td><?= $k == count($value) - 1 ? $v : round($v, 4); ?></td>
                                    <?php endforeach; ?>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                        <p class="mt-2" style="color:red;">Hanya menampilkan 50 data pertama, guna menghindari waktu
                            <i>loading</i>
                            yang lama.
                        </p>
                        <?php else : ?>
                        <div class="alert alert-primary alert-has-icon alert-dismissible show fade">
                            <button class="close" data-dismiss="alert">
                                <span>×</span>
                            </button>
                            <div class="alert-icon"><i class="fas fa-bell"></i></div>
                            <div class="alert-body">
                                <div class="alert-title">Oups!</div>
                                <p>Pembobotan pada pembagian data ini belum ada, buat pembobotan baru dulu yuk!</p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$js = <<< JS
function processData(for_, code, type){
    let url;
    switch(for_){
        case "weighting":
            url= baseUrl+controller+'/weighting-new?code='+code+'&type='+type;
            break;
    }
    swal({
        title: 'Lakukan Pembobotan?',
        text: "Data lama pada pembagian yang sama akan tertimpa",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Iya',
        cancelButtonText: 'Batalkan',
        buttonsStyling: true,
        showLoaderOnConfirm: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
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
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);

        let val = $(this).val();
        window.location = '?code='+val+'&type='+urlParams.get('type');
    });

    $('#weighting').click(function() {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        processData('weighting', urlParams.get('code'), urlParams.get('type'));
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