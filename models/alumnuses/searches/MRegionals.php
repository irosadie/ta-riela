<?php

namespace app\models\alumnuses\searches;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\alumnuses\generals\MRegionals as MRegionalsModel;

/**
 * MRegionals represents the model behind the search form of `app\models\alumnuses\generals\MRegionals`.
 */
class MRegionals extends MRegionalsModel
{
    public $query;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'level'], 'integer'],
            [['region_id', 'name', 'parent_region_id'], 'safe'],
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
        $query = MRegionalsModel::find();

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
            'level' => $this->level,
        ]);

        $query->andFilterWhere(['like', 'region_id', $this->region_id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'parent_region_id', $this->parent_region_id]);

        $query->orFilterWhere(['like', 'region_id', $this->query])
            ->orFilterWhere(['like', 'name', $this->query])
            ->orFilterWhere(['like', 'parent_region_id', $this->query]);

        return $dataProvider;
    }
}