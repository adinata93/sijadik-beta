<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "periode".
 *
 * @property string $nama
 * @property string $is_locked
 * @property string $last_updated_by
 * @property string $last_updated_time
 *
 * @property Dosen[] $dosens
 * @property MataKuliah[] $mataKuliahs
 */
class Periode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'periode';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama', 'is_locked', 'last_updated_by', 'last_updated_time'], 'required'],
            [['is_locked'], 'string'],
            [['last_updated_time'], 'safe'],
            [['nama'], 'string', 'max' => 13],
            [['last_updated_by'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nama' => 'Nama',
            'is_locked' => 'Is Locked',
            'last_updated_by' => 'Last Updated By',
            'last_updated_time' => 'Last Updated Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDosens()
    {
        return $this->hasMany(Dosen::className(), ['periode' => 'nama']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMataKuliahs()
    {
        return $this->hasMany(MataKuliah::className(), ['periode' => 'nama']);
    }
}
