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
use yii\data\ArrayDataProvider;

class TestController extends Controller
{
    public $title = "Pengujian";

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

    public function actionIndex($code = NULL)
    {

        $path = "data/train.json";
        $data = file_get_contents($path);
        $data = json_decode($data, TRUE);
        if ($code) :
            $data = array_filter($data, function ($var) use ($code) {
                return ($var['code'] == $code);
            });
        endif;
        $dataProvider = new ArrayDataProvider([
            'allModels' => $code && $data ? $data : [],
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'code' => $code,
        ]);
    }

    public function actionTestingNew()
    {
        $model = Yii::$app->request->post('model');
        return Service::postTesting($model);
    }

    public function actionDetail($type = "tfidf", $code = "")
    {
        $path = "data/train.json";
        $data = file_get_contents($path);
        $data = json_decode($data, TRUE);
        $res = $this->searchArray($data, 'id', $code);
        if ($data && $res >= 0) :
            $type = $type == "tfidf" ? "tfidf" : "tfabs";
            $result = Service::getTesting($type, $data[$res]['info']['model_name']);

            $dataProvider = new ArrayDataProvider([
                'allModels' => $result['data'] ?? [],
                'pagination' => [
                    'pageSize' => 100,
                ],
            ]);

            $time_execute = $result['data'] ? $result['execution_time'] : 0;
            $accuracy = $result['data'] ? $result['measure']['accuracy'] : 0;
            $cm = $result['data'] ? $result['measure']['cm'] : 0;

            return $this->render('detail', [
                'infoModel' => $res >= 0 ? $data[$res] : [],
                'infoTest' => ['accuracy' => $accuracy, 'time_execute' => $time_execute, 'cm' => $cm],
                'type' => $type,
                'dataProvider' => $dataProvider,
            ]);
        else :
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        endif;
    }

    function searchArray($array, $key, $value)
    {
        foreach ($array as $k => $val) {
            if ($val[$key] == $value) :
                return $k;
            endif;
        }
        return -1;
    }
}