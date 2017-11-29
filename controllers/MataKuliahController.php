<?php

namespace app\controllers;

use Yii;
use app\models\MataKuliah;
use app\models\MataKuliahSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use app\models\Periode;
use app\models\ProgramStudi;

/**
 * MataKuliahController implements the CRUD actions for MataKuliah model.
 */
class MataKuliahController extends Controller
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
     * Lists all MataKuliah models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MataKuliahSearch();
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
     * Displays a single MataKuliah model.
     * @param string $periode
     * @param string $program_studi
     * @param string $kategori_koefisien_program_studi
     * @param string $nama
     * @param string $jenis
     * @return mixed
     */
    public function actionView($periode, $program_studi, $kategori_koefisien_program_studi, $nama, $jenis)
    {
        return $this->render('view', [
            'model' => $this->findModel($periode, $program_studi, $kategori_koefisien_program_studi, $nama, $jenis),
        ]);
    }

    /**
     * Creates a new MataKuliah model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MataKuliah();

        if ($model->load(Yii::$app->request->post())) {
            
            $matkul = MataKuliah::find()
                ->where(
                    ['and',
                        ['periode' => $model->periode],
                        ['and',
                            ['nama' => $model->nama],
                        ]
                    ]
                )
            ->one();

            if ($matkul != null) {
                Yii::$app->session->setFlash('danger', '<b>GAGAL CREATE</b> <br> Mata kuliah <i>' . $model->nama . '</i> pada periode <i>' . $model->periode . '</i> sudah ada');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>Periode</i> dan <i>Nama Mata Kuliah</i> yang berbeda jika ingin menambahkan mata kuliah baru');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

            # AccessControl Detail
            if ((Yii::$app->user->identity->role=='Manajer Pendidikan') ||
                (Yii::$app->user->identity->role=='Manajer Umum') ||
                (Yii::$app->user->identity->role=='Tenaga Kependidikan SDM')) {
                true;
            } else if (Yii::$app->user->identity->role=='KPS S1') {
                if ($model->program_studi != 'S1 Pendidikan Dokter Gigi') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS Profesi') {
                if ($model->program_studi != 'Profesi Dokter Gigi') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS S2 IKGD') {
                if ($model->program_studi != 'S2 IKGD') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS S2 IKGK') {
                if ($model->program_studi != 'S2 IKGK') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS S3') {
                if ($model->program_studi != 'S3 Ilmu Kedokteran Gigi') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp BM') {
                if ($model->program_studi != 'Spesialis Bedah Mulut') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp IKGA') {
                if ($model->program_studi != 'Spesialis IKGA') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp IPM') {
                if ($model->program_studi != 'Spesialis Ilmu Penyakit Mulut') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');   
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp Konservasi') {
                if ($model->program_studi != 'Spesialis Ilmu Konservasi Gigi') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp Orto') {
                if ($model->program_studi != 'Spesialis Ortodonsia') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');   
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp Perio') {
                if ($model->program_studi != 'Spesialis Periodonsia') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp Prosto') {
                if ($model->program_studi != 'Spesialis Prostodonsia') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            }

            $ps = ProgramStudi::find()
                ->where(
                    ['nama' => $model->program_studi]
                )
            ->one();
            
            $model->kategori_koefisien_program_studi = $ps->kategori_koefisien;
            $model->last_updated_by = Yii::$app->user->identity->nama;
            $model->last_updated_time = date('Y-m-d H:i:s');
            $model->save();
            return $this->redirect(['view', 'periode' => $model->periode, 'program_studi' => $model->program_studi, 'kategori_koefisien_program_studi' => $model->kategori_koefisien_program_studi, 'nama' => $model->nama, 'jenis' => $model->jenis]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MataKuliah model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $periode
     * @param string $program_studi
     * @param string $kategori_koefisien_program_studi
     * @param string $nama
     * @param string $jenis
     * @return mixed
     */
    public function actionUpdate($periode, $program_studi, $kategori_koefisien_program_studi, $nama, $jenis)
    {
        $model = $this->findModel($periode, $program_studi, $kategori_koefisien_program_studi, $nama, $jenis);

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
            (Yii::$app->user->identity->role=='Tenaga Kependidikan SDM')) {
            true;
        } else if (Yii::$app->user->identity->role=='KPS S1') {
            if ($rogram_studi != 'S1 Pendidikan Dokter Gigi') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
        }
        } else if (Yii::$app->user->identity->role=='KPS Profesi') {
            if ($program_studi != 'Profesi Dokter Gigi') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
        }
        } else if (Yii::$app->user->identity->role=='KPS S2 IKGD') {
            if ($program_studi != 'S2 IKGD') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
        }
        } else if (Yii::$app->user->identity->role=='KPS S2 IKGK') {
            if ($program_studi != 'S2 IKGK') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
        }
        } else if (Yii::$app->user->identity->role=='KPS S3') {
            if ($program_studi != 'S3 Ilmu Kedokteran Gigi') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
        }
        } else if (Yii::$app->user->identity->role=='KPS Sp BM') {
            if ($program_studi != 'Spesialis Bedah Mulut') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
        }
        } else if (Yii::$app->user->identity->role=='KPS Sp IKGA') {
            if ($program_studi != 'Spesialis IKGA') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
        }
        } else if (Yii::$app->user->identity->role=='KPS Sp IPM') {
            if ($program_studi != 'Spesialis Ilmu Penyakit Mulut') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');   
        }
        } else if (Yii::$app->user->identity->role=='KPS Sp Konservasi') {
            if ($program_studi != 'Spesialis Ilmu Konservasi Gigi') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
        }
        } else if (Yii::$app->user->identity->role=='KPS Sp Orto') {
            if ($program_studi != 'Spesialis Ortodonsia') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');   
        }
        } else if (Yii::$app->user->identity->role=='KPS Sp Perio') {
            if ($program_studi != 'Spesialis Periodonsia') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp Prosto') {
            if ($program_studi != 'Spesialis Prostodonsia') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            
            $matkul = MataKuliah::find()
                ->where(
                    ['and',
                        ['or',
                            ['!=', 'periode', $periode],
                            ['or',
                                ['!=', 'nama', $nama],
                                ['!=', 'jenis', $jenis]
                            ]
                        ],                    
                        ['and',
                            ['periode' => $model->periode],
                            ['and',
                                ['nama' => $model->nama],
                                ['jenis' => $model->jenis]
                            ]
                        ]
                    ]
                )
            ->one();

            if ($matkul != null) {
                Yii::$app->session->setFlash('danger', '<b>GAGAL UPDATE</b> <br> Mata kuliah <i>' . $model->nama . '</i> pada periode <i>' . $model->periode . '</i> sudah ada');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>Periode</i> dan <i>Nama Mata Kuliah</i> yang berbeda jika ingin melakukan update mata kuliah');
                return $this->render('update', [
                    'model' => $model,
                ]);
            }

            # AccessControl Detail
            if ($model->program_studi != $program_studi) {
                if ((Yii::$app->user->identity->role!='Manajer Pendidikan') ||
                    (Yii::$app->user->identity->role!='Manajer Umum') ||
                    (Yii::$app->user->identity->role!='Tenaga Kependidikan SDM')) {
                    true;
                } else {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            }
            

            $ps = ProgramStudi::find()
                ->where(
                    ['nama' => $model->program_studi]
                )
            ->one();
            
            $model->kategori_koefisien_program_studi = $ps->kategori_koefisien;
            $model->last_updated_by = Yii::$app->user->identity->nama;
            $model->last_updated_time = date('Y-m-d H:i:s');
            $model->save();
            return $this->redirect(['view', 'periode' => $model->periode, 'program_studi' => $model->program_studi, 'kategori_koefisien_program_studi' => $model->kategori_koefisien_program_studi, 'nama' => $model->nama, 'jenis' => $model->jenis]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MataKuliah model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $periode
     * @param string $program_studi
     * @param string $kategori_koefisien_program_studi
     * @param string $nama
     * @param string $jenis
     * @return mixed
     */
    public function actionDelete($periode, $program_studi, $kategori_koefisien_program_studi, $nama, $jenis)
    {
        $per = Periode::find()
            ->where(
                ['nama' => $periode]
            )
        ->one();

        if ($per->is_locked == 'Locked') {
            throw new ForbiddenHttpException('You are not allowed to perform this action.');
        }

        $this->findModel($periode, $program_studi, $kategori_koefisien_program_studi, $nama, $jenis)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MataKuliah model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $periode
     * @param string $program_studi
     * @param string $kategori_koefisien_program_studi
     * @param string $nama
     * @param string $jenis
     * @return MataKuliah the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($periode, $program_studi, $kategori_koefisien_program_studi, $nama, $jenis)
    {
        if (($model = MataKuliah::findOne(['periode' => $periode, 'program_studi' => $program_studi, 'kategori_koefisien_program_studi' => $kategori_koefisien_program_studi, 'nama' => $nama, 'jenis' => $jenis])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
