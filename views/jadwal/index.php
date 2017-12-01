<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Periode;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JadwalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jadwal';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-index">

    <h1 style="margin-top: 0"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Jadwal', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'panel'=>[
            'type'=>'success',
            'before' => false,
            'after' => false,
        ],
        'panelHeadingTemplate' => '{summary}{toggleData}',
        'toggleDataContainer' => ['class' => 'btn-group-xs text-right'],
        'summaryOptions' => ['class' => 'pull-left'],
        'toggleDataOptions' => [
            'all' => [
                'icon' => 'resize-full',
                'label' => 'Show All Data',
                'class' => 'btn btn-primary',
                'title' => ''
            ],
            'page' => [
                'icon' => 'resize-small',
                'label' => 'Show Data by Pages',
                'class' => 'btn btn-primary',
                'title' => ''
            ],
        ],
        'toolbar' => false,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'striped' => false,
        // 'pjax' => true,
        'hover' => true,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            // 'id',
            [
                'attribute'=>'periode_dosen_pengajar',
                'group'=>true,
                'filterType'=>GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(Periode::find()->all(),'nama','nama'),
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                    'options' => ['placeholder' => 'Select periode'],
                ],
            ],
            [
                'attribute'=>'departemen_dosen_pengajar',
                'group'=>true,
                'subGroupOf'=>1,
            ],
            [
                'attribute'=>'nip_nidn_dosen_pengajar',
                'value'=>'periodeDosenPengajar.periodeDosen.nama_dosen',
                'group'=>true,
                'subGroupOf'=>2,
            ],
            // 'periode_mata_kuliah_pengajar',
            // 'program_studi_mata_kuliah_pengajar',
            // 'kategori_koefisien_program_studi_mata_kuliah_pengajar',
            // 'jenis_mata_kuliah_pengajar',
            [
                'attribute'=>'nama_mata_kuliah_pengajar',
                'group'=>true,
                'subGroupOf'=>3,
            ],
            'jadwal_start',
            'jadwal_end',
            // 'last_updated_by',
            // 'last_updated_time',

            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]); ?>
</div>
