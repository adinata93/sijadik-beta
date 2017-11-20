<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pengajar".
 *
 * @property string $periode_dosen
 * @property string $departemen_dosen
 * @property string $nip_nidn_dosen
 * @property string $periode_mata_kuliah
 * @property string $program_studi_mata_kuliah
 * @property string $kategori_koefisien_program_studi_mata_kuliah
 * @property string $nama_mata_kuliah
 * @property string $jenis_mata_kuliah
 * @property integer $skenario
 * @property string $sks_ekivalen
 * @property string $sks_kum
 * @property string $bkd_fte
 * @property string $last_updated_by
 * @property string $last_updated_time
 *
 * @property Jadwal[] $jadwals
 * @property Dosen $periodeDosen
 * @property MataKuliah $periodeMataKuliah
 */
class Pengajar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pengajar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['periode_dosen', 'departemen_dosen', 'nip_nidn_dosen', 'periode_mata_kuliah', 'program_studi_mata_kuliah', 'kategori_koefisien_program_studi_mata_kuliah', 'nama_mata_kuliah', 'jenis_mata_kuliah', 'last_updated_by', 'last_updated_time'], 'required'],
            [['skenario'], 'integer'],
            [['sks_ekivalen', 'sks_kum', 'bkd_fte'], 'number'],
            [['last_updated_time'], 'safe'],
            [['periode_dosen', 'periode_mata_kuliah'], 'string', 'max' => 13],
            [['departemen_dosen', 'program_studi_mata_kuliah', 'kategori_koefisien_program_studi_mata_kuliah', 'nama_mata_kuliah', 'jenis_mata_kuliah', 'last_updated_by'], 'string', 'max' => 100],
            [['nip_nidn_dosen'], 'string', 'max' => 18],
            [['periode_dosen', 'departemen_dosen', 'nip_nidn_dosen'], 'exist', 'skipOnError' => true, 'targetClass' => Dosen::className(), 'targetAttribute' => ['periode_dosen' => 'periode', 'departemen_dosen' => 'departemen', 'nip_nidn_dosen' => 'nip_nidn']],
            [['periode_mata_kuliah', 'program_studi_mata_kuliah', 'kategori_koefisien_program_studi_mata_kuliah', 'nama_mata_kuliah', 'jenis_mata_kuliah'], 'exist', 'skipOnError' => true, 'targetClass' => MataKuliah::className(), 'targetAttribute' => ['periode_mata_kuliah' => 'periode', 'program_studi_mata_kuliah' => 'program_studi', 'kategori_koefisien_program_studi_mata_kuliah' => 'kategori_koefisien_program_studi', 'nama_mata_kuliah' => 'nama', 'jenis_mata_kuliah' => 'jenis']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'periode_dosen' => 'Periode',
            'departemen_dosen' => 'Departemen',
            'nip_nidn_dosen' => 'Nama Dosen',
            'periode_mata_kuliah' => 'Periode',
            'program_studi_mata_kuliah' => 'Program Studi',
            'kategori_koefisien_program_studi_mata_kuliah' => 'Kategori Koefisien',
            'nama_mata_kuliah' => 'Nama Mata Kuliah',
            'jenis_mata_kuliah' => 'Jenis Mata Kuliah',
            'skenario' => 'Skenario',
            'sks_ekivalen' => 'Sks Ekivalen',
            'sks_kum' => 'SKS KUM',
            'bkd_fte' => 'BKD/FTE',
            'last_updated_by' => 'Last Updated By',
            'last_updated_time' => 'Last Updated Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJadwals()
    {
        return $this->hasMany(Jadwal::className(), ['periode_dosen_pengajar' => 'periode_dosen', 'departemen_dosen_pengajar' => 'departemen_dosen', 'nip_nidn_dosen_pengajar' => 'nip_nidn_dosen', 'periode_mata_kuliah_pengajar' => 'periode_mata_kuliah', 'program_studi_mata_kuliah_pengajar' => 'program_studi_mata_kuliah', 'kategori_koefisien_program_studi_mata_kuliah_pengajar' => 'kategori_koefisien_program_studi_mata_kuliah', 'nama_mata_kuliah_pengajar' => 'nama_mata_kuliah', 'jenis_mata_kuliah_pengajar' => 'jenis_mata_kuliah']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodeDosen()
    {
        return $this->hasOne(Dosen::className(), ['periode' => 'periode_dosen', 'departemen' => 'departemen_dosen', 'nip_nidn' => 'nip_nidn_dosen']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodeMataKuliah()
    {
        return $this->hasOne(MataKuliah::className(), ['periode' => 'periode_mata_kuliah', 'program_studi' => 'program_studi_mata_kuliah', 'kategori_koefisien_program_studi' => 'kategori_koefisien_program_studi_mata_kuliah', 'nama' => 'nama_mata_kuliah', 'jenis' => 'jenis_mata_kuliah']);
    }
}
