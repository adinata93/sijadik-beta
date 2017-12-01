<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Dosen;

/* @var $this yii\web\View */
/* @var $model app\models\Pembimbing */
    
$dos = Dosen::find()->where(['nip_nidn' => $model->nip_nidn_dosen,'periode' => $model->periode_dosen,])->one();

$this->title = $dos->nama_dosen;
$this->params['breadcrumbs'][] = ['label' => 'Pembimbing', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pembimbing-view">

    <h1 style="margin-top: 0"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'periode_dosen' => $model->periode_dosen, 'departemen_dosen' => $model->departemen_dosen, 'nip_nidn_dosen' => $model->nip_nidn_dosen, 'jenis_bimbingan' => $model->jenis_bimbingan], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'periode_dosen' => $model->periode_dosen, 'departemen_dosen' => $model->departemen_dosen, 'nip_nidn_dosen' => $model->nip_nidn_dosen, 'jenis_bimbingan' => $model->jenis_bimbingan], [
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
            'periodeDosen.nama_dosen',
            'jenis_bimbingan',
            'jumlah_mahasiswa',
            'sks_kum',
            'bkd_fte',
            'last_updated_by',
            'last_updated_time',
        ],
    ]) ?>

</div>
