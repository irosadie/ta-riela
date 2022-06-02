<?php

namespace app\models\alumnuses\searches;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\alumnuses\generals\QuestionnaireDetails as QuestionnaireDetailsModel;

/**
 * QuestionnaireDetails represents the model behind the search form of `app\models\alumnuses\generals\QuestionnaireDetails`.
 */
class QuestionnaireDetails extends QuestionnaireDetailsModel
{
    public $query;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'questionnaire_id', 'content', 'group_id', 'queue_of_parent', 'queue_of_group', 'created_at', 'created_by', 'updated_at', 'deleted_at', 'deleted_by'], 'integer'],
            [['answer_type', 'answer_type_str', 'option_values', 'default_value'], 'safe'],
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
        $query = QuestionnaireDetailsModel::find();

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
            'questionnaire_id' => $this->questionnaire_id,
            'content' => $this->content,
            'group_id' => $this->group_id,
            'queue_of_parent' => $this->queue_of_parent,
            'queue_of_group' => $this->queue_of_group,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'answer_type', $this->answer_type])
            ->andFilterWhere(['like', 'answer_type_str', $this->answer_type_str])
            ->andFilterWhere(['like', 'option_values', $this->option_values])
            ->andFilterWhere(['like', 'default_value', $this->default_value]);

        $query->orFilterWhere(['like', 'answer_type', $this->query])
            ->orFilterWhere(['like', 'answer_type_str', $this->query])
            ->orFilterWhere(['like', 'option_values', $this->query])
            ->orFilterWhere(['like', 'default_value', $this->query]);

        return $dataProvider;
    }
}