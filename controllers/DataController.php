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

class DataController extends Controller
{
    public $title = "Data";

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

    public function beforeAction($action) {
        if($action->id == 'handle-file') :
            Yii::$app->request->enableCsrfValidation = false;
        endif;
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $data = Service::getDataOri();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data['data'],
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSplit($code=NULL, $type='train')
    {
        $data = $code?Service::getDataSplit($code, $type):[];
        $dataProvider = new ArrayDataProvider([
            'allModels' => $code?$data['data']:[],
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);
        return $this->render('split', [
            'dataProvider' => $dataProvider,
            'code'=> $code,
            'type' => $type
        ]);
    }

    public function actionSplitNew($code=0.2){
        return Service::postDataSplit($code);
    }

    public function actionHandleFile(){
        if($_SERVER['REQUEST_METHOD'] === 'DELETE'):
            Yii::$app->response->statusCode = 200;
            return;
        endif;
        $tmp1 = array_key_first($_FILES);
        $tmp2 = array_key_first($_FILES[$tmp1]['name']);
        $fileIs = "$tmp1"."[".$tmp2."]";
        try{
            $file = UploadedFile::getInstanceByName($fileIs);
            $gdrive = new GDrive();
            $_file = $gdrive->uploadFile($file->name, $file->tempName, $file->type);
            Yii::$app->response->statusCode = 200;
            return Yii::$app->params['drive']['urlOpen'] . $_file;
        }
        catch(Exception $e){
            Yii::$app->response->statusCode = 500;
        }
    }
}