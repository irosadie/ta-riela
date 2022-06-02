<?php

namespace app\models\mains\searches;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\mains\generals\Requests as RequestsModel;

/**
 * Requests represents the model behind the search form of `app\models\mains\generals\Requests`.
 */
class Requests extends RequestsModel
{
    /**
     * {@inheritdoc}
     */
    public $query;
    public function rules()
    {
        return [
            [['id', 'project_id', 'company_id', 'prepared_designed_id', 'aproved_company_id', 'aproved_designed_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['code', 'name', 'desc', 'report_number', 'date', 'location', 'prepared_designed_str', 'prepared_signature', 'prepared_signature_date', 'aproved_str', 'aproved_signature', 'aproved_signature_date'], 'safe'],
            ['query', 'safe']
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
        $query = RequestsModel::find();

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
            'date' => $this->date,
            'project_id' => $this->project_id,
            'company_id' => $this->company_id,
            'prepared_designed_id' => $this->prepared_designed_id,
            'prepared_signature_date' => $this->prepared_signature_date,
            'aproved_company_id' => $this->aproved_company_id,
            'aproved_designed_id' => $this->aproved_designed_id,
            'aproved_signature_date' => $this->aproved_signature_date,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'report_number', $this->report_number])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'prepared_designed_str', $this->prepared_designed_str])
            ->andFilterWhere(['like', 'prepared_signature', $this->prepared_signature])
            ->andFilterWhere(['like', 'aproved_str', $this->aproved_str])
            ->andFilterWhere(['like', 'aproved_signature', $this->aproved_signature]);

        $query->orFilterWhere(['like', 'code', $this->query])
            ->orFilterWhere(['like', 'name', $this->query])
            ->orFilterWhere(['like', 'desc', $this->query])
            ->orFilterWhere(['like', 'report_number', $this->query])
            ->orFilterWhere(['like', 'location', $this->query])
            ->orFilterWhere(['like', 'prepared_designed_str', $this->query])
            ->orFilterWhere(['like', 'prepared_signature', $this->query])
            ->orFilterWhere(['like', 'aproved_str', $this->query])
            ->orFilterWhere(['like', 'aproved_signature', $this->query]);

        return $dataProvider;
    }
}