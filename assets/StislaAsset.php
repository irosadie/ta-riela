<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class StislaAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/tailwind.css',
        //General CSS Files
        ['https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css', 'integrity'=>"sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T", 'crossorigin'=>"anonymous"],
        ['https://use.fontawesome.com/releases/v5.7.2/css/all.css', 'integrity'=>"sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr", 'crossorigin'=>"anonymous"],
        //CSS Libraries
        'theme/stisla/node_modules/jqvmap/dist/jqvmap.min.css',
        'theme/stisla/node_modules/summernote/dist/summernote-bs4.css',
        'theme/stisla/node_modules/owl.carousel/dist/assets/owl.carousel.min.css',
        'theme/stisla/node_modules/owl.carousel/dist/assets/owl.theme.default.min.css',
        // Template CSS
        'theme/stisla/assets/css/style.css',
        'theme/stisla/assets/css/components.css',
        'theme/stisla/node_modules/select2/dist/css/select2.min.css',
        'theme/stisla/node_modules/bootstrap-daterangepicker/daterangepicker.css',

        'plugin/sweetalert2/dist/sweetalert2.min.css',
        'https://unpkg.com/filepond/dist/filepond.css',

        'theme/stisla/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css'

		// 'plugin/editable/editablegrid.css'
    ];
    public $js = [
    // General JS Scripts
    ['https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js', 'integrity'=>"sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1", 'crossorigin'=>"anonymous"],
    ['https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', 'integrity'=>"sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM", 'crossorigin'=>"anonymous"],
    'https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js',
    'theme/stisla/assets/js/stisla.js',
    // JS Libraies
    'theme/stisla/node_modules/jquery-sparkline/jquery.sparkline.min.js',
    'theme/stisla/node_modules/chart.js/dist/Chart.min.js',
    'theme/stisla/node_modules/owl.carousel/dist/owl.carousel.min.js',
    'theme/stisla/node_modules/summernote/dist/summernote-bs4.js',
    'theme/stisla/node_modules/chocolat/dist/js/jquery.chocolat.min.js',
    // Template JS File
    'theme/stisla/assets/js/scripts.js',
    'theme/stisla/assets/js/custom.js',
    // Page Specific JS File
    'theme/stisla/assets/js/page/index.js',
    'theme/stisla/assets/js/page/modules-sweetalert.js',
    'theme/stisla/node_modules/select2/dist/js/select2.full.min.js',

    'plugin/sweetalert2/dist/sweetalert2.min.js',

    'plugin/editable/editablegrid.js',
    'plugin/editable/editablegrid_renderers.js',
    'plugin/editable/editablegrid_editors.js',
    'plugin/editable/editablegrid_validators.js',
    'plugin/editable/editablegrid_utils.js',
    'plugin/editable/editablegrid_charts.js',
    'plugin/notify/notify.min.js',

    'https://unpkg.com/filepond/dist/filepond.js',
    'https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js',
    'theme/stisla/node_modules/bootstrap-daterangepicker/daterangepicker.js',
    'theme/stisla/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js',
    '//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js',
    '//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.plugins.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}