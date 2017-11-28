<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Periode;
use app\models\Dosen;
use app\models\MataKuliah;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Jadwal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jadwal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'periode_dosen_pengajar')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Periode::find()
            ->where(['!=', 'is_locked', 'Locked'])
        ->all(),'nama','nama'),
        'hideSearch' => true,
        'theme' => Select2::THEME_DEFAULT,
    ]) ?>

    <?= $form->field($model, 'nip_nidn_dosen_pengajar')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Dosen::find()->all(),'nip_nidn','nama_dosen'),
        'options' => ['placeholder' => 'Search nama dosen'],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'theme' => Select2::THEME_DEFAULT,
    ]) ?>

    <?= $form->field($model, 'nama_mata_kuliah_pengajar')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(MataKuliah::find()->all(),'nama','nama'),
        'options' => ['placeholder' => 'Search nama mata kuliah'],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'theme' => Select2::THEME_DEFAULT,
    ]) ?>

    <?= $form->field($model, 'jadwal_start')->widget(DateTimePicker::classname(), [
        'options' => ['placeholder' => 'Tentukan tanggal, jam, dan menit'],
        'type' => DateTimePicker::TYPE_INPUT,
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd h:i:00',
            'autoclose'=>true,
        ],
    ]) ?>

    <?= $form->field($model, 'jadwal_end')->widget(DateTimePicker::classname(), [
        'options' => ['placeholder' => 'Tentukan tanggal, jam, dan menit'],
        'type' => DateTimePicker::TYPE_INPUT,
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd h:i:00',
            'autoclose'=>true,
        ],
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
