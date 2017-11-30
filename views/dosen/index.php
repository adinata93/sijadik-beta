<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Periode;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DosenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dosen';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dosen-index">

    <h1 style="margin-top: 0"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dosen', ['create'], ['class' => 'btn btn-success']) ?>
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
                'attribute'=>'departemen',
                'subGroupOf'=>1,
                'group'=>true,
                'groupFooter'=>function ($model, $key, $index, $widget) { // Closure method
                    return [
                        'mergeColumns'=>[[2,4]], // columns to merge in summary
                        'content'=>[             // content to show in each summary cell
                            2=>'Summary (' . $model->departemen . ')',
                            5=>GridView::F_SUM,
                            6=>GridView::F_SUM,
                        ],
                        'contentFormats'=>[      // content reformatting for each summary cell
                            5=>['format'=>'number', 'decimals'=>3],
                            6=>['format'=>'number', 'decimals'=>3],
                        ],
                        'contentOptions'=>[      // content html attributes for each summary cell
                            2=>['style'=>'font-variant:small-caps',],
                            5=>['style'=>'text-align:right'],
                            6=>['style'=>'text-align:right'],
                        ],
                        'options'=>['class'=>'info','style'=>'font-weight:bold;'] // html attributes for group summary row
                    ];
                },
            ],
            'nama_dosen',
            [
                'attribute'=>'nip_nidn',
                'pageSummary' => 'Page Summary',
            ],
            [
                'attribute'=>'total_sks_kum',
                'pageSummary' => true,
            ],
            [
                'attribute'=>'total_bkd_fte',
                'pageSummary' => true,
            ],
            // 'last_updated_by',
            // 'last_updated_time',

            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]); ?>
</div>
