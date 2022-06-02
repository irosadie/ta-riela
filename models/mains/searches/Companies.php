<?php

namespace app\models\mains\searches;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\mains\generals\Companies as CompaniesModel;

/**
 * Companies represents the model behind the search form of `app\models\mains\generals\Companies`.
 */
class Companies extends CompaniesModel
{
    /**
     * {@inheritdoc}
     */
    public $query;
    public function rules()
    {
        return [
            [['id', 'pic_in_user_id', 'pic_ex_user_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['code', 'name', 'desc', 'address', 'npwp', 'telp', 'fax', 'email', 'pic_in_user_str', 'pic_in_user_phone', 'pic_ex_user_str', 'pic_ex_user_phone'], 'safe'],
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
        $query = CompaniesModel::find();

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
            'pic_in_user_id' => $this->pic_in_user_id,
            'pic_ex_user_id' => $this->pic_ex_user_id,
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
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'npwp', $this->npwp])
            ->andFilterWhere(['like', 'telp', $this->telp])
            ->andFilterWhere(['like', 'fax', $this->fax])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'pic_in_user_str', $this->pic_in_user_str])
            ->andFilterWhere(['like', 'pic_in_user_phone', $this->pic_in_user_phone])
            ->andFilterWhere(['like', 'pic_ex_user_str', $this->pic_ex_user_str])
            ->andFilterWhere(['like', 'pic_ex_user_phone', $this->pic_ex_user_phone]);

        $query->orFilterWhere(['like', 'code', $this->query])
            ->orFilterWhere(['like', 'name', $this->query])
            ->orFilterWhere(['like', 'desc', $this->query])
            ->orFilterWhere(['like', 'address', $this->query])
            ->orFilterWhere(['like', 'npwp', $this->query])
            ->orFilterWhere(['like', 'telp', $this->query])
            ->orFilterWhere(['like', 'fax', $this->query])
            ->orFilterWhere(['like', 'email', $this->query])
            ->orFilterWhere(['like', 'pic_in_user_str', $this->query])
            ->orFilterWhere(['like', 'pic_in_user_phone', $this->query])
            ->orFilterWhere(['like', 'pic_ex_user_str', $this->query])
            ->orFilterWhere(['like', 'pic_ex_user_phone', $this->query]);

        return $dataProvider;
    }
}