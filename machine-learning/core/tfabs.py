import math
import numpy as np
from functools import reduce


class TfAbs:

    def __init__(self):
        return

    def getTfPerLabel(self, data, tf, label):
        tfPerLabel = []
        for i in label:
            term = {}
            for j in data:
                isTotal = 0
                for n in tf:
                    if(n['label'] == i):
                        if(j in n['data']):
                            isTotal += n['data'][j]
                term[j] = isTotal
            tfPerLabel.append({'label': i, 'data': term})
        return tfPerLabel

    def getTermDocPerLabel(self, data, label, tfPerLabel):
        nij = []
        for n in label:
            nij_term = []
            # d1
            for i in tfPerLabel:
                term = {}
                if(i['label'] == n):
                    for j in i['data']:
                        term[j] = 0 if (i['data'].get(j)) == 0 else 1
                    nij_term.append({'label': 'd1', 'data': term})

            # d2
            term = {}
            for y in data:
                total = 0
                for i in tfPerLabel:
                    if(i['label'] != n):
                        if(i['data'].get(y) != 0):
                            total += 1
                    term[y] = total
            nij_term.append({'label': 'd2', 'data': term})

            # d3
            for i in tfPerLabel:
                term = {}
                if(i['label'] == n):
                    for j in i['data']:
                        term[j] = 1 if (i['data'].get(j)) == 0 else 0
                    nij_term.append({'label': 'd3', 'data': term})

            # d4
            term = {}
            for y in data:
                total = 4
                for i in tfPerLabel:
                    if(i['label'] != n):
                        if(i['data'].get(y) != 0):
                            total -= 1
                    term[y] = total
            nij_term.append({'label': 'd4', 'data': term})

            nij.append({'label': n, 'data': nij_term})
        return nij

    def getAbs(self, data, termDocPerLabel, b):
        isAbs = []
        for i in termDocPerLabel:
            term = {}
            res = 0
            for j in data:
                d1 = i['data'][0]['data'].get(j)
                d2 = i['data'][1]['data'].get(j)
                d3 = i['data'][2]['data'].get(j)
                d4 = i['data'][3]['data'].get(j)
                res = abs(np.log((d1+b)*(d4+b)/(d2+b)*(d3+b)))
                term[j] = res
            isAbs.append({'label': i['label'], 'data': term})
        return isAbs

    def getTfAbs(self, isAbs, tfPerLabel):
        isTfAbs = []
        for i in isAbs:
            term = {}
            for j in tfPerLabel:
                if(i['label'] == j['label']):
                    for y in (j['data']):
                        term[y] = (j['data'].get(y)*i['data'].get(y))
            isTfAbs.append({'label': i['label'], 'data': term})
        return isTfAbs

    def getTfAbsToDocument(self, tfAbs, data, tf):
        tfabs_to_doc = []
        for i in tf:
            term = {}
            for y in data:
                term[y] = 0
                for n in tfAbs:
                    if(n['label'] == i['label'] and y in i['data']):
                        term[y] = n['data'].get(y)
                        break
            tfabs_to_doc.append({**i, 'data': term})
        return tfabs_to_doc

    def getTfAbsToFeature(self, tfAbsDoc):
        features = []
        for y in tfAbsDoc:
            tmp_data = []
            for j in y['data']:
                tmp_data.append(float(y['data'].get(j)))
            tmp_data.append(y['id'])
            tmp_data.append(y['label'])
            features.append(tmp_data)

        return features
