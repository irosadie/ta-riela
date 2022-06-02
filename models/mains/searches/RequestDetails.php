<?php

namespace app\models\mains\searches;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\mains\generals\RequestDetails as RequestDetailsModel;

/**
 * RequestDetails represents the model behind the search form of `app\models\mains\generals\RequestDetails`.
 */
class RequestDetails extends RequestDetailsModel
{
    /**
     * {@inheritdoc}
     */
    public $query;
    public function rules()
    {
        return [
            [['id', 'request_id', 'diameter_id', 'material_id', 'method_id', 'line_class_id', 'status', 'shop_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['request_number', 'joint_number', 'drawing_number', 'line_number', 'diameter_str', 'material_str', 'method_str', 'multiple_welder_id', 'multiple_process_id', 'request_status', 'line_class_str', 'remark', 'shop_str'], 'safe'],
            [['thickness'], 'number'],
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
        $query = RequestDetailsModel::find();

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
            'request_id' => $this->request_id,
            'diameter_id' => $this->diameter_id,
            'thickness' => $this->thickness,
            'material_id' => $this->material_id,
            'method_id' => $this->method_id,
            'line_class_id' => $this->line_class_id,
            'status' => $this->status,
            'shop_id' => $this->shop_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'request_number', $this->request_number])
            ->andFilterWhere(['like', 'joint_number', $this->joint_number])
            ->andFilterWhere(['like', 'drawing_number', $this->drawing_number])
            ->andFilterWhere(['like', 'line_number', $this->line_number])
            ->andFilterWhere(['like', 'diameter_str', $this->diameter_str])
            ->andFilterWhere(['like', 'material_str', $this->material_str])
            ->andFilterWhere(['like', 'method_str', $this->method_str])
            ->andFilterWhere(['like', 'multiple_welder_id', $this->multiple_welder_id])
            ->andFilterWhere(['like', 'multiple_process_id', $this->multiple_process_id])
            ->andFilterWhere(['like', 'request_status', $this->request_status])
            ->andFilterWhere(['like', 'line_class_str', $this->line_class_str])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'shop_str', $this->shop_str]);

        $query->orFilterWhere(['like', 'request_number', $this->query])
            ->orFilterWhere(['like', 'joint_number', $this->query])
            ->orFilterWhere(['like', 'drawing_number', $this->query])
            ->orFilterWhere(['like', 'line_number', $this->query])
            ->orFilterWhere(['like', 'diameter_str', $this->query])
            ->orFilterWhere(['like', 'material_str', $this->query])
            ->orFilterWhere(['like', 'method_str', $this->query])
            ->orFilterWhere(['like', 'multiple_welder_id', $this->query])
            ->orFilterWhere(['like', 'multiple_process_id', $this->query])
            ->orFilterWhere(['like', 'request_status', $this->query])
            ->orFilterWhere(['like', 'line_class_str', $this->query])
            ->orFilterWhere(['like', 'remark', $this->query])
            ->orFilterWhere(['like', 'shop_str', $this->query]);

        return $dataProvider;
    }
}