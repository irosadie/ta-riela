<?php

namespace app\models\alumnuses\searches;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\alumnuses\generals\PpdbInformations as PpdbInformationsModel;

/**
 * PpdbInformations represents the model behind the search form of `app\models\alumnuses\generals\PpdbInformations`.
 */
class PpdbInformations extends PpdbInformationsModel
{
    public $query;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'school_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['title', 'school_str', 'school_website_str', 'school_address_str', 'desc', 'content', 'time_range', 'original_link_info', 'thumbnail'], 'safe'],
            [['query'], 'safe']
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
        $query = PpdbInformationsModel::find();

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
            'school_id' => $this->school_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'school_str', $this->school_str])
            ->andFilterWhere(['like', 'school_website_str', $this->school_website_str])
            ->andFilterWhere(['like', 'school_address_str', $this->school_address_str])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'time_range', $this->time_range])
            ->andFilterWhere(['like', 'original_link_info', $this->original_link_info])
            ->andFilterWhere(['like', 'thumbnail', $this->thumbnail]);
        
        $query->orFilterWhere(['like', 'title', $this->query])
            ->orFilterWhere(['like', 'campus_str', $this->query])
            ->orFilterWhere(['like', 'school_website_str', $this->query])
            ->orFilterWhere(['like', 'school_address_str', $this->query])
            ->orFilterWhere(['like', 'desc', $this->query])
            ->orFilterWhere(['like', 'content', $this->query])
            ->orFilterWhere(['like', 'time_range', $this->query])
            ->orFilterWhere(['like', 'original_link_info', $this->query])
            ->orFilterWhere(['like', 'thumbnail', $this->query]);

        return $dataProvider;
    }
}