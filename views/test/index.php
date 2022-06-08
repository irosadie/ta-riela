<?php

use yii\helpers\{
    Html,
    Url,
    HtmlPurifier
};
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Model Pelatihan';

?>
<?php Pjax::begin(); ?>
<?= $this->render('@app/views/site/_message') ?>
<div class="index">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Pilih Pelatihan yang Akan di Gunakan</h4>
                </div>
                <div class="card-body mb-4">
                    <?= Html::dropDownList('code', Yii::$app->request->get('code'), ['0.1' => '10:90', '0.2' => '20:80', '0.3' => '30:70', '0.4' => '40:60',], ['prompt' => '--pilih--', 'class' => 'form-control select2', 'id' => 'code']) ?>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4> <?= $this->title ?></h4>
                </div>
                <div class="card-body">
                    <?php if ($dataProvider->getTotalCount() >= 1) : ?>
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
                                        'label' => 'Nama Pelatihan',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model['name'];
                                        }
                                    ],
                                    [
                                        'label' => 'C',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model['info']['params']['C'];
                                        }
                                    ],
                                    [
                                        'label' => 'degree',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model['info']['params']['degree'];
                                        }
                                    ],
                                    [
                                        'label' => 'Waktu Pelatihan',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return date('d/m/Y h:i:s', $model['info']['trained_at']);
                                        }
                                    ],
                                    [
                                        'label' => 'SVM x TF.IDF',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return "<a href='" . Yii::$app->homeUrl . "test/detail?type=tfidf&code=" . $model['id'] . "'><button class='btn btn-primary btn-sm'>Lihat</button></a>";
                                        }
                                    ],
                                    [
                                        'label' => 'SVM x TF.ABS',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return "<a href='" . Yii::$app->homeUrl . "test/detail?type=tfabs&code=" . $model['id'] . "'><button class='btn btn-primary btn-sm'>Lihat</button></a>";
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
                            <p>Pelatihan (Model) pada pembagian data ini belum ada, buat pembobotan baru dulu yuk!</p>
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
function processData(for_, name, code, degree, c){
    let url;
    switch(for_){
        case "training":
            url= baseUrl+controller+'/training-new';
            break;
    }
    swal({
        title: 'Pelatihan',
        text: "Lakukan pelatihan pada variable yang diinput?",
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
                        name: name,
                        code: code,
                        c: c,
                        degree: degree,
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

    $('#training').click(function(){
        let name = $('#is-name').val();
        let degree = $('#is-degree').val();
        let c = $('#is-c').val();

        if(!name){
            swal(
                'Oups Galat!!!',
                'Nama variable tidak boleh kosong',
                'error'
            ).then(function () {
                window.location.reload();
            });
            return;
        }
        
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        processData('training', name, urlParams.get('code'), degree, c);
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