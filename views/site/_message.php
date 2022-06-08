<!-- alert -->
<?php if (Yii::$app->session->hasFlash('warning')) : ?>
<div class="alert alert-warning alert-has-icon alert-dismissible show fade">
    <button class="close" data-dismiss="alert">
        <span>×</span>
    </button>
    <div class="alert-icon"><i class="fas fa-bell"></i></div>
    <div class="alert-body">
        <div class="alert-title">Oups!</div>
        <?= Yii::$app->session->getFlash('warning') ?>
    </div>
</div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('success')) : ?>
<div class="alert alert-primary alert-has-icon alert-dismissible show fade">
    <button class="close" data-dismiss="alert">
        <span>×</span>
    </button>
    <div class="alert-icon"><i class="fas fa-bell"></i></div>
    <div class="alert-body">
        <div class="alert-title">Success!</div>
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
</div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('danger')) : ?>
<div class="alert alert-danger alert-has-icon alert-dismissible show fade">
    <button class="close" data-dismiss="alert">
        <span>×</span>
    </button>
    <div class="alert-icon"><i class="fas fa-bell"></i></div>
    <div class="alert-body">
        <div class="alert-title">Oups!</div>
        <?= Yii::$app->session->getFlash('danger') ?>
    </div>
</div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('info')) : ?>
<div class="alert alert-secondary alert-has-icon alert-dismissible show fade">
    <button class="close" data-dismiss="alert">
        <span>×</span>
    </button>
    <div class="alert-icon"><i class="fas fa-bell"></i></div>
    <div class="alert-body">
        <div class="alert-title">Info!</div>
        <?= Yii::$app->session->getFlash('info') ?>
    </div>
</div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('no_school')) : ?>
<div class="alert alert-secondary alert-has-icon alert-dismissible show fade">
    <button class="close" data-dismiss="alert">
        <span>×</span>
    </button>
    <div class="alert-icon"><i class="fas fa-bell"></i></div>
    <div class="alert-body">
        <div class="alert-title">Info!</div>
        <?= Yii::$app->session->getFlash('no_school') ?>
    </div>
</div>
<?php endif; ?>
<!-- end alert -->