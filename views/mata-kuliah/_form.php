<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Periode;
use app\models\KategoriKoefisien;
use app\models\ProgramStudi;

/* @var $this yii\web\View */
/* @var $model app\models\MataKuliah */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mata-kuliah-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'periode')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Periode::find()
            ->where(['!=', 'is_locked', 'Locked'])
        ->all(),'nama','nama'),
        'hideSearch' => true,
    ]) ?>

    <?= $form->field($model, 'fakultas_unit_pengajaran')->widget(Select2::classname(), [
        'data' => [ 'Kedokteran' => 'Kedokteran', 'Kedokteran Gigi' => 'Kedokteran Gigi', 'Matematika dan Ilmu Pengetehuan Alam' => 'Matematika dan Ilmu Pengetehuan Alam', 'Teknik' => 'Teknik', 'Hukum' => 'Hukum', 'Ekonomi' => 'Ekonomi', 'Ilmu Pengetahuan Budaya' => 'Ilmu Pengetahuan Budaya', 'Psikologi' => 'Psikologi', 'Ilmu Sosial dan Politik' => 'Ilmu Sosial dan Politik', 'Kesehatan Masyarakat' => 'Kesehatan Masyarakat', 'Program Pasca Sarjana' => 'Program Pasca Sarjana', 'Ilmu Komputer' => 'Ilmu Komputer', 'Ilmu Keperawatan' => 'Ilmu Keperawatan', 'Pusat Administrasi Universitas' => 'Pusat Administrasi Universitas', 'Program Vokasi' => 'Program Vokasi', 'Pertukaran Pelajar' => 'Pertukaran Pelajar', 'Farmasi' => 'Farmasi', 'FIA' => 'FIA', 'Ilmu Lingkungan' => 'Ilmu Lingkungan', 'Kajian Strategic dan Global' => 'Kajian Strategic dan Global', 'Program Mata Ajaran Universitas' => 'Program Mata Ajaran Universitas', 'Rumpun Ilmu Kesehatan' => 'Rumpun Ilmu Kesehatan', 'Psikologi Virtual' => 'Psikologi Virtual', ],
        'options' => ['placeholder' => 'Search program studi'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

    <?= $form->field($model, 'kode_organisasi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'program_studi')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(ProgramStudi::find()->all(),'nama','nama'),
        'options' => ['placeholder' => 'Search program studi'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

    <?= $form->field($model, 'jenjang')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'program')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jenis')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kode_kelas')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jenis_kelas')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
