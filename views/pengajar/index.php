<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Periode;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PengajarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pengajar';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengajar-index">

    <h1 style="margin-top: 0"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Pengajar', ['create'], ['class' => 'btn btn-success']) ?>
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
        'showPageSummary' => true,
        'pageSummaryRowOptions' => ['class'=>'warning text-right text-danger', 'style'=>'font-weight:bold;'],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            [
                'attribute'=>'periode_dosen',
                'group'=>true,
                'filterType'=>GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(Periode::find()->all(),'nama','nama'),
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                    'options' => ['placeholder' => 'Select periode'],
                ],
            ],
            [
                'attribute'=>'departemen_dosen',
                'group'=>true,
                'subGroupOf'=>1,
                'groupFooter'=>function ($model, $key, $index, $widget) { // Closure method
                    return [
                        'mergeColumns'=>[[2,5]], // columns to merge in summary
                        'content'=>[             // content to show in each summary cell
                            2=>'Summary (' . $model->departemen_dosen . ')',
                            7=>GridView::F_SUM,
                            8=>GridView::F_SUM,
                            9=>GridView::F_SUM,
                        ],
                        'contentFormats'=>[      // content reformatting for each summary cell
                            7=>['format'=>'number', 'decimals'=>3],
                            8=>['format'=>'number', 'decimals'=>3],
                            9=>['format'=>'number', 'decimals'=>3],
                        ],
                        'contentOptions'=>[      // content html attributes for each summary cell
                            2=>['style'=>'font-variant:small-caps',],
                            7=>['style'=>'text-align:right'],
                            8=>['style'=>'text-align:right'],
                            9=>['style'=>'text-align:right'],
                        ],
                        'options'=>['class'=>'info','style'=>'font-weight:bold;'] // html attributes for group summary row
                    ];
                },
            ],
            [
                'attribute'=>'nip_nidn_dosen',
                'value'=>'periodeDosen.nama_dosen',
                'group'=>true,
                'subGroupOf'=>2,
            ],
            // 'peiode_mata_kuliah',
            // 'program_studi_mata_kuliah',
            // 'kategori_koefisien_program_studi_mata_kuliah',
            [
                'attribute'=>'jenis_mata_kuliah',
                'group'=>true,
                'subGroupOf'=>3,
            ],
            [
                'attribute'=>'nama_mata_kuliah',
                'pageSummary' => 'Page Summary',
            ],
            'skenario',
            [
                'attribute'=>'sks_ekivalen',
                'pageSummary' => true,
            ],
            [
                'attribute'=>'sks_kum',
                'pageSummary' => true,
            ],
            [
                'attribute'=>'bkd_fte',
                'pageSummary' => true,
            ],
            // 'last_updated_by',
            // 'last_updated_time',

            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]); ?>
</div>
