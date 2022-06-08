<?php

namespace app\utils\service;

use yii\httpclient\Client;
use yii\web\HttpException;
use Yii;

class Service
{
    static function getDataOri()
    {
        $client = new Client(['baseUrl' => Yii::$app->params['baseUrl']]);
        $response =  $client->get('ori')->send();
        if ($response->isOk && $response->data['status']) :
            return $response->data['data'];
        endif;
        throw new HttpException(500, 'Something Wrong!');
    }

    static function getDataSplit($code, $type)
    {
        $client = new Client(['baseUrl' => Yii::$app->params['baseUrl']]);
        $response =  $client->get('split', ['code' => $code])->send();
        if ($response->isOk) :
            if ($response->data['status'] == 1) :
                $data = [];
                foreach ($response->data['data']['data_split'][$type == 'training' ? 'data_train' : 'data_test']['sentence'] as $key => $value) {
                    $data[] = ['sentence' => $value, 'label' => $response->data['data']['data_split']['data_train']['label'][$key]];
                }
                return ['data' => $data];
            endif;
            return ['data' => []];
        endif;
        throw new HttpException(500, 'Something Wrong!');
    }

    static function postDataSplit($code)
    {
        $client = new Client(['baseUrl' => Yii::$app->params['baseUrl']]);
        $response =  $client->post("split?code=$code")->send();
        if ($response->isOk && $response->data['status'] == 1) :
            return 1;
        endif;
        return -1;
    }

    static function getWeighting($code, $type = 'tfidf')
    {
        $client = new Client(['baseUrl' => Yii::$app->params['baseUrl']]);
        $response =  $client->get($type == 'tfidf' ? 'tfidf' : 'tfabs', ['code' => $code])->send();
        if ($response->isOk) :
            if ($response->data['status'] == 1) :
                $data = [];
                $tmpData = $type == 'tfidf' ? $response->data['data']['data_tf_idf'] : $response->data['data']['data_tf_abs'];
                foreach ($tmpData as $key => $value) {
                    $data[] = $value;
                    if ($key == 49) {
                        break;
                    }
                }
                return ['data' => ['info' => ['feature' => count($response->data['data']['info']['indexing']) - 2, 'length' => count($tmpData)], 'indexing' => $response->data['data']['info']['indexing'], 'weight' => $data]];
            endif;
            return ['data' => []];
        endif;
        throw new HttpException(500, 'Something Wrong!');
    }

    static function postWeighting($code, $type)
    {
        $client = new Client(['baseUrl' => Yii::$app->params['baseUrl']]);
        $response =  $client->post(($type == 'tfidf' ? "tfidf" : "tfabs") . "?code=$code")->send();
        if ($response->isOk && $response->data['status'] == 1) :
            return 1;
        endif;
        return -1;
    }

    static function getTraining($code)
    {
        $client = new Client(['baseUrl' => Yii::$app->params['baseUrl']]);
        $response =  $client->get('train', ['code' => $code])->send();
        if ($response->isOk && $response->data['status'] == 1) :
            return $response->data['data'];
        endif;
        throw new HttpException(500, 'Something Wrong!');
    }

    static function postTraining($code, $c, $degree)
    {
        $client = new Client(['baseUrl' => Yii::$app->params['baseUrl']]);
        $response =  $client->post("train", ['code' => $code, 'C' => $c, 'degree' => $degree])->send();
        if ($response->isOk && $response->data['status'] == 1) :
            return ['data' => $response->data['data']['info']];
        endif;
        return -1;
    }

    static function postTesting($model)
    {
        $client = new Client(['baseUrl' => Yii::$app->params['baseUrl']]);
        $response =  $client->post("test", ['model' => $model])->send();
        if ($response->isOk && $response->data['status'] == 1) :
            return 1;
        endif;
        return -1;
    }

    static function getTesting($type, $model)
    {
        $client = new Client(['baseUrl' => Yii::$app->params['baseUrl']]);
        $response =  $client->get('test', ['type' => $type, 'model' => $model])->send();
        if ($response->isOk) :
            if ($response->data['status'] == 1) :
                return $response->data['data'];
            endif;
            return ['data' => []];
        endif;
        throw new HttpException(500, 'Something Wrong!');
    }

    static function postFile($file)
    {

        $client = new Client(['baseUrl' => Yii::$app->params['baseUrl']]);
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('upload')
            ->addFile('data', $file)
            ->send();
        if ($response->isOk && $response->data['status'] == 1) :
            return 1;
        endif;
        return -1;
    }
}