<?php

use yii\helpers\Url;
?>

<div class="hero text-white hero-bg-image hero-bg-parallax"
    style="background-image: url('<?= Yii::$app->homeUrl ?>theme/stisla/assets/img/unsplash/andre-benz-1214056-unsplash.jpg');">
    <div class="hero-inner">
        <h2>Machine Learning untuk Klasifikasi NLP</h2>
        <p class="lead">Gapailah cita-cita setinggi langit!</p>
        <div class="mt-4">
            <a href="<?= Url::to('@web/data/index') ?>" class="m-2 btn btn-outline-white btn-lg btn-icon icon-left"><i
                    class="fas fa-database"></i> Lihat
                Data</a>
            &nbsp;
            <a href="<?= Url::to('@web/train/index') ?>" class="m-2 btn btn-outline-white btn-lg btn-icon icon-left"><i
                    class="fas fa-cogs"></i>
                Pelatihan</a>
            &nbsp;
            <a href="<?= Url::to('@web/test/index') ?>" class="m-2 btn btn-outline-white btn-lg btn-icon icon-left"><i
                    class="fas fa-list"></i> Pengujian</a>
        </div>
    </div>
</div>