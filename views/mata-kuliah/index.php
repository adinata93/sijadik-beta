<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Periode;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MataKuliahSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mata Kuliah';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mata-kuliah-index">

    <h1 style="margin-top: 0"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Mata Kuliah', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'panel'=>[
            'type'=>'success',
            'footer'=>false,
            'beforeOptions'=>['class'=>'grid_panel_remove'],
            'afterOptions'=>['class'=>'grid_panel_remove'],
        ],
        'toolbar' => false,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'striped' => false,
        // 'pjax' => true,
        'hover' => true,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            [
                'attribute'=>'periode',
                'group'=>true,
                'filterType'=>GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(Periode::find()->all(),'nama','nama'),
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                    'options' => ['placeholder' => 'Select periode'],
                ],
            ],
            [
                'attribute'=>'fakultas_unit_pengajaran',
                // 'group'=>true,
            ],
            [
                'attribute'=>'kode_organisasi',
                // 'group'=>true,
            ],
            [
                'attribute'=>'program_studi',
                // 'group'=>true,
            ],
            [
                'attribute'=>'jenjang',
                // 'group'=>true,
            ],
            [
                'attribute'=>'program',
                // 'group'=>true,
            ],
            // 'kategori_koefisien_program_studi',
            [
                'attribute'=>'jenis',
                // 'group'=>true,
            ],
            'nama',
            'kode_kelas',
            // 'jenis_kelas',
            // 'last_updated_by',
            // 'last_updated_time',

            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]); ?>
</div>
