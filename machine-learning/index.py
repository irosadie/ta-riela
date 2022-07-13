'''
Please Read README.md before running this program
Credit by Imron Rosadi | https://imronrosadi.com or https://github.com/irosadie
'''

from flask import request
from flask import jsonify
from werkzeug.utils import secure_filename
import os

import logging
import time
import random
import string
import json
import numpy as np
from core.data import Data
from core.tfidf import TfIdf
from core.tfabs import TfAbs
from core.svm import Svm

from config.app import app, ALLOWED_EXTENSIONS, UPLOAD_FOLDER

logging.basicConfig(level=logging.DEBUG)


@app.route('/')
def index():
    return "Hello world!"


@app.route('/ori', methods=['GET'])
def data():
    msg = "OK"
    status = 1
    result = None
    try:
        dt = Data()
        data = dt.dataReading()
        result = {
            'info': {
                'data_length': len(data),
                'field': ['id', 'created_at', 'username', 'tweet', 'downloaded_at', 'sentence', 'type', 'label']
            },
            'data': data
        }
        status = 1
    except Exception as e:
        msg = str(e)
        status = 0
    finally:
        return jsonify({'status': status, 'msg': msg, 'data': result})


@app.route('/split', methods=['GET', 'POST'])
def split():
    msg = "OK"
    status = 1
    result = None
    try:
        code = request.args.get('code', default=0.2, type=float)
        type_ = request.args.get('type', default='train', type=str)

        dt = Data()

        is_sufix = f'[{int(100-code*100)}-{int(code*100)}]'

        train_path = f'data/train/data{is_sufix}.json'
        train_target_path = f'data/train/target{is_sufix}.json'

        test_path = f'data/test/data{is_sufix}.json'
        test_target_path = f'data/test/target{is_sufix}.json'

        tf_path = f'data/tf/data{is_sufix}.json'
        indexing_path = f'data/indexing/data{is_sufix}.json'

        if(request.method == 'POST'):
            shuffle = request.args.get('shuffle', default=0, type=int)
            data_ori = dt.dataReading()
            data_remake = dt.remakeData(data_ori)
            data_sort = dt.sortingData(data_remake)
            split_result = dt.dataSplit(data_sort, code, shuffle)

            # save train to json
            dt.dataJsonWriting(train_path, split_result['train'])
            dt.dataJsonWriting(train_target_path,
                               split_result['trainTarget'])

            # save test to json
            dt.dataJsonWriting(test_path, split_result['test'])
            dt.dataJsonWriting(test_target_path, split_result['testTarget'])

            # save index to json
            indexing = dt.indexingFeatures(split_result['train'])
            dt.dataJsonWriting(indexing_path, indexing.tolist())

            # generate tf for TF.IDF and TF.ABS
            tf = dt.getTf(split_result['train'], split_result['trainTarget'])
            # save tf to json
            dt.dataJsonWriting(tf_path, tf)
            is_data_percentage = f'{int(100-code*100)}:{int(code*100)}'
            is_data_usage = f'{len(split_result["train"])}:{len(split_result["test"])}'

            result = {'info': {'data_length': len(split_result['train'])+len(split_result['test']), 'data_percentage': is_data_percentage,
                               'data_usage': is_data_usage, 'shuffle': None, 'code': code}, 'data_split': {'data_train': {'sentence': split_result['train'], 'label': split_result['trainTarget'], 'data_test': {'sentence': split_result['test'], 'label': split_result['testTarget']}}}}

        else:
            train_data = dt.dataJsonReading(train_path)
            test_data = dt.dataJsonReading(test_path)

            train_target = dt.dataJsonReading(train_target_path)
            test_target = dt.dataJsonReading(test_target_path)

            is_data_percentage = f'{int(100-code*100)}:{int(code*100)}'
            is_data_usage = f'{len(train_data)}:{len(test_data)}'

            result = {'info': {'data_length': len(train_data)+len(test_data), 'data_percentage': is_data_percentage,
                               'data_usage': is_data_usage, 'shuffle': None, 'code': code}, 'data_split': {'data_train': {'sentence': train_data, 'label': train_target}, 'data_test': {'sentence': test_data, 'label': test_target}}}
        status = 1
    except Exception as e:
        msg = str(e)
        status = 0
    finally:
        return jsonify({'status': status, 'msg': msg, 'data': result})


@ app.route('/tfidf', methods=['GET', 'POST'])
def tfidf():
    msg = "OK"
    status = 1
    result = None
    try:
        code = request.args.get('code', default=0.2, type=float)
        dt = Data()
        tfIdf = TfIdf()

        is_sufix = f'[{int(100-code*100)}-{int(code*100)}]'

        indexing_path = f'data/indexing/data{is_sufix}.json'
        tf_path = f'data/tf/data{is_sufix}.json'
        tfidf_path = f'data/tfidf/data{is_sufix}.json'
        feature_tfidf_path = f'data/tfidf/feature-data{is_sufix}.csv'

        if(request.method == 'POST'):
            index_data = dt.dataJsonReading(indexing_path)
            tf = dt.dataJsonReading(tf_path)
            idf = tfIdf.getIdf(index_data, tf)
            tfidf = tfIdf.getTfIdf(index_data, tf, idf)

            # save tfidf to json
            dt.dataJsonWriting(tfidf_path, tfidf)

            features = tfIdf.getTfIdfToFeature(tfidf)
            # save features to csv
            dt.dataWriting(feature_tfidf_path, features, True)
            result = {'info': {'data_length': len(
                features), 'code': code, 'indexing': index_data+['id_', 'label_']}, 'data_tf_idf': features}
        else:
            tfidf = dt.dataJsonReading(tfidf_path)
            index_data = dt.dataJsonReading(indexing_path)
            features = tfIdf.getTfIdfToFeature(tfidf)
            result = {'info': {'data_length': len(
                features), 'code': code, 'indexing': index_data+['id_', 'label_']}, 'data_tf_idf': features}
        status = 1
    except Exception as e:
        msg = str(e)
        status = 0
    finally:
        return jsonify({'status': status, 'msg': msg, 'data': result})


@ app.route('/tfabs', methods=['GET', 'POST'])
def tfabs():
    msg = "OK"
    status = 1
    result = None
    try:
        code = request.args.get('code', default=0.2, type=float)
        dt = Data()
        tfAbs = TfAbs()

        is_sufix = f'[{int(100-code*100)}-{int(code*100)}]'

        train_target_path = f'data/train/target{is_sufix}.json'
        indexing_path = f'data/indexing/data{is_sufix}.json'
        tf_path = f'data/tf/data{is_sufix}.json'
        tfabs_path = f'data/tfabs/data{is_sufix}.json'
        tfabs_doc_path = f'data/tfabs/data-doc{is_sufix}.json'
        feature_tfabs_path = f'data/tfabs/feature-data{is_sufix}.csv'

        if(request.method == 'POST'):
            train_target = dt.dataJsonReading(train_target_path)
            label = list(set(train_target))
            index_data = dt.dataJsonReading(indexing_path)
            tf = dt.dataJsonReading(tf_path)
            tf_per_label = tfAbs.getTfPerLabel(index_data, tf, label)
            term_doc_per_label = tfAbs.getTermDocPerLabel(
                index_data, label, tf_per_label)
            abs = tfAbs.getAbs(index_data, term_doc_per_label, 0.5)
            tfabs = tfAbs.getTfAbs(abs, tf_per_label)
            tfabs_doc = tfAbs.getTfAbsToDocument(tfabs, index_data, tf)

            # save tfabs to json
            dt.dataJsonWriting(tfabs_path, tfabs)

            # save tfabs doc to json
            dt.dataJsonWriting(tfabs_doc_path, tfabs_doc)

            features = tfAbs.getTfAbsToFeature(tfabs_doc)
            # save features to csv
            dt.dataWriting(feature_tfabs_path, features, True)

            result = {'info': {'data_length': len(
                features), 'code': code, 'indexing': index_data+['id_', 'label_']}, 'data_tf_abs': features}
        else:
            tfabs_doc = dt.dataJsonReading(tfabs_doc_path)
            index_data = dt.dataJsonReading(indexing_path)
            features = tfAbs.getTfAbsToFeature(tfabs_doc)

            result = {'info': {'data_length': len(
                features), 'code': code, 'indexing': index_data+['id_', 'label_']}, 'data_tf_abs': features}
        status = 1
    except Exception as e:
        msg = str(e)
        status = 0
    finally:
        return jsonify({'status': status, 'msg': msg, 'data': result})


@ app.route('/train', methods=['POST', 'GET'])
def trainData():
    msg = "OK"
    status = 1
    result = None
    try:
        if(request.method == 'POST'):
            code = request.form.get('code', default=0.2, type=float)
            C = request.form.get('C', default=1.0, type=float)
            kernel = request.form.get('kernel', default='rbf', type=str)
            degree = request.form.get('degree', default=3.0, type=float)
            gamma = request.form.get('gamma', default='auto', type=str)

            dt = Data()
            svm = Svm()

            is_sufix = f'[{int(100-code*100)}-{int(code*100)}]'
            name = request.form.get('name', default=f'training{is_sufix}-'+(
                ''.join(random.choices(string.ascii_uppercase + string.digits, k=5))), type=str)

            model_path = f'data/model/{name}.pickle'
            tfidf_model_path = f'data/model/tfidf/{name}.pickle'
            tfabs_model_path = f'data/model/tfabs/{name}.pickle'

            tfidf_train_data_path = f'data/tfidf/feature-data{is_sufix}.csv'
            tfabs_train_data_path = f'data/tfabs/feature-data{is_sufix}.csv'

            # Train TF.IDF
            tfidf_train_data = np.array(
                dt.dataCsvReading(tfidf_train_data_path))
            tfidf_X = tfidf_train_data[:, 0:-2]
            tfidf_y = tfidf_train_data[0::1, -1]

            tfidf_start_time = time.time()

            # begin training
            model1 = svm.training(X=tfidf_X, y=tfidf_y, C=C, kernel=kernel,
                                  degree=degree, gamma=gamma)
            dt.dataPickleWriting(tfidf_model_path, model1)
            # end training
            tfidf_execution_time = time.time() - tfidf_start_time

            # Train TF.ABS
            tfabs_train_data = np.array(
                dt.dataCsvReading(tfabs_train_data_path))
            tfidf_X = tfabs_train_data[:, 0:-2]
            tfabs_y = tfabs_train_data[0::1, -1]

            tfabs_start_time = time.time()

            # begin training
            model2 = svm.training(
                X=tfidf_X, y=tfabs_y, C=C, kernel=kernel, degree=degree, gamma=gamma)

            dt.dataPickleWriting(tfabs_model_path, model2)
            # end training

            tfabs_execution_time = time.time() - tfabs_start_time

            is_data_percentage = f'{int(100-code*100)}:{int(code*100)}'
            result = {'info': {'train_name': name, 'model_name': f'{name}.pickle', 'model_path': {'tfidf': tfidf_model_path, 'tfabs': tfabs_model_path}, 'trained_at': time.time(), 'data_percentage': is_data_percentage,
                               'code': code, 'params': {'C': C, 'kernel': kernel, 'degree': degree, 'gamma': gamma}, 'execution_time': {'time': {'tfidf': tfidf_execution_time, 'tfabs': tfabs_execution_time}, 'unit': 'seconds'}}}
            dt.dataPickleWriting(model_path, result)
        else:
            model_path = f'data/model/{request.args["model"]}'
            result = dt.dataPickleReading(model_path)
        status = 1
    except Exception as e:
        msg = str(e)
        status = 0
    finally:
        return jsonify({'status': status, 'msg': msg, 'data': result})


@ app.route('/test', methods=['POST', 'GET'])
def testData():
    msg = "OK"
    status = 1
    result = None
    try:
        dt = Data()
        if(request.method == 'POST'):
            tfIdf = TfIdf()
            tfAbs = TfAbs()
            svm = Svm()

            model_name = request.form["model"]

            model_path = f'data/model/{model_name}'
            tfidf_model_path = f'data/model/tfidf/{model_name}'
            tfabs_model_path = f'data/model/tfabs/{model_name}'

            tfidf_result_path = f'data/result/tfidf/{model_name}'
            tfabs_result_path = f'data/result/tfabs/{model_name}'

            model = dt.dataPickleReading(model_path)
            code = model['info']['code']

            is_sufix = f'[{int(100-code*100)}-{int(code*100)}]'

            # load test
            test_path = f'data/test/data{is_sufix}.json'
            test_target_path = f'data/test/target{is_sufix}.json'
            indexing_path = f'data/indexing/data{is_sufix}.json'

            # transform to feature TF.IDF
            index_data = dt.dataJsonReading(indexing_path)
            test_data = dt.dataJsonReading(test_path)
            test_target = dt.dataJsonReading(test_target_path)

            tf = dt.getTf(test_data, test_target)
            idf = tfIdf.getIdf(index_data, tf)
            tfidf = tfIdf.getTfIdf(index_data, tf, idf)
            tfidf_features = np.array(tfIdf.getTfIdfToFeature(tfidf))

            # transform to feature TF.ABS
            label = list(set(test_target))
            tf_per_label = tfAbs.getTfPerLabel(index_data, tf, label)
            term_doc_per_label = tfAbs.getTermDocPerLabel(
                index_data, label, tf_per_label)
            abs = tfAbs.getAbs(index_data, term_doc_per_label, 0.5)
            tfabs = tfAbs.getTfAbs(abs, tf_per_label)
            tfabs_doc = tfAbs.getTfAbsToDocument(tfabs, index_data, tf)
            tfabs_features = np.array(tfAbs.getTfAbsToFeature(tfabs_doc))

            # load model tfidf
            tfidf_model = dt.dataPickleReading(tfidf_model_path)
            # testing area
            tfidf_start_time = time.time()
            tfidf_predict = svm.testing(tfidf_model, tfidf_features[:, 0:-2])
            tfidf_execution_time = time.time() - tfidf_start_time

            tfidf_measure = svm.measure(test_target, tfidf_predict, label)
            tfidf_data = svm.convertResult(
                test_data, test_target, tfidf_features, tfidf_predict)

            tfidf_result = {'execution_time': tfidf_execution_time,
                            'measure': tfidf_measure, 'data': tfidf_data}

            # load model tfabs
            tfabs_model = dt.dataPickleReading(tfabs_model_path)
            # testing area
            tfabs_start_time = time.time()
            tfabs_predict = svm.testing(tfabs_model, tfabs_features[:, 0:-2])
            tfabs_execution_time = time.time() - tfabs_start_time

            tfabs_measure = svm.measure(test_target, tfabs_predict, label)
            tfabs_data = svm.convertResult(
                test_data, test_target, tfabs_features, tfabs_predict)

            tfabs_result = {'execution_time': tfabs_execution_time,
                            'measure': tfabs_measure, 'data': tfabs_data}

            result = {'tfidf': tfidf_result, 'tfabs': tfabs_result}

            dt.dataPickleWriting(tfidf_result_path, result['tfidf'])
            dt.dataPickleWriting(tfabs_result_path, result['tfabs'])

        else:
            test_path = f'data/result/{request.args["type"]}/{request.args["model"]}'
            result = dt.dataPickleReading(test_path)
        status = 1
    except Exception as e:
        msg = str(e)
        status = 0
    finally:
        return jsonify({'status': status, 'msg': msg, 'data': result})


@ app.route('/upload', methods=['POST'])
def uploadData():
    msg = "OK"
    status = 1
    result = None
    try:
        file = request.files['data']
        if file and allowed_file(file.filename):
            filename = secure_filename('import.xlsx')
            file.save(os.path.join(app.config['UPLOAD_FOLDER'], filename))
            result = 1
        else:
            raise Exception(f"Upload file is failed!")
    except Exception as e:
        msg = str(e)
        status = 0
    finally:
        return jsonify({'status': status, 'msg': msg, 'data': result})


def allowed_file(filename):
    return '.' in filename and \
           filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS


if __name__ == "__main__":
    app.run(debug=True)
