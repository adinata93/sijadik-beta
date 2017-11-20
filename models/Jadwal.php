<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jadwal".
 *
 * @property integer $id
 * @property string $periode_dosen_pengajar
 * @property string $departemen_dosen_pengajar
 * @property string $nip_nidn_dosen_pengajar
 * @property string $periode_mata_kuliah_pengajar
 * @property string $program_studi_mata_kuliah_pengajar
 * @property string $kategori_koefisien_program_studi_mata_kuliah_pengajar
 * @property string $nama_mata_kuliah_pengajar
 * @property string $jenis_mata_kuliah_pengajar
 * @property string $jadwal_start
 * @property string $jadwal_end
 * @property string $last_updated_by
 * @property string $last_updated_time
 *
 * @property Pengajar $periodeDosenPengajar
 */
class Jadwal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jadwal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['periode_dosen_pengajar', 'departemen_dosen_pengajar', 'nip_nidn_dosen_pengajar', 'periode_mata_kuliah_pengajar', 'program_studi_mata_kuliah_pengajar', 'kategori_koefisien_program_studi_mata_kuliah_pengajar', 'nama_mata_kuliah_pengajar', 'jenis_mata_kuliah_pengajar', 'jadwal_start', 'jadwal_end', 'last_updated_by', 'last_updated_time'], 'required'],
            [['jadwal_start', 'jadwal_end', 'last_updated_time'], 'safe'],
            [['periode_dosen_pengajar', 'periode_mata_kuliah_pengajar'], 'string', 'max' => 13],
            [['departemen_dosen_pengajar', 'program_studi_mata_kuliah_pengajar', 'kategori_koefisien_program_studi_mata_kuliah_pengajar', 'nama_mata_kuliah_pengajar', 'jenis_mata_kuliah_pengajar', 'last_updated_by'], 'string', 'max' => 100],
            [['nip_nidn_dosen_pengajar'], 'string', 'max' => 18],
            [['periode_dosen_pengajar', 'departemen_dosen_pengajar', 'nip_nidn_dosen_pengajar', 'periode_mata_kuliah_pengajar', 'program_studi_mata_kuliah_pengajar', 'kategori_koefisien_program_studi_mata_kuliah_pengajar', 'nama_mata_kuliah_pengajar', 'jenis_mata_kuliah_pengajar'], 'exist', 'skipOnError' => true, 'targetClass' => Pengajar::className(), 'targetAttribute' => ['periode_dosen_pengajar' => 'periode_dosen', 'departemen_dosen_pengajar' => 'departemen_dosen', 'nip_nidn_dosen_pengajar' => 'nip_nidn_dosen', 'periode_mata_kuliah_pengajar' => 'periode_mata_kuliah', 'program_studi_mata_kuliah_pengajar' => 'program_studi_mata_kuliah', 'kategori_koefisien_program_studi_mata_kuliah_pengajar' => 'kategori_koefisien_program_studi_mata_kuliah', 'nama_mata_kuliah_pengajar' => 'nama_mata_kuliah', 'jenis_mata_kuliah_pengajar' => 'jenis_mata_kuliah']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'periode_dosen_pengajar' => 'Periode Dosen Pengajar',
            'departemen_dosen_pengajar' => 'Departemen Dosen Pengajar',
            'nip_nidn_dosen_pengajar' => 'Nip Nidn Dosen Pengajar',
            'periode_mata_kuliah_pengajar' => 'Periode Mata Kuliah Pengajar',
            'program_studi_mata_kuliah_pengajar' => 'Program Studi Mata Kuliah Pengajar',
            'kategori_koefisien_program_studi_mata_kuliah_pengajar' => 'Kategori Koefisien Program Studi Mata Kuliah Pengajar',
            'nama_mata_kuliah_pengajar' => 'Nama Mata Kuliah Pengajar',
            'jenis_mata_kuliah_pengajar' => 'Jenis Mata Kuliah Pengajar',
            'jadwal_start' => 'Jadwal Start',
            'jadwal_end' => 'Jadwal End',
            'last_updated_by' => 'Last Updated By',
            'last_updated_time' => 'Last Updated Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodeDosenPengajar()
    {
        return $this->hasOne(Pengajar::className(), ['periode_dosen' => 'periode_dosen_pengajar', 'departemen_dosen' => 'departemen_dosen_pengajar', 'nip_nidn_dosen' => 'nip_nidn_dosen_pengajar', 'periode_mata_kuliah' => 'periode_mata_kuliah_pengajar', 'program_studi_mata_kuliah' => 'program_studi_mata_kuliah_pengajar', 'kategori_koefisien_program_studi_mata_kuliah' => 'kategori_koefisien_program_studi_mata_kuliah_pengajar', 'nama_mata_kuliah' => 'nama_mata_kuliah_pengajar', 'jenis_mata_kuliah' => 'jenis_mata_kuliah_pengajar']);
    }
}
