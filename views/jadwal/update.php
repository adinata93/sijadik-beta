<?php

use yii\helpers\Html;
use app\models\Dosen;

/* @var $this yii\web\View */
/* @var $model app\models\Jadwal */
$dos = Dosen::find()->where(['nip_nidn' => $model->nip_nidn_dosen_pengajar,'periode' => $model->periode_dosen_pengajar])->one();

$this->title = 'Update Jadwal: ' . $dos->nama_dosen;
$this->params['breadcrumbs'][] = ['label' => 'Jadwal', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $dos->nama_dosen, 'url' => ['view', 'id' => $model->id, 'periode_dosen_pengajar' => $model->periode_dosen_pengajar, 'departemen_dosen_pengajar' => $model->departemen_dosen_pengajar, 'nip_nidn_dosen_pengajar' => $model->nip_nidn_dosen_pengajar, 'periode_mata_kuliah_pengajar' => $model->periode_mata_kuliah_pengajar, 'program_studi_mata_kuliah_pengajar' => $model->program_studi_mata_kuliah_pengajar, 'kategori_koefisien_program_studi_mata_kuliah_pengajar' => $model->kategori_koefisien_program_studi_mata_kuliah_pengajar, 'nama_mata_kuliah_pengajar' => $model->nama_mata_kuliah_pengajar, 'jenis_mata_kuliah_pengajar' => $model->jenis_mata_kuliah_pengajar]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="jadwal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
