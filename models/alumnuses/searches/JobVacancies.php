<?php

namespace app\models\alumnuses\searches;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\alumnuses\generals\JobVacancies as JobVacanciesModel;

/**
 * JobVacancies represents the model behind the search form of `app\models\alumnuses\generals\JobVacancies`.
 */
class JobVacancies extends JobVacanciesModel
{
    public $query;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'company_id', 'salary_currency_id', 'submition_deadline', 'published_at', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['title', 'salary_range', 'salary_currency_str', 'skill_needed', 'company_str', 'requirements', 'desc', 'schools'], 'safe'],
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
        $query = JobVacanciesModel::find();

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
            'company_id' => $this->company_id,
            'salary_currency_id' => $this->salary_currency_id,
            'submition_deadline' => $this->submition_deadline,
            'published_at' => $this->published_at,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'salary_range', $this->salary_range])
            ->andFilterWhere(['like', 'salary_currency_str', $this->salary_currency_str])
            ->andFilterWhere(['like', 'skill_needed', $this->skill_needed])
            ->andFilterWhere(['like', 'company_str', $this->company_str])
            ->andFilterWhere(['like', 'requirements', $this->requirements])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'schools', $this->schools]);

        $query->orFilterWhere(['like', 'title', $this->query])
            ->orFilterWhere(['like', 'salary_range', $this->query])
            ->orFilterWhere(['like', 'salary_currency_str', $this->query])
            ->orFilterWhere(['like', 'skill_needed', $this->query])
            ->orFilterWhere(['like', 'company_str', $this->query])
            ->orFilterWhere(['like', 'requirements', $this->query])
            ->orFilterWhere(['like', 'desc', $this->query])
            ->orFilterWhere(['like', 'schools', $this->query]);

        return $dataProvider;
    }
}