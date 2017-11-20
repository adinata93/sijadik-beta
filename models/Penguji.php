<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "penguji".
 *
 * @property string $periode_dosen
 * @property string $departemen_dosen
 * @property string $nip_nidn_dosen
 * @property string $jenis_ujian
 * @property string $peran
 * @property integer $jumlah_mahasiswa
 * @property string $sks_kum
 * @property string $last_updated_by
 * @property string $last_updated_time
 *
 * @property Dosen $periodeDosen
 */
class Penguji extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'penguji';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['periode_dosen', 'departemen_dosen', 'nip_nidn_dosen', 'jenis_ujian', 'peran', 'jumlah_mahasiswa', 'last_updated_by', 'last_updated_time'], 'required'],
            [['jenis_ujian', 'peran'], 'string'],
            [['jumlah_mahasiswa'], 'integer'],
            [['sks_kum'], 'number'],
            [['last_updated_time'], 'safe'],
            [['periode_dosen'], 'string', 'max' => 13],
            [['departemen_dosen', 'last_updated_by'], 'string', 'max' => 100],
            [['nip_nidn_dosen'], 'string', 'max' => 18],
            [['periode_dosen', 'departemen_dosen', 'nip_nidn_dosen'], 'exist', 'skipOnError' => true, 'targetClass' => Dosen::className(), 'targetAttribute' => ['periode_dosen' => 'periode', 'departemen_dosen' => 'departemen', 'nip_nidn_dosen' => 'nip_nidn']],
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
            'jenis_ujian' => 'Ujian',
            'peran' => 'Peran',
            'jumlah_mahasiswa' => 'Jumlah Mhs',
            'sks_kum' => 'SKS KUM',
            'last_updated_by' => 'Last Updated By',
            'last_updated_time' => 'Last Updated Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodeDosen()
    {
        return $this->hasOne(Dosen::className(), ['periode' => 'periode_dosen', 'departemen' => 'departemen_dosen', 'nip_nidn' => 'nip_nidn_dosen']);
    }
}
