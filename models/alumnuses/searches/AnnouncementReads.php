<?php

namespace app\models\alumnuses\searches;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\alumnuses\generals\AnnouncementReads as AnnouncementReadsModel;

/**
 * AnnouncementReads represents the model behind the search form of `app\models\alumnuses\generals\AnnouncementReads`.
 */
class AnnouncementReads extends AnnouncementReadsModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'announcement_id', 'read_at'], 'integer'],
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
        $query = AnnouncementReadsModel::find();

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
            'user_id' => $this->user_id,
            'announcement_id' => $this->announcement_id,
            'read_at' => $this->read_at,
        ]);

        return $dataProvider;
    }
}