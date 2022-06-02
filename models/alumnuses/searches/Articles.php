<?php

namespace app\models\alumnuses\searches;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\alumnuses\generals\Articles as ArticlesModel;

/**
 * Articles represents the model behind the search form of `app\models\alumnuses\generals\Articles`.
 */
class Articles extends ArticlesModel
{
    public $query;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'status', 'published_at', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['title', 'slug', 'content', 'seo_meta_data', 'tags'], 'safe'],
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
        $query = ArticlesModel::find();

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
            'category_id' => $this->category_id,
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
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'seo_meta_data', $this->seo_meta_data])
            ->andFilterWhere(['like', 'tags', $this->tags]);
        
        $query->orFilterWhere(['like', 'title', $this->query])
            ->orFilterWhere(['like', 'slug', $this->query])
            ->orFilterWhere(['like', 'content', $this->query])
            ->orFilterWhere(['like', 'seo_meta_data', $this->query])
            ->orFilterWhere(['like', 'tags', $this->query]);

        return $dataProvider;
    }
}