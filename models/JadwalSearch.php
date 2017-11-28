<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Jadwal;

/**
 * JadwalSearch represents the model behind the search form about `app\models\Jadwal`.
 */
class JadwalSearch extends Jadwal
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['periode_dosen_pengajar', 'departemen_dosen_pengajar', 'nip_nidn_dosen_pengajar', 'periode_mata_kuliah_pengajar', 'program_studi_mata_kuliah_pengajar', 'kategori_koefisien_program_studi_mata_kuliah_pengajar', 'nama_mata_kuliah_pengajar', 'jenis_mata_kuliah_pengajar', 'jadwal_start', 'jadwal_end', 'last_updated_by', 'last_updated_time'], 'safe'],
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
        $query = Jadwal::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => [
                    'periode_dosen_pengajar'=>SORT_ASC,
                    'departemen_dosen_pengajar'=>SORT_ASC,
                    'nip_nidn_dosen_pengajar'=>SORT_ASC,
                    'nama_mata_kuliah_pengajar'=>SORT_ASC,
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
            'id' => $this->id,
            'jadwal_start' => $this->jadwal_start,
            'jadwal_end' => $this->jadwal_end,
            'last_updated_time' => $this->last_updated_time,
        ]);

        $query->andFilterWhere(['like', 'periode_dosen_pengajar', $this->periode_dosen_pengajar])
            ->andFilterWhere(['like', 'departemen_dosen_pengajar', $this->departemen_dosen_pengajar])
            ->andFilterWhere(['like', 'nip_nidn_dosen_pengajar', $this->nip_nidn_dosen_pengajar])
            ->andFilterWhere(['like', 'periode_mata_kuliah_pengajar', $this->periode_mata_kuliah_pengajar])
            ->andFilterWhere(['like', 'program_studi_mata_kuliah_pengajar', $this->program_studi_mata_kuliah_pengajar])
            ->andFilterWhere(['like', 'kategori_koefisien_program_studi_mata_kuliah_pengajar', $this->kategori_koefisien_program_studi_mata_kuliah_pengajar])
            ->andFilterWhere(['like', 'nama_mata_kuliah_pengajar', $this->nama_mata_kuliah_pengajar])
            ->andFilterWhere(['like', 'jenis_mata_kuliah_pengajar', $this->jenis_mata_kuliah_pengajar])
            ->andFilterWhere(['like', 'last_updated_by', $this->last_updated_by]);

        return $dataProvider;
    }
}
