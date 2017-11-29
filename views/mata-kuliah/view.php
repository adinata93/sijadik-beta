<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MataKuliah */

$this->title = $model->nama;
$this->params['breadcrumbs'][] = ['label' => 'Mata Kuliah', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mata-kuliah-view">

    <h1 style="margin-top: 0"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'periode' => $model->periode, 'program_studi' => $model->program_studi, 'kategori_koefisien_program_studi' => $model->kategori_koefisien_program_studi, 'nama' => $model->nama, 'jenis' => $model->jenis], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'periode' => $model->periode, 'program_studi' => $model->program_studi, 'kategori_koefisien_program_studi' => $model->kategori_koefisien_program_studi, 'nama' => $model->nama, 'jenis' => $model->jenis], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'periode',
            'fakultas_unit_pengajaran',
            'kode_organisasi',
            'program_studi',
            'jenjang',
            'program',
            'kategori_koefisien_program_studi',
            'nama',
            'jenis',
            'kode_kelas',
            'jenis_kelas',
            'last_updated_by',
            'last_updated_time',
        ],
    ]) ?>

</div>
