<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Dosen;

/* @var $this yii\web\View */
/* @var $model app\models\Pengajar */
    
$dos = Dosen::find()->where(['nip_nidn' => $model->nip_nidn_dosen,'periode' => $model->periode_dosen,])->one();

$this->title = $dos->nama_dosen;
$this->params['breadcrumbs'][] = ['label' => 'Pengajar', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengajar-view">

    <h1 style="margin-top: 0"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'periode_dosen' => $model->periode_dosen, 'departemen_dosen' => $model->departemen_dosen, 'nip_nidn_dosen' => $model->nip_nidn_dosen, 'periode_mata_kuliah' => $model->periode_mata_kuliah, 'program_studi_mata_kuliah' => $model->program_studi_mata_kuliah, 'kategori_koefisien_program_studi_mata_kuliah' => $model->kategori_koefisien_program_studi_mata_kuliah, 'nama_mata_kuliah' => $model->nama_mata_kuliah, 'jenis_mata_kuliah' => $model->jenis_mata_kuliah], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'periode_dosen' => $model->periode_dosen, 'departemen_dosen' => $model->departemen_dosen, 'nip_nidn_dosen' => $model->nip_nidn_dosen, 'periode_mata_kuliah' => $model->periode_mata_kuliah, 'program_studi_mata_kuliah' => $model->program_studi_mata_kuliah, 'kategori_koefisien_program_studi_mata_kuliah' => $model->kategori_koefisien_program_studi_mata_kuliah, 'nama_mata_kuliah' => $model->nama_mata_kuliah, 'jenis_mata_kuliah' => $model->jenis_mata_kuliah], [
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
            'periode_dosen',
            'departemen_dosen',
            'nip_nidn_dosen',
            'periode_mata_kuliah',
            'kategori_koefisien_program_studi_mata_kuliah',
            'program_studi_mata_kuliah',
            'jenis_mata_kuliah',
            'nama_mata_kuliah',
            'skenario',
            'sks_ekivalen',
            'sks_kum',
            'bkd_fte',
            'last_updated_by',
            'last_updated_time',
        ],
    ]) ?>

</div>
