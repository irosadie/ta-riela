import math
from functools import reduce
import numpy as np
from sklearn.pipeline import make_pipeline
from sklearn.preprocessing import StandardScaler
from sklearn.svm import SVC
from sklearn.metrics import confusion_matrix
from sklearn.metrics import accuracy_score


class Svm:

    def __init__(self):
        return

    def training(self, X, y, C=1.0, kernel='rbf', degree=3, gamma='auto'):
        clf = make_pipeline(StandardScaler(), SVC(
            C=C, kernel=kernel, degree=degree, gamma=gamma))
        clf.fit(X, y)
        return clf

    def testing(self, model, X):
        return model.predict(X)

    def measure(self, y_true, y_pred, labels):
        cm = confusion_matrix(y_true=y_true, y_pred=y_pred, labels=labels)
        accuracy = accuracy_score(y_true, y_pred)
        return {'accuracy': accuracy, 'cm': {'labels': labels, 'matrix': cm.tolist()}}

    def convertResult(self, X, y, features, result):
        tf_idf_report = []
        for index, val in enumerate(X):
            tf_idf_report.append({'id': features[index][-2], 'term': val, 'label': {
                'ori': y[index], 'predict': result[index]}})
        return tf_idf_report
