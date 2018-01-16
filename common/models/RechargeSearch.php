<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Recharge;

/**
 * RechargeSearch represents the model behind the search form about `common\models\Recharge`.
 */
class RechargeSearch extends Recharge
{
    public $username;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'state', 'pay_type', 'pay_time', ], 'integer'],
            [['re_money'], 'number'],
            [['info', 'username'], 'safe'],
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
        
        $query = Recharge::find()->joinWith(['member'])->orderBy(['id' => SORT_DESC]);

        // $query = Recharge::find()->joinWith(['member'])->where('member_id = 1')->select('ld_recharge.*, ld_member.username')->orderBy(['id' => SORT_DESC])->one();
        // echo '<pre>';
        // var_dump($query);
        // // var_dump($query->createCommand()->getRawSql());
        // echo '</pre>';
        // exit;

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
            'username' => $this->username,
            'id' => $this->id,
            'member_id' => $this->member_id,
            'type' => $this->type,
            're_money' => $this->re_money,
            'create_time' => $this->create_time,
            'confirm_time' => $this->confirm_time,
            'state' => $this->state,
            'pay_type' => $this->pay_type,
            'pay_time' => $this->pay_time,
            
        ]);

        $query->andFilterWhere(['like', 'info', $this->info]);
        // $query->andFilterWhere('like', array($this->username), $this->username);

        return $dataProvider;
    }
}
