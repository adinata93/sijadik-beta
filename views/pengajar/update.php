<?php

use yii\helpers\Html;
use app\models\Dosen;

/* @var $this yii\web\View */
/* @var $model app\models\Pengajar */

$dos = Dosen::find()->where(['nip_nidn' => $model->nip_nidn_dosen,'periode' => $model->periode_dosen])->one();

$this->title = 'Update Pengajar: ' . $dos->nama_dosen;
$this->params['breadcrumbs'][] = ['label' => 'Pengajar', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $dos->nama_dosen, 'url' => ['view', 'periode_dosen' => $model->periode_dosen, 'departemen_dosen' => $model->departemen_dosen, 'nip_nidn_dosen' => $model->nip_nidn_dosen, 'periode_mata_kuliah' => $model->periode_mata_kuliah, 'program_studi_mata_kuliah' => $model->program_studi_mata_kuliah, 'kategori_koefisien_program_studi_mata_kuliah' => $model->kategori_koefisien_program_studi_mata_kuliah, 'nama_mata_kuliah' => $model->nama_mata_kuliah, 'jenis_mata_kuliah' => $model->jenis_mata_kuliah]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pengajar-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
