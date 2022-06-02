<?php

/* @var $this \yii\web\View */
/* @var $content string */
use app\assets\StislaAsset;
use yii\bootstrap4\Html;
use app\utils\breadcrumb\Breadcrumb as BC;
use yii\bootstrap4\Modal;

StislaAsset::register($this);
$router = $this->context->action->uniqueId;
$breadcrumb = BC::generateBreadcrumbs($router, "breadcrumb-item");
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<!-- <body class="d-flex flex-column h-100"> -->

<body>
    <?php $this->beginBody() ?>
    <div id="app">
        <div class="main-wrapper">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar" style="z-index:unset">
                <form class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i
                                    class="fas fa-bars"></i></a></li>
                        <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i
                                    class="fas fa-search"></i></a></li>
                    </ul>
                </form>
            </nav>
            <div class="main-sidebar">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand">
                        <a href="index.html">TA RILA</a>
                    </div>
                    <div class="sidebar-brand sidebar-brand-sm">
                        <a href="index.html">RL</a>
                    </div>
                    <?= $this->render('menu') ?>
                </aside>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <!-- breadcrumb -->
                    <div class="section-header">
                        <h1><?= $this->context->title?$this->context->title:ucfirst(Yii::$app->controller->id) ?></h1>
                        <div class='section-header-breadcrumb'>
                            <?= $breadcrumb ?>
                        </div>
                    </div>
                    <!-- endbteadcrumb -->
                    <!-- main content -->
                    <?= $content ?>
                    <!-- end main content -->
                </section>
            </div>


            <footer class="main-footer">
                <div class="footer-left">
                    Copyright &copy; 2022
                </div>
                <div class="footer-right">
                    2.3.0
                </div>
            </footer>
        </div>
    </div>
    <?php $this->endBody() ?>
    <?php
        Modal::begin([
            'title' => '<span id="modalTitle">Modal</span>',
            'centerVertical' => true,
            'id'=>'modal',
            'size' => 'modal-lg',
            'scrollable' => true,
        ]);

        echo '<div id="modalContent"></div>';

        Modal::end();
    ?>
</body>

<?php
$js = <<< JS
$('document').ready(()=>{  
    let start = '';
    let isInterval = '';
    $(document).on('pjax:beforeSend', function(){
        start = Date.now();
    });
    $(document).on('pjax:send', function(){
        isInterval = setInterval(function(){
            let now = Date.now();
            if((now - start) >=1500){
                $.notify('Still working...', { 
                    className: 'info',
                    autoHide: false,
                    clickToHide: false
                });
                clearInterval(isInterval)
            }
        },50)
    });
    $(document).on('pjax:success', function(){
        $('.notifyjs-wrapper').trigger('notify-hide');
        clearInterval(isInterval)

    });
    $(document).on('pjax:error', function(){
        $('.notifyjs-wrapper').trigger('notify-hide');
        clearInterval(isInterval)
    });
    $(document).on('pjax:popstate', function(){
        document.referrer;
    });

});
JS;
$this->registerJs($js);
$this->registerJsVar('baseUrl', Yii::$app->homeUrl);
$this->registerJsVar('module', Yii::$app->controller->module->id);
$this->registerJsVar('controller', Yii::$app->controller->id);
$this->registerJsVar('_csrf', Yii::$app->request->csrfToken);
?>

</html>
<?php $this->endPage() ?>