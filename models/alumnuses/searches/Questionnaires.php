<?php

namespace app\models\alumnuses\searches;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\alumnuses\generals\Questionnaires as QuestionnairesModel;

/**
 * Questionnaires represents the model behind the search form of `app\models\alumnuses\generals\Questionnaires`.
 */
class Questionnaires extends QuestionnairesModel
{
    public $query;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'code', 'begin_at', 'end_at', 'is_obligation', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['slug', 'title', 'desc', 'schools', 'year_of_graduates', 'privacy'], 'safe'],
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
        $query = QuestionnairesModel::find();

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
            'code' => $this->code,
            'begin_at' => $this->begin_at,
            'end_at' => $this->end_at,
            'is_obligation' => $this->is_obligation,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'schools', $this->schools])
            ->andFilterWhere(['like', 'year_of_graduates', $this->year_of_graduates])
            ->andFilterWhere(['like', 'privacy', $this->privacy]);
        
        $query->orFilterWhere(['like', 'slug', $this->query])
            ->orFilterWhere(['like', 'title', $this->query])
            ->orFilterWhere(['like', 'desc', $this->query])
            ->orFilterWhere(['like', 'schools', $this->query])
            ->orFilterWhere(['like', 'year_of_graduates', $this->query])
            ->orFilterWhere(['like', 'privacy', $this->query]);

        return $dataProvider;
    }
}