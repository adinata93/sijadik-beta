<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "departemen".
 *
 * @property string $nama
 * @property string $last_updated_by
 * @property string $last_updated_time
 *
 * @property Dosen[] $dosens
 */
class Departemen extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'departemen';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama', 'last_updated_by', 'last_updated_time'], 'required'],
            [['last_updated_time'], 'safe'],
            [['nama', 'last_updated_by'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nama' => 'Nama',
            'last_updated_by' => 'Last Updated By',
            'last_updated_time' => 'Last Updated Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDosens()
    {
        return $this->hasMany(Dosen::className(), ['departemen' => 'nama']);
    }
}
