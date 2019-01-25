<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Lot;

/**
 * LotSeach represents the model behind the search form of `common\models\Lot`.
 */
class LotSeach extends Lot
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'seller_id', 'status'], 'integer'],
            [['name', 'description', 'start_date', 'end_date'], 'safe'],
            [['start_price', 'step'], 'number'],
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
        $query = Lot::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['end_date'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
             $query->where('1=1');
            //return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'seller_id' => $this->seller_id,
            'start_price' => $this->start_price,
            'step' => $this->step
        ]);

        if (in_array($this->status, [2,3,4])) {
            $query->andFilterWhere(['status' => $this->status]);
        } else if ($this->status == 5) {
            $subQuery = (new \yii\db\Query)
                                ->select('lot_id')
                                ->from('bargain_result b')
                                ->where('lot_id = lot.id');

            $query->andFilterWhere([
                'status' => 1
            ]);
            $query->andFilterWhere([
                'not exists', $subQuery
            ]);
        } else if ($this->status == 6) {
            $query->andFilterWhere([
                '>',
                'start_date',
                date('Y-m-d')
            ]);
        } else if ($this->status == 1) {
            $query->andFilterWhere([
                'status' => 1
            ]);
            $query->andFilterWhere([
                '<=',
                'start_date',
                date('Y-m-d')
            ]);
            $query->andFilterWhere([
                '>=',
                'end_date',
                date('Y-m-d')
            ]);
        }

        if (!empty($this->start_date)) {
            if (is_string($this->start_date) && mb_stripos($this->start_date, ' - ') !== false) {
                $date = explode(' - ', $this->start_date);
            }
            if (!empty($date[0]) && !empty($date[1])) {
                $query->andFilterWhere([
                    'between',
                    'start_date',
                    $date[0],
                    $date[1]
                ]);
            }
        }

        if (!empty($this->end_date)) {
            if (is_string($this->end_date) && mb_stripos($this->end_date, ' - ') !== false) {
                $date = explode(' - ', $this->end_date);
            }
            if (!empty($date[0]) && !empty($date[1])) {
                $query->andFilterWhere([
                    'between',
                    'end_date',
                    $date[0],
                    $date[1]
                ]);
            }
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
