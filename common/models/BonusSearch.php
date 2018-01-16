<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Bonus;

/**
 * BonusSearch represents the model behind the search form about `common\models\Bonus`.
 */
class BonusSearch extends Bonus
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'member_id', 'bonus_type', 'account_type', 'create_time', 'today_time', 'clear_time', 'state', 'reg_id'], 'integer'],
            [['amount', 'start_amount', 'end_amount'], 'number'],
            [['bz'], 'safe'],
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
        $query = Bonus::find();

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
            'bonus_type' => $this->bonus_type,
            'account_type' => $this->account_type,
            'create_time' => $this->create_time,
            'today_time' => $this->today_time,
            'clear_time' => $this->clear_time,
            'amount' => $this->amount,
            'start_amount' => $this->start_amount,
            'end_amount' => $this->end_amount,
            'state' => $this->state,
            'reg_id' => $this->reg_id,
        ]);

        $query->andFilterWhere(['like', 'bz', $this->bz]);

        return $dataProvider;
    }

    public function searchView($params)
    {
        $query = Bonus::find()->orderBy(['id' => SORT_DESC]);

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
            'bonus_type' => $this->bonus_type,
            'account_type' => $this->account_type,
            'create_time' => $this->create_time,
            'today_time' => $this->today_time,
            'clear_time' => $this->clear_time,
            'amount' => $this->amount,
            'start_amount' => $this->start_amount,
            'end_amount' => $this->end_amount,
            'state' => $this->state,
            'reg_id' => $this->reg_id,
        ]);

        $query->andFilterWhere(['like', 'bz', $this->bz]);

        return $dataProvider;
    }

    public function searchAllDay($params)
    {
        $query = Bonus::find();
        $query->groupBy('today_time');
        $query->orderBy('today_time desc');
        $query->select('
            sum(case when bonus_type < 9 then amount else amount * 0 end) as b_all,
            sum(case when bonus_type = 1 then amount else amount * 0 end) as b1,
            sum(case when bonus_type = 2 then amount else amount * 0 end) as b2,
            sum(case when bonus_type = 3 then amount else amount * 0 end) as b3,
            sum(case when bonus_type = 4 then amount else amount * 0 end) as b4,
            sum(case when bonus_type = 5 then amount else amount * 0 end) as b5,
            sum(case when bonus_type = 6 then amount else amount * 0 end) as b6,
            sum(case when bonus_type = 7 then amount else amount * 0 end) as b7,
            sum(case when bonus_type = 8 then amount else amount * 0 end) as b8,
            sum(case when bonus_type = 9 then amount else amount * 0 end) as b9,
            sum(case when bonus_type = 10 then amount else amount * 0 end) as b10, today_time');
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
            'bonus_type' => $this->bonus_type,
            'account_type' => $this->account_type,
            'create_time' => $this->create_time,
            'today_time' => $this->today_time,
            'amount' => $this->amount,
            'clear_time' => $this->clear_time,
            'start_amount' => $this->start_amount,
            'end_amount' => $this->end_amount,
        ]);

        $query->andFilterWhere(['like', 'bz', $this->bz]);

        return $dataProvider;
    }

    public function searchAll($params)
    {
        $query = Bonus::find();
        $query->groupBy('today_time,member_id');
        $query->orderBy('member_id desc');
        $query->select('
         sum(case when bonus_type < 9 then amount else amount*0 end) as b_all,
         sum(case when bonus_type=1 then amount else amount*0 end) as b1,
         sum(case when bonus_type=2 then amount else amount*0 end) as b2,
         sum(case when bonus_type=3 then amount else amount*0 end) as b3,
         sum(case when bonus_type=4 then amount else amount*0 end) as b4,
         sum(case when bonus_type=5 then amount else amount*0 end) as b5,
         sum(case when bonus_type=6 then amount else amount*0 end) as b6,
         sum(case when bonus_type=7 then amount else amount*0 end) as b7,
         sum(case when bonus_type=8 then amount else amount*0 end) as b8,
         sum(case when bonus_type=9 then amount else amount*0 end) as b9,
         sum(case when bonus_type=10 then amount else amount*0 end) as b10,
         member_id,today_time');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'today_time' => SORT_DESC,            
                ]
            ],
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
            'bonus_type' => $this->bonus_type,
            'account_type' => $this->account_type,
            'create_time' => $this->create_time,
            'today_time' => $this->today_time,
            'amount' => $this->amount,
            'clear_time' => $this->clear_time,
            'start_amount' => $this->start_amount,
            'end_amount' => $this->end_amount,
        ]);

        $query->andFilterWhere(['like', 'bz', $this->bz]);

        return $dataProvider;
    }

    public function searchRatio($params)
    {
        $query = Bonus::find();
        $query->groupBy('today_time');
        $query->orderBy('today_time desc');
        $query->select('
         sum(case when bonus_type <= 6 then amount else amount * 0 end) as b_all, today_time');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'today_time' => $this->today_time,
        ]);
        return $dataProvider;
    }
}
