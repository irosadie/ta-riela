<?php

namespace app\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\{
    Controller,
    NotFoundHttpException,
    Response,
    UploadedFile
};
use yii\filters\{
    VerbFilter,
    AccessControl
};
use app\utils\{
    service\Service
};
use yii\data\ArrayDataProvider;

use yii\widgets\ActiveForm;

class TrainController extends Controller
{
    public $title = "Pelatihan";

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
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
        $data = json_decode($data);

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

    public function actionTrainingNew()
    {
        $name = Yii::$app->request->post('name');
        $code = Yii::$app->request->post('code');
        $c = Yii::$app->request->post('c');
        $degree = Yii::$app->request->post('degree');

        $res = Service::postTraining($code, $c, $degree);
        if ($res != -1) :
            //olah data
            $path = "data/train.json";
            $data = file_get_contents($path);
            $data = json_decode($data);
            $new_data = ['id' => uniqid(), 'code' => $code, 'name' => $name, 'info' => $res['data']];
            array_push($data, $new_data);
            $json_data = json_encode($data);
            file_put_contents($path, $json_data);
            return 1;
        endif;
        return -1;
    }

    function searchArray($array, $key, $value)
    {
        return array_search($value, array_column($array, $key));
    }
}