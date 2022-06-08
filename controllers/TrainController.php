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

class TrainController extends Controller
{
    public $title = "Pelatihan";

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
            $data = json_decode($data, TRUE);
            $new_data = ['id' => uniqid(), 'code' => $code, 'name' => $name, 'info' => $res['data']];
            array_push($data, $new_data);
            $json_data = json_encode($data);
            file_put_contents($path, $json_data);
            return 1;
        endif;
        return -1;
    }

    public function actionDelete()
    {
        $id = Yii::$app->request->post('id');
        $path = "data/train.json";
        $data = file_get_contents($path);
        $data = json_decode($data, TRUE);

        if (count($data) == 1) :
            file_put_contents($path, json_encode([]));
            return 1;
        endif;

        $res = $this->searchArray($data, 'id', $id);
        if ($res >= 0) :
            unset($data[$res]);
            $json_data = json_encode($data);
            file_put_contents($path, $json_data);
            return 1;
        endif;
        return -1;
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