<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Order;
use common\models\Member;
/**
 * OrderSearch represents the model behind the search form about `common\models\Order`.
 */
class OrderSearch extends Order
{   

    public $s_date ;
    public $e_date ;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'order_status', 'shipping_status', 'pay_status', 'shipping_id', 'pay_id', 'confirm_time','delivery'], 'integer'],
            [['order_no', 'name', 'address', 'postcode', 'tel', 'pay_name','s_date','e_date','usename'], 'safe'],
            [['goods_amount', 'order_amount',], 'number'],
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

        $query = Order::find();
        $query->joinWith(['member'])->where(['>','ld_member.activate',0]);

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
            'order_no' => $this->order_no,
            'order_status' => $this->order_status,
            'shipping_status' => $this->shipping_status,
            'pay_status' => $this->pay_status,
            'shipping_id' => $this->shipping_id,
            'pay_id' => $this->pay_id,
            'goods_amount' => $this->goods_amount,
            'order_amount' => $this->order_amount,
            'confirm_time' => $this->confirm_time,
            'delivery' => $this->delivery,

        ]);
        if ($this->usename) {
             $member=Member::findByUsername($this->usename);
             if($member){
                $query->andFilterWhere(['user_id'=>$member->id]);
             }
        }
        if ($this->s_date) {
            if (empty($this->e_date)) {
                $this->e_date = date('Y-m-d H:i',strtotime($this->s_date) + 24 *3600);
            }
            $query->andFilterWhere(['between','create_time',strtotime($this->s_date),strtotime($this->e_date)]);
        }
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'postcode', $this->postcode])
            ->andFilterWhere(['like', 'tel', $this->tel])
            ->andFilterWhere(['like', 'pay_name', $this->pay_name]);
        $query->orderBy('order_status asc');

        return $dataProvider;
    }
}
