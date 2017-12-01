<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\KategoriKoefisien */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kategori-koefisien-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nama')->widget(Select2::classname(), [
        'data' => [ 'D3' => 'D3', 'S1 Reguler' => 'S1 Reguler', 'S1 Ekstensi / Khusus / Sejenis' => 'S1 Ekstensi / Khusus / Sejenis', 'S1 Internasional' => 'S1 Internasional', 'Profesi' => 'Profesi', 'S2 Reguler' => 'S2 Reguler', 'Spesialis' => 'Spesialis', 'S2 Khusus' => 'S2 Khusus', 'S3' => 'S3', ],
        'language' => 'en',
        'options' => ['placeholder' => 'Search kategori koefisien'],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'theme' => Select2::THEME_DEFAULT,
    ])?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
