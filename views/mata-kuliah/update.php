<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MataKuliah */

$this->title = 'Update Mata Kuliah: ' . $model->nama;
$this->params['breadcrumbs'][] = ['label' => 'Mata Kuliah', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nama, 'url' => ['view', 'periode' => $model->periode, 'program_studi' => $model->program_studi, 'kategori_koefisien_program_studi' => $model->kategori_koefisien_program_studi, 'nama' => $model->nama, 'jenis' => $model->jenis]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mata-kuliah-update">

    <h1 style="margin-top: 0"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
