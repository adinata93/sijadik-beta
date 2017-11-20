<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mata_kuliah".
 *
 * @property string $periode
 * @property string $fakultas_unit_pengajaran
 * @property string $kode_organisasi
 * @property string $program_studi
 * @property string $jenjang
 * @property string $program
 * @property string $kategori_koefisien_program_studi
 * @property string $nama
 * @property string $jenis
 * @property string $kode_kelas
 * @property string $jenis_kelas
 * @property string $last_updated_by
 * @property string $last_updated_time
 *
 * @property Periode $periode0
 * @property ProgramStudi $kategoriKoefisienProgramStudi
 * @property Pengajar[] $pengajars
 * @property Dosen[] $periodeDosens
 */
class MataKuliah extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mata_kuliah';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['periode', 'fakultas_unit_pengajaran', 'kode_organisasi', 'program_studi', 'jenjang', 'program', 'kategori_koefisien_program_studi', 'nama', 'jenis', 'kode_kelas', 'jenis_kelas', 'last_updated_by', 'last_updated_time'], 'required'],
            [['fakultas_unit_pengajaran'], 'string'],
            [['last_updated_time'], 'safe'],
            [['periode'], 'string', 'max' => 13],
            [['kode_organisasi', 'program_studi', 'jenjang', 'program', 'kategori_koefisien_program_studi', 'nama', 'jenis', 'kode_kelas', 'jenis_kelas', 'last_updated_by'], 'string', 'max' => 100],
            [['periode'], 'exist', 'skipOnError' => true, 'targetClass' => Periode::className(), 'targetAttribute' => ['periode' => 'nama']],
            [['kategori_koefisien_program_studi', 'program_studi'], 'exist', 'skipOnError' => true, 'targetClass' => ProgramStudi::className(), 'targetAttribute' => ['kategori_koefisien_program_studi' => 'kategori_koefisien', 'program_studi' => 'nama']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'periode' => 'Periode',
            'fakultas_unit_pengajaran' => 'Fak. Unit Pengajaran',
            'kode_organisasi' => 'Kode Org.',
            'program_studi' => 'Program Studi',
            'jenjang' => 'Jenjang',
            'program' => 'Program',
            'kategori_koefisien_program_studi' => 'kategori Koefisien',
            'nama' => 'Nama',
            'jenis' => 'Jenis',
            'kode_kelas' => 'Kode Kelas',
            'jenis_kelas' => 'Jenis Kelas',
            'last_updated_by' => 'Last Updated By',
            'last_updated_time' => 'Last Updated Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getperiode0()
    {
        return $this->hasOne(Periode::className(), ['nama' => 'periode']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKategoriKoefisienProgramStudi()
    {
        return $this->hasOne(ProgramStudi::className(), ['kategori_koefisien' => 'kategori_koefisien_program_studi', 'nama' => 'program_studi']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPengajars()
    {
        return $this->hasMany(Pengajar::className(), ['periode_mata_kuliah' => 'periode', 'program_studi_mata_kuliah' => 'program_studi', 'kategori_koefisien_program_studi_mata_kuliah' => 'kategori_koefisien_program_studi', 'nama_mata_kuliah' => 'nama', 'jenis_mata_kuliah' => 'jenis']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodeDosens()
    {
        return $this->hasMany(Dosen::className(), ['periode' => 'periode_dosen', 'departemen' => 'departemen_dosen', 'nip_nidn' => 'nip_nidn_dosen'])->viaTable('pengajar', ['periode_mata_kuliah' => 'periode', 'program_studi_mata_kuliah' => 'program_studi', 'kategori_koefisien_program_studi_mata_kuliah' => 'kategori_koefisien_program_studi', 'nama_mata_kuliah' => 'nama', 'jenis_mata_kuliah' => 'jenis']);
    }
}
