import math
from functools import reduce


class TfIdf:

    def __init__(self):
        return

    def getIdf(self, data, tf):
        df = []
        for i in data:
            for j in tf:
                for key, val in enumerate(j['data']):
                    if(i == val):
                        df.append(i)
        tmpDf = (reduce(lambda d, c: d.update(
            [(c, d.get(c, 0)+1)]) or d, df, {}))
        totalD = len(tf)
        idf = {}
        for i in tmpDf.items():
            idf[i[0]] = math.log(totalD/i[1])
        return idf

    def getTfIdf(self, data, tf, idf):
        tfIdf = []
        for i in tf:
            tmpTfIdf = {}
            for y in data:
                isVal = (i['data'].get(y)*idf.get(y)
                         ) if (i['data'].get(y) != None) else 0
                tmpTfIdf[y] = isVal
            tfIdf.append({**i, 'data': tmpTfIdf})
        return tfIdf

    def getTfIdfToFeature(self, tfIdf):
        features = []
        for y in tfIdf:
            tmp_data = []
            for j in y['data']:
                tmp_data.append(float(y['data'].get(j)))
            tmp_data.append(y['id'])
            tmp_data.append(y['label'])
            features.append(tmp_data)

        return features
