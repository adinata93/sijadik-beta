<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProgramStudi;

/**
 * ProgramStudiSearch represents the model behind the search form about `app\models\ProgramStudi`.
 */
class ProgramStudiSearch extends ProgramStudi
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kategori_koefisien', 'nama', 'last_updated_by', 'last_updated_time'], 'safe'],
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
        $query = ProgramStudi::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => [
                    'kategori_koefisien'=>SORT_ASC,
                    'nama'=>SORT_ASC,
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'last_updated_time' => $this->last_updated_time,
        ]);

        $query->andFilterWhere(['like', 'kategori_koefisien', $this->kategori_koefisien])
            ->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'last_updated_by', $this->last_updated_by]);

        return $dataProvider;
    }
}
