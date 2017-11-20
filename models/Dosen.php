<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dosen".
 *
 * @property string $periode
 * @property string $departemen
 * @property string $nama_dosen
 * @property string $nip_nidn
 * @property string $total_sks_kum
 * @property string $total_bkd_fte
 * @property string $last_updated_by
 * @property string $last_updated_time
 *
 * @property Departemen $departemen0
 * @property Periode $periode0
 * @property Pembimbing[] $pembimbings
 * @property Pengajar[] $pengajars
 * @property MataKuliah[] $periodeMataKuliahs
 * @property Penguji[] $pengujis
 */
class Dosen extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dosen';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['periode', 'departemen', 'nama_dosen', 'nip_nidn', 'last_updated_by', 'last_updated_time'], 'required'],
            [['total_sks_kum', 'total_bkd_fte'], 'number'],
            [['last_updated_time'], 'safe'],
            [['periode'], 'string', 'max' => 13],
            [['departemen', 'nama_dosen', 'last_updated_by'], 'string', 'max' => 100],
            [['nip_nidn'], 'string', 'max' => 18],
            [['departemen'], 'exist', 'skipOnError' => true, 'targetClass' => Departemen::className(), 'targetAttribute' => ['departemen' => 'nama']],
            [['periode'], 'exist', 'skipOnError' => true, 'targetClass' => Periode::className(), 'targetAttribute' => ['periode' => 'nama']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'periode' => 'Periode',
            'departemen' => 'Departemen',
            'nama_dosen' => 'Nama Dosen',
            'nip_nidn' => 'NIP/NIDN',
            'total_sks_kum' => 'Total SKS KUM',
            'total_bkd_fte' => 'Total BKD/FTE',
            'last_updated_by' => 'Last Updated By',
            'last_updated_time' => 'Last Updated Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartemen0()
    {
        return $this->hasOne(Departemen::className(), ['nama' => 'departemen']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriode0()
    {
        return $this->hasOne(Periode::className(), ['nama' => 'periode']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPembimbings()
    {
        return $this->hasMany(Pembimbing::className(), ['periode_dosen' => 'periode', 'departemen_dosen' => 'departemen', 'nip_nidn_dosen' => 'nip_nidn']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPengajars()
    {
        return $this->hasMany(Pengajar::className(), ['periode_dosen' => 'periode', 'departemen_dosen' => 'departemen', 'nip_nidn_dosen' => 'nip_nidn']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodeMataKuliahs()
    {
        return $this->hasMany(MataKuliah::className(), ['periode' => 'periode_mata_kuliah', 'program_studi' => 'program_studi_mata_kuliah', 'kategori_koefisien_program_studi' => 'kategori_koefisien_program_studi_mata_kuliah', 'nama' => 'nama_mata_kuliah', 'jenis' => 'jenis_mata_kuliah'])->viaTable('pengajar', ['periode_dosen' => 'periode', 'departemen_dosen' => 'departemen', 'nip_nidn_dosen' => 'nip_nidn']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPengujis()
    {
        return $this->hasMany(Penguji::className(), ['periode_dosen' => 'periode', 'departemen_dosen' => 'departemen', 'nip_nidn_dosen' => 'nip_nidn']);
    }
}
