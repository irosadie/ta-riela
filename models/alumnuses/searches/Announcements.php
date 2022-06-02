<?php

namespace app\models\alumnuses\searches;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\alumnuses\generals\Announcements as AnnouncementsModel;

/**
 * Announcements represents the model behind the search form of `app\models\alumnuses\generals\Announcements`.
 */
class Announcements extends AnnouncementsModel
{
    public $query;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'published_at', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['title', 'slug', 'desc', 'content', 'read_more_uri', 'thumbnail', 'file_json', 'year_of_graduates', 'schools', 'privacy'], 'safe'],
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
        $query = AnnouncementsModel::find();

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
            'status' => $this->status,
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'read_more_uri', $this->read_more_uri])
            ->andFilterWhere(['like', 'thumbnail', $this->thumbnail])
            ->andFilterWhere(['like', 'file_json', $this->file_json])
            ->andFilterWhere(['like', 'year_of_graduates', $this->year_of_graduates])
            ->andFilterWhere(['like', 'schools', $this->schools])
            ->andFilterWhere(['like', 'privacy', $this->privacy]);

        $query->orFilterWhere(['like', 'title', $this->query])
            ->orFilterWhere(['like', 'slug', $this->query])
            ->orFilterWhere(['like', 'desc', $this->query])
            ->orFilterWhere(['like', 'content', $this->query])
            ->orFilterWhere(['like', 'read_more_uri', $this->query])
            ->orFilterWhere(['like', 'thumbnail', $this->query])
            ->orFilterWhere(['like', 'file_json', $this->query])
            ->orFilterWhere(['like', 'year_of_graduates', $this->query])
            ->orFilterWhere(['like', 'schools', $this->query])
            ->orFilterWhere(['like', 'privacy', $this->query]);

        return $dataProvider;
    }
}