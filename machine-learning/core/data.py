
import numpy as np
from sklearn.model_selection import train_test_split
from pandas import read_excel
import csv
import json
import pickle
from functools import reduce
import random


class Data:
    # done
    def dataReading(self):
        file_name = 'data/origin/import.xlsx'
        df = read_excel(file_name, sheet_name=0)
        data = []
        for key, item in enumerate(df.values.tolist()):
            if(key >= 3):
                data.append(item[0:len(item)])
        return data

    def dataWriting(self, path, data, multiple=False):
        with open(path, 'w', newline='') as f:
            writer = csv.writer(f)
            writer.writerows(
                data) if multiple == True else writer.writerow(data)

    def dataJsonWriting(self, path, data):
        with open(path, "w") as f:
            json.dump(data, f)

    def dataPickleWriting(self, path, data):
        f = open(path, 'wb')
        pickle.dump(data, f)
        f.close()

    def dataCsvReading(self, path):
        file = open(path)
        csvreader = csv.reader(file)
        rows = []
        for row in csvreader:
            rows.append(row)
        file.close()
        return rows

    def dataJsonReading(self, path):
        f = open(path)
        data = json.load(f)
        f.close()
        return data

    def dataPickleReading(self, path):
        f = open(path, 'rb')
        data = pickle.load(f)
        f.close()
        return data

    def remakeData(self, data):
        remake = [{'sentence': i[5], 'class':i[7]} for i in data]
        return remake

    def dataSplit(self, data, code, shuffle=0):
        data = np.array(data)
        params_ = [i['sentence'] for i in data]
        class_ = [i['class'] for i in data]
        X_train, X_test, y_train, y_test = train_test_split(
            params_, class_, test_size=code, shuffle=(True if shuffle == 1 else False))
        return {'train': X_train, 'test': X_test, 'trainTarget': y_train, 'testTarget': y_test}

    def sortingData(self, data):
        random.shuffle(data)
        sortData = sorted(data, key=lambda x: x['class'])
        tmpClass = ""
        index = 0
        sortData_ = []
        for i in sortData:
            if(tmpClass != i['class']):
                index = 0
                tmpClass = i['class']
            sortData_.append({**i, 'squence': index})
            index += 1
        return sorted(sortData_, key=lambda x: x['squence'])

    def indexingFeatures(self, train):
        index = []
        for k, i in enumerate(train):
            for j in i.split(" "):
                index.append(j)

        return np.unique(index)

    def getTf(self, X, y):
        tf = []
        for k, i in enumerate(X):
            tf.append({'id': k, 'label': y[k], 'data': reduce(
                lambda d, c: d.update([(c, d.get(c, 0)+1)]) or d, i.split(), {})})
        return tf
