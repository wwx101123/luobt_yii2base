<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AccountHistory;

/**
 * AccountHistorySearch represents the model behind the search form about `common\models\AccountHistory`.
 */
class AccountHistorySearch extends AccountHistory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'member_id', 'created_at'], 'integer'],
            [['amount'], 'number'],
            [['account', 'bz','username','account_type'], 'safe'],
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
    public function search($params, $member_id=NULL)
    {
        $query = AccountHistory::find()->OrderBy('member_id');

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

        if ($this->username) {
             $member = Member::findByUsername($this->username);
             if($member){
                $query->andFilterWhere(['member_id'=>$member->id]);
             }else{
                $this->addError('username', '会员编号不存在！');
             }
        }
        if ($this->account!='All') {
            $query->andFilterWhere(['like', 'account', $this->account]);
        }
        if ($this->bz!='All') {
            $query->andFilterWhere(['like', 'bz', $this->bz]);
        }

        if (isset($member_id)) {
            $this->member_id = $member_id;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'member_id' => $this->member_id,
            'amount' => $this->amount,
            'created_at' => $this->created_at,
        ]);
        
        return $dataProvider;
    }

    
}
