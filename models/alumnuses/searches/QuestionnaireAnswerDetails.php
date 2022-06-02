<?php

namespace app\models\alumnuses\searches;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\alumnuses\generals\QuestionnaireAnswerDetails as QuestionnaireAnswerDetailsModel;

/**
 * QuestionnaireAnswerDetails represents the model behind the search form of `app\models\alumnuses\generals\QuestionnaireAnswerDetails`.
 */
class QuestionnaireAnswerDetails extends QuestionnaireAnswerDetailsModel
{
    public $query;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'questionnaire_answer_id', 'questionnaire_detail_id', 'questionnaire_answer_value_id', 'answered_at', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['questionnaire_content_str', 'questionnaire_answer_value_str', 'questionnaire_answer_type_str'], 'safe'],
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
        $query = QuestionnaireAnswerDetailsModel::find();

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
            'questionnaire_answer_id' => $this->questionnaire_answer_id,
            'questionnaire_detail_id' => $this->questionnaire_detail_id,
            'questionnaire_answer_value_id' => $this->questionnaire_answer_value_id,
            'answered_at' => $this->answered_at,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'questionnaire_content_str', $this->questionnaire_content_str])
            ->andFilterWhere(['like', 'questionnaire_answer_value_str', $this->questionnaire_answer_value_str])
            ->andFilterWhere(['like', 'questionnaire_answer_type_str', $this->questionnaire_answer_type_str]);
        
        $query->orFilterWhere(['like', 'questionnaire_content_str', $this->query])
            ->orFilterWhere(['like', 'questionnaire_answer_value_str', $this->query])
            ->orFilterWhere(['like', 'questionnaire_answer_type_str', $this->query]);

        return $dataProvider;
    }
}