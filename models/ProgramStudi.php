<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "program_studi".
 *
 * @property string $kategori_koefisien
 * @property string $nama
 * @property string $last_updated_by
 * @property string $last_updated_time
 *
 * @property MataKuliah[] $mataKuliahs
 * @property KategoriKoefisien $kategoriKoefisien
 */
class ProgramStudi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'program_studi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kategori_koefisien', 'nama', 'last_updated_by', 'last_updated_time'], 'required'],
            [['last_updated_time'], 'safe'],
            [['kategori_koefisien', 'nama', 'last_updated_by'], 'string', 'max' => 100],
            [['kategori_koefisien'], 'exist', 'skipOnError' => true, 'targetClass' => KategoriKoefisien::className(), 'targetAttribute' => ['kategori_koefisien' => 'nama']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kategori_koefisien' => 'Kategori Koefisien',
            'nama' => 'Nama',
            'last_updated_by' => 'Last Updated By',
            'last_updated_time' => 'Last Updated Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMataKuliahs()
    {
        return $this->hasMany(MataKuliah::className(), ['kategori_koefisien_program_studi' => 'kategori_koefisien', 'program_studi' => 'nama']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKategoriKoefisien()
    {
        return $this->hasOne(KategoriKoefisien::className(), ['nama' => 'kategori_koefisien']);
    }
}
