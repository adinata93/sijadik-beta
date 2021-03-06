<?php

namespace app\controllers;

use Yii;
use app\models\Departemen;
use app\models\DepartemenSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * DepartemenController implements the CRUD actions for Departemen model.
 */
class DepartemenController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'login',
                        ],
                        'allow' => true,
                    ],
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback'=>function(){
                            return (
                                (Yii::$app->user->identity->role=='Tenaga Kependidikan SDM') 
                            );
                        }
                    ],
                    [
                        'actions' => [
                            'index',
                            'view',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback'=>function(){
                            return (
                                (Yii::$app->user->identity->role=='Manajer Pendidikan') ||
                                (Yii::$app->user->identity->role=='Manajer Umum') ||
                                (Yii::$app->user->identity->role=='KPS Profesi') ||
                                (Yii::$app->user->identity->role=='KPS S1') ||
                                (Yii::$app->user->identity->role=='KPS S2 IKGD') ||
                                (Yii::$app->user->identity->role=='KPS S2 IKGK') ||
                                (Yii::$app->user->identity->role=='KPS S3') ||
                                (Yii::$app->user->identity->role=='KPS Sp BM') ||
                                (Yii::$app->user->identity->role=='KPS Sp IKGA') ||
                                (Yii::$app->user->identity->role=='KPS Sp IPM') ||
                                (Yii::$app->user->identity->role=='KPS Sp Konservasi') ||
                                (Yii::$app->user->identity->role=='KPS Sp Orto') ||
                                (Yii::$app->user->identity->role=='KPS Sp Perio') ||
                                (Yii::$app->user->identity->role=='KPS Sp Prosto')
                            );
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Departemen models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DepartemenSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Departemen model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Departemen model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Departemen();

        if ($model->load(Yii::$app->request->post())) {
            $dep = Departemen::find()
                ->where(
                    ['nama' => $model->nama]
                )
            ->one();

            if ($dep != null) {
                Yii::$app->session->setFlash('danger', '<b>GAGAL CREATE</b> <br>Departemen dengan <i> Nama: ' . $dep->nama . '</i> sudah ada');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>Nama</i> yang berbeda untuk menambahkan departemen baru');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

            $model->last_updated_by = Yii::$app->user->identity->nama;
            $model->last_updated_time = date('Y-m-d H:i:s');
            $model->save();
            return $this->redirect(['view', 'id' => $model->nama]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Departemen model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $dep = Departemen::find()
                ->where(
                    ['and',
                        ['!=', 'nama', $id],
                        ['nama' => $model->nama]
                    ]
                )
            ->one();

            if ($dep != null) {
                Yii::$app->session->setFlash('danger', '<b>GAGAL UPDATE</b> <br>Departemen dengan <i> Nama: ' . $dep->nama . '</i> sudah ada');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>Nama</i> yang berbeda untuk melakukan update departemen');
                return $this->render('update', [
                    'model' => $model,
                ]);
            }

            $model->last_updated_by = Yii::$app->user->identity->nama;
            $model->last_updated_time = date('Y-m-d H:i:s');
            $model->save();
            return $this->redirect(['view', 'id' => $model->nama]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Departemen model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Departemen model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Departemen the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Departemen::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
