<?php

namespace app\controllers;

use Yii;
use app\models\Dosen;
use app\models\DosenSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use app\models\Periode;

/**
 * DosenController implements the CRUD actions for Dosen model.
 */
class DosenController extends Controller
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
                    [ // after login
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
                    [ // after login
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback'=>function(){
                            return (
                                (Yii::$app->user->identity->role!='Tenaga Kependidikan SDM') 
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
     * Lists all Dosen models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DosenSearch();
        # MAINTENANCE CODE
        $searchModel->periode = '2016/2017 - 1';
        # /.MAINTENANCE CODE
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Dosen model.
     * @param string $periode
     * @param string $departemen
     * @param string $nip_nidn
     * @return mixed
     */
    public function actionView($periode, $departemen, $nip_nidn)
    {
        return $this->render('view', [
            'model' => $this->findModel($periode, $departemen, $nip_nidn),
        ]);
    }

    /**
     * Creates a new Dosen model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Dosen();

        if ($model->load(Yii::$app->request->post())) {
            $dos = Dosen::find()
                ->where(
                    ['and', 
                        ['periode' => $model->periode],
                        ['nip_nidn' => $model->nip_nidn]
                    ]
                )
            ->one();

            if ($dos != null) {
               Yii::$app->session->setFlash('danger', '<b>GAGAL CREATE</b> <br> Dosen dengan <i>NIP/NIDN: ' . $model->nip_nidn . '</i> pada <i>Periode: ' . $model->periode . '</i> sudah ada');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>NIP/NIDN</i> dan <i>Periode</i> yang berbeda jika ingin menambahkan dosen baru');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

            # AccessControl Detail
            if ((Yii::$app->user->identity->role=='Manajer Pendidikan') ||
                (Yii::$app->user->identity->role=='Manajer Umum') ||
                (Yii::$app->user->identity->role=='Tenaga Kependidikan SDM') ||
                (Yii::$app->user->identity->role=='KPS S1') || 
                (Yii::$app->user->identity->role=='KPS S3')) {
                true;
            } else if (Yii::$app->user->identity->role=='KPS Profesi') {
                if (($model->departemen == 'Oral Biologi') ||
                    ($model->departemen == 'Ilmu Material Kedokteran Gigi') ||
                    ($model->departemen == 'Ilmu Kesehatan Gigi Masyarakat Pencegahan')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS S2 IKGD') {
                if (($model->departemen != 'Oral Biologi') &&
                    ($model->departemen != 'Ilmu Material Kedokteran Gigi')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS S2 IKGK') {
                if ($model->departemen != 'Ilmu Kesehatan Gigi Masyarakat Pencegahan') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp BM') {
                if ($model->departemen != 'Ilmu Bedah Mulut') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp IPM') {
                if ($model->departemen != 'Ilmu Penyakit Mulut') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');   
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp Orto') {
                if ($model->departemen != 'Ortodonsia') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');   
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp IKGA') {
                if ($model->departemen != 'Ilmu Kedokteran Gigi Anak') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp Perio') {
                if ($model->departemen != 'Periodonsia') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp Konservasi') {
                if ($model->departemen != 'Ilmu Koservasi Gigi') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp Prosto') {
                if ($model->departemen != 'Prostodonsia') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            }

            $model->total_sks_kum = 0;
            $model->total_bkd_fte = 0;
            $model->last_updated_by = Yii::$app->user->identity->nama;
            $model->last_updated_time = date('Y-m-d H:i:s');
            $model->save();
            return $this->redirect(['view', 'periode' => $model->periode, 'departemen' => $model->departemen, 'nip_nidn' => $model->nip_nidn]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Dosen model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $periode
     * @param string $departemen
     * @param string $nip_nidn
     * @return mixed
     */
    public function actionUpdate($periode, $departemen, $nip_nidn)
    {
        $model = $this->findModel($periode, $departemen, $nip_nidn);

        $per = Periode::find()
            ->where(
                ['nama' => $periode]
            )
        ->one();

        if ($per->is_locked == 'Locked') {
            throw new ForbiddenHttpException('You are not allowed to perform this action.');
        }

        # AccessControl Detail
        if ((Yii::$app->user->identity->role=='Manajer Pendidikan') ||
            (Yii::$app->user->identity->role=='Manajer Umum') ||
            (Yii::$app->user->identity->role=='Tenaga Kependidikan SDM') ||
            (Yii::$app->user->identity->role=='KPS S1') || 
            (Yii::$app->user->identity->role=='KPS S3')) {
            true;
        } else if (Yii::$app->user->identity->role=='KPS Profesi') {
            if (($departemen == 'Oral Biologi') ||
                ($departemen == 'Ilmu Material Kedokteran Gigi') ||
                ($departemen == 'Ilmu Kesehatan Gigi Masyarakat Pencegahan')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS S2 IKGD') {
            if (($departemen != 'Oral Biologi') &&
                ($departemen != 'Ilmu Material Kedokteran Gigi')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS S2 IKGK') {
            if ($departemen != 'Ilmu Kesehatan Gigi Masyarakat Pencegahan') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp BM') {
            if ($departemen != 'Ilmu Bedah Mulut') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp IPM') {
            if ($departemen != 'Ilmu Penyakit Mulut') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');   
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp Orto') {
            if ($departemen != 'Ortodonsia') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');   
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp IKGA') {
            if ($departemen != 'Ilmu Kedokteran Gigi Anak') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp Perio') {
            if ($departemen != 'Periodonsia') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp Konservasi') {
            if ($departemen != 'Ilmu Koservasi Gigi') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp Prosto') {
            if ($departemen != 'Prostodonsia') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            $dos = Dosen::find()
                ->where(
                    ['and',
                        ['or',
                            ['!=', 'periode', $periode],
                            ['!=', 'nip_nidn', $nip_nidn]
                        ],
                        ['and', 
                            ['periode' => $model->periode],
                            ['nip_nidn' => $model->nip_nidn]
                        ]
                    ]
                )
            ->one();

            if ($dos != null) {
               Yii::$app->session->setFlash('danger', '<b>GAGAL UPDATE</b> <br> Dosen dengan <i>NIP/NIDN: ' . $model->nip_nidn . '</i> pada <i>Periode: ' . $model->periode . '</i> sudah ada');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>NIP/NIDN</i> dan <i>Periode</i> yang berbeda jika ingin melakukan update dosen');
                return $this->render('update', [
                    'model' => $model,
                ]);
            }

            # AccessControl Detail
            if ((Yii::$app->user->identity->role=='Manajer Pendidikan') ||
                (Yii::$app->user->identity->role=='Manajer Umum') ||
                (Yii::$app->user->identity->role=='Tenaga Kependidikan SDM') ||
                (Yii::$app->user->identity->role=='KPS S1') || 
                (Yii::$app->user->identity->role=='KPS S3')) {
                true;
            } else if (Yii::$app->user->identity->role=='KPS Profesi') {
                if (($model->departemen == 'Oral Biologi') ||
                    ($model->departemen == 'Ilmu Material Kedokteran Gigi') ||
                    ($model->departemen == 'Ilmu Kesehatan Gigi Masyarakat Pencegahan')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS S2 IKGD') {
                if (($model->departemen != 'Oral Biologi') &&
                    ($model->departemen != 'Ilmu Material Kedokteran Gigi')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else {
                if ($model->departemen != $departemen) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            }

            $model->last_updated_by = Yii::$app->user->identity->nama;
            $model->last_updated_time = date('Y-m-d H:i:s');
            $model->save();
            return $this->redirect(['view', 'periode' => $model->periode, 'departemen' => $model->departemen, 'nip_nidn' => $model->nip_nidn]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Dosen model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $periode
     * @param string $departemen
     * @param string $nip_nidn
     * @return mixed
     */
    public function actionDelete($periode, $departemen, $nip_nidn)
    {
        $per = Periode::find()
            ->where(
                ['nama' => $periode]
            )
        ->one();

        if ($per->is_locked == 'Locked') {
            throw new ForbiddenHttpException('You are not allowed to perform this action.');
        }
        
        $this->findModel($periode, $departemen, $nip_nidn)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Dosen model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $periode
     * @param string $departemen
     * @param string $nip_nidn
     * @return Dosen the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($periode, $departemen, $nip_nidn)
    {
        if (($model = Dosen::findOne(['periode' => $periode, 'departemen' => $departemen, 'nip_nidn' => $nip_nidn])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
