<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Dosen;

/* @var $this yii\web\View */
/* @var $model app\models\Jadwal */
$dos = Dosen::find()->where(['nip_nidn' => $model->nip_nidn_dosen_pengajar,'periode' => $model->periode_dosen_pengajar])->one();

$this->title = $dos->nama_dosen;
$this->params['breadcrumbs'][] = ['label' => 'Jadwal', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-view">

    <h1 style="margin-top: 0"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id, 'periode_dosen_pengajar' => $model->periode_dosen_pengajar, 'departemen_dosen_pengajar' => $model->departemen_dosen_pengajar, 'nip_nidn_dosen_pengajar' => $model->nip_nidn_dosen_pengajar, 'periode_mata_kuliah_pengajar' => $model->periode_mata_kuliah_pengajar, 'program_studi_mata_kuliah_pengajar' => $model->program_studi_mata_kuliah_pengajar, 'kategori_koefisien_program_studi_mata_kuliah_pengajar' => $model->kategori_koefisien_program_studi_mata_kuliah_pengajar, 'nama_mata_kuliah_pengajar' => $model->nama_mata_kuliah_pengajar, 'jenis_mata_kuliah_pengajar' => $model->jenis_mata_kuliah_pengajar], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id, 'periode_dosen_pengajar' => $model->periode_dosen_pengajar, 'departemen_dosen_pengajar' => $model->departemen_dosen_pengajar, 'nip_nidn_dosen_pengajar' => $model->nip_nidn_dosen_pengajar, 'periode_mata_kuliah_pengajar' => $model->periode_mata_kuliah_pengajar, 'program_studi_mata_kuliah_pengajar' => $model->program_studi_mata_kuliah_pengajar, 'kategori_koefisien_program_studi_mata_kuliah_pengajar' => $model->kategori_koefisien_program_studi_mata_kuliah_pengajar, 'nama_mata_kuliah_pengajar' => $model->nama_mata_kuliah_pengajar, 'jenis_mata_kuliah_pengajar' => $model->jenis_mata_kuliah_pengajar], [
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
            // 'id',
            'periode_dosen_pengajar',
            'departemen_dosen_pengajar',
            'periodeDosenPengajar.periodeDosen.nama_dosen',
            // 'periode_mata_kuliah_pengajar',
            'program_studi_mata_kuliah_pengajar',
            'kategori_koefisien_program_studi_mata_kuliah_pengajar',
            'nama_mata_kuliah_pengajar',
            'jenis_mata_kuliah_pengajar',
            'jadwal_start',
            'jadwal_end',
            'last_updated_by',
            'last_updated_time',
        ],
    ]) ?>

</div>
