<?php

namespace app\models\alumnuses\searches;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\alumnuses\generals\MNations as MNationsModel;

/**
 * MNations represents the model behind the search form of `app\models\alumnuses\generals\MNations`.
 */
class MNations extends MNationsModel
{
    public $query;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['code', 'name'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = MNationsModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'name', $this->name]);

        $query->orFilterWhere(['like', 'code', $this->query])
            ->orFilterWhere(['like', 'name', $this->query]);

        return $dataProvider;
    }
}