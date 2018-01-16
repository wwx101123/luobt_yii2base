<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AccountChange;

/**
 * AccountChangeSearch represents the model behind the search form about `common\models\AccountChange`.
 */
class AccountChangeSearch extends AccountChange
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'member_id', 'type', 'create_time'], 'integer'],
            [['old_money', 'new_money', 'money'], 'number'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = AccountChange::find();
        $query->orderBy('id desc');
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
            'member_id' => $this->member_id,
            'old_money' => $this->old_money,
            'new_money' => $this->new_money,
            'type' => $this->type,
            'create_time' => $this->create_time,
            'money' => $this->money,
        ]);

        return $dataProvider;
    }
}
