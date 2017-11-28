<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pengajar;

/**
 * PengajarSearch represents the model behind the search form about `app\models\Pengajar`.
 */
class PengajarSearch extends Pengajar
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['periode_dosen', 'departemen_dosen', 'nip_nidn_dosen', 'periode_mata_kuliah', 'program_studi_mata_kuliah', 'kategori_koefisien_program_studi_mata_kuliah', 'nama_mata_kuliah', 'jenis_mata_kuliah', 'last_updated_by', 'last_updated_time'], 'safe'],
            [['skenario'], 'integer'],
            [['sks_ekivalen', 'sks_kum', 'bkd_fte'], 'number'],
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
        $query = Pengajar::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => [
                    'periode_dosen'=>SORT_ASC,
                    'departemen_dosen'=>SORT_ASC,
                    'nip_nidn_dosen'=>SORT_ASC,
                    'jenis_mata_kuliah'=>SORT_ASC,
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('periodeDosen');

        // grid filtering conditions
        $query->andFilterWhere([
            'skenario' => $this->skenario,
            'sks_ekivalen' => $this->sks_ekivalen,
            'sks_kum' => $this->sks_kum,
            'bkd_fte' => $this->bkd_fte,
            'last_updated_time' => $this->last_updated_time,
        ]);

        $query->andFilterWhere(['like', 'periode_dosen', $this->periode_dosen])
            ->andFilterWhere(['like', 'departemen_dosen', $this->departemen_dosen])
            ->andFilterWhere(['like', 'dosen.nama_dosen', $this->nip_nidn_dosen])
            ->andFilterWhere(['like', 'periode_mata_kuliah', $this->periode_mata_kuliah])
            ->andFilterWhere(['like', 'program_studi_mata_kuliah', $this->program_studi_mata_kuliah])
            ->andFilterWhere(['like', 'kategori_koefisien_program_studi_mata_kuliah', $this->kategori_koefisien_program_studi_mata_kuliah])
            ->andFilterWhere(['like', 'nama_mata_kuliah', $this->nama_mata_kuliah])
            ->andFilterWhere(['like', 'jenis_mata_kuliah', $this->jenis_mata_kuliah])
            ->andFilterWhere(['like', 'last_updated_by', $this->last_updated_by]);

        return $dataProvider;
    }
}
