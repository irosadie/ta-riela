<?php

namespace app\models\mains\searches;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\mains\generals\RequestDetailPerfilm as RequestDetailPerfilmModel;

/**
 * RequestDetailPerfilm represents the model behind the search form of `app\models\mains\generals\RequestDetailPerfilm`.
 */
class RequestDetailPerfilm extends RequestDetailPerfilmModel
{
    /**
     * {@inheritdoc}
     */
    public $query;
    public function rules()
    {
        return [
            [['id', 'request_detail_id', 'film_number', 'welder_id', 'welder_process_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['location_range', 'result'], 'safe'],
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
        $query = RequestDetailPerfilmModel::find();

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
            'request_detail_id' => $this->request_detail_id,
            'film_number' => $this->film_number,
            'welder_id' => $this->welder_id,
            'welder_process_id' => $this->welder_process_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'location_range', $this->location_range])
            ->andFilterWhere(['like', 'result', $this->result]);

        $query->orFilterWhere(['like', 'location_range', $this->query])
            ->orFilterWhere(['like', 'result', $this->query]);

        return $dataProvider;
    }
}