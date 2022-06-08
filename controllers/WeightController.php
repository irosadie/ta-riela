<?php

namespace app\controllers;

use Yii;
use yii\web\{
    Controller
};
use yii\filters\{
    VerbFilter
};
use app\utils\{
    service\Service
};

class WeightController extends Controller
{
    public $title = "Pembobotan";

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function beforeAction($action)
    {
        if ($action->id == 'handle-file') :
            Yii::$app->request->enableCsrfValidation = false;
        endif;
        return parent::beforeAction($action);
    }

    public function actionIndex($code = NULL, $type = 'tfidf')
    {
        $this->title = $type == 'tfidf' ? "Pembobotan TF.IDF" : "Pembobotan TF.ABS";
        $data = $code ? Service::getWeighting($code, $type) : [];
        return $this->render('index', [
            'data' => $data,
            'code' => $code,
            'type' => $type
        ]);
    }

    public function actionWeightingNew($code = 0.2, $type = 'tfidf')
    {
        return Service::postWeighting($code, $type);
    }
}