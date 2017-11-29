<?php

namespace app\controllers;

use Yii;
use app\models\Jadwal;
use app\models\JadwalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Dosen;
use app\models\Periode;
use app\models\MataKuliah;
use app\models\Pengajar;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;
use yii\helpers\Url;

/**
 * JadwalController implements the CRUD actions for Jadwal model.
 */
class JadwalController extends Controller
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
     * Lists all Jadwal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new JadwalSearch();
        # MAINTENANCE CODE
        $searchModel->periode_dosen_pengajar = '2016/2017 - 1';
        # /.MAINTENANCE CODE
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Jadwal model.
     * @param integer $id
     * @param string $periode_dosen_pengajar
     * @param string $departemen_dosen_pengajar
     * @param string $nip_nidn_dosen_pengajar
     * @param string $periode_mata_kuliah_pengajar
     * @param string $program_studi_mata_kuliah_pengajar
     * @param string $kategori_koefisien_program_studi_mata_kuliah_pengajar
     * @param string $nama_mata_kuliah_pengajar
     * @param string $jenis_mata_kuliah_pengajar
     * @return mixed
     */
    public function actionView($id, $periode_dosen_pengajar, $departemen_dosen_pengajar, $nip_nidn_dosen_pengajar, $periode_mata_kuliah_pengajar, $program_studi_mata_kuliah_pengajar, $kategori_koefisien_program_studi_mata_kuliah_pengajar, $nama_mata_kuliah_pengajar, $jenis_mata_kuliah_pengajar)
    {
        return $this->render('view', [
            'model' => $this->findModel($id, $periode_dosen_pengajar, $departemen_dosen_pengajar, $nip_nidn_dosen_pengajar, $periode_mata_kuliah_pengajar, $program_studi_mata_kuliah_pengajar, $kategori_koefisien_program_studi_mata_kuliah_pengajar, $nama_mata_kuliah_pengajar, $jenis_mata_kuliah_pengajar),
        ]);
    }

    /**
     * Creates a new Jadwal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Jadwal();

        if ($model->load(Yii::$app->request->post())) {
            $dos = Dosen::find()->where([
                'nip_nidn' => $model->nip_nidn_dosen_pengajar,
                'periode' => $model->periode_dosen_pengajar,
            ])->one();

            if ($dos == null) {
                Yii::$app->session->setFlash('danger', '<b>GAGAL CREATE</b> <br> Input <i>Nama Dosen</i> yang diberikan tidak terdaftar pada <i>Periode: ' . $model->periode_dosen_pengajar . '</i>');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>Nama Dosen</i> yang sudah terdaftar pada <i>Periode</i> tertentu jika ingin menambahkan jadwal baru');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

            $matkul = MataKuliah::find()->where(['nama' => $model->nama_mata_kuliah_pengajar])->one();

            # AccessControl Detail
            if ((Yii::$app->user->identity->role=='Manajer Pendidikan') ||
                (Yii::$app->user->identity->role=='Manajer Umum') ||
                (Yii::$app->user->identity->role=='Tenaga Kependidikan SDM')) {
                true;
            } else if (Yii::$app->user->identity->role=='KPS S1') {
                if ($matkul->program_studi != 'S1 Pendidikan Dokter Gigi') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS S3') {
                if ($matkul->program_studi != 'S3 Ilmu Kedokteran Gigi') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS Profesi') {
                if ((($dos->departemen == 'Oral Biologi') ||
                    ($dos->departemen == 'Ilmu Material Kedokteran Gigi') ||
                    ($dos->departemen == 'Ilmu Kesehatan Gigi Masyarakat Pencegahan'))
                    && 
                    ($matkul->program_studi != 'Profesi Dokter Gigi')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS S2 IKGD') {
                if ((($dos->departemen != 'Oral Biologi') && 
                    ($dos->departemen != 'Ilmu Material Kedokteran Gigi')) 
                    && 
                    ($matkul->program_studi != 'S2 IKGD')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS S2 IKGK') {
                if (($dos->departemen != 'Ilmu Kesehatan Gigi Masyarakat Pencegahan') && 
                    ($matkul->program_studi != 'S2 IKGK')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp BM') {
                if (($dos->departemen != 'Ilmu Bedah Mulut') && 
                    ($matkul->program_studi != 'Spesialis Bedah Mulut')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp IPM') {
                if (($dos->departemen != 'Ilmu Penyakit Mulut') && 
                    ($matkul->program_studi != 'Spesialis Ilmu Penyakit Mulut')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');   
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp Orto') {
                if (($dos->departemen != 'Ortodonsia') && 
                    ($matkul->program_studi != 'Spesialis Ortodonsia')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');   
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp IKGA') {
                if (($dos->departemen != 'Ilmu Kedokteran Gigi Anak') && 
                    ($matkul->program_studi != 'Spesialis IKGA')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp Perio') {
                if (($dos->departemen != 'Periodonsia') && 
                    ($matkul->program_studi != 'Spesialis Periodonsia')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp Konservasi') {
                if (($dos->departemen != 'Ilmu Koservasi Gigi') && 
                    ($matkul->program_studi != 'Spesialis Ilmu Konservasi Gigi')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp Prosto') {
                if (($dos->departemen != 'Prostodonsia') && 
                    ($matkul->program_studi != 'Spesialis Prostodonsia')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            }

            $jad = Jadwal::find()
                ->where(
                    ['and',
                        ['and',
                            ['periode_dosen_pengajar' => $model->periode_dosen_pengajar,],
                            ['nip_nidn_dosen_pengajar' => $model->nip_nidn_dosen_pengajar]
                        ],
                        ['or',
                            ['and', ['>=', 'jadwal_start', $model->jadwal_start], ['<=', 'jadwal_end', $model->jadwal_end]],
                            ['or',
                                ['and', ['<=', 'jadwal_start', $model->jadwal_start], ['>=', 'jadwal_end', $model->jadwal_start]],
                                ['and', ['<=', 'jadwal_start', $model->jadwal_end], ['>=', 'jadwal_end', $model->jadwal_end]]
                            ]
                        ]
                    ]
                )
            ->all();

            if ($model->jadwal_start > $model->jadwal_end) {
                Yii::$app->session->setFlash('danger', "<b>GAGAL CREATE</b> <br> input <i>Jadwal Start</i> dan <i>Jadwal End</i> tidak valid");
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>Jadwal Start</i> yang lebih kecil dari input <i>Jadwal End</i> untuk menambahkan jadwal baru');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

            if ($jad != null) {
                $print = "<b>GAGAL CREATE</b>";
                foreach ($jad as $key) {
                    $print = $print . "<br>Terdapat konflik jadwal dengan Mata Kuliah <i>" . $matkul->nama . "</i> pada <i>" . $key->jadwal_start . "</i> sampai dengan <i>" . $key->jadwal_end . "</i>";
                }
                Yii::$app->session->setFlash('danger', $print);
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>Jadwal Start</i> dan <i>Jadwal End</i> yang berbeda dan tidak memiliki konflik untuk menambahkan jadwal baru');
                return $this->render('create', [
                    'model' => $model,
                ]);
            } 

            $pen = Pengajar::find()
                ->where(
                    ['and',
                        ['periode_dosen' => $model->periode_dosen_pengajar],
                        ['and',
                            ['nip_nidn_dosen' => $model->nip_nidn_dosen_pengajar],
                            ['nama_mata_kuliah' => $model->nama_mata_kuliah_pengajar]
                        ]
                    ]
                )
            ->one();

            if ($pen == null) {
                Yii::$app->session->setFlash('danger', '<b>GAGAL CREATE</b> <br> Input <i>Nama Dosen</i> yang diberikan tidak terdaftar sebagai pengajar <i>Mata Kuliah: ' . $matkul->nama . '</i>');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>Nama Dosen</i> yang sudah terdaftar pada <i>Mata Kuliah</i> tertentu jika ingin menambahkan jadwal baru');
                return $this->render('create', [
                    'model' => $model,
                ]);                
            }

            $model->periode_mata_kuliah_pengajar = $model->periode_dosen_pengajar;
            $model->departemen_dosen_pengajar = $dos->departemen;
            $model->program_studi_mata_kuliah_pengajar = $matkul->program_studi;
            $model->kategori_koefisien_program_studi_mata_kuliah_pengajar = $matkul->kategori_koefisien_program_studi;
            $model->jenis_mata_kuliah_pengajar = $matkul->jenis;
            $model->last_updated_by = Yii::$app->user->identity->nama;
            $model->last_updated_time = date('Y-m-d H:i:s');

            $model->save();
            return $this->redirect(['view', 'id' => $model->id, 'periode_dosen_pengajar' => $model->periode_dosen_pengajar, 'departemen_dosen_pengajar' => $model->departemen_dosen_pengajar, 'nip_nidn_dosen_pengajar' => $model->nip_nidn_dosen_pengajar, 'periode_mata_kuliah_pengajar' => $model->periode_mata_kuliah_pengajar, 'program_studi_mata_kuliah_pengajar' => $model->program_studi_mata_kuliah_pengajar, 'kategori_koefisien_program_studi_mata_kuliah_pengajar' => $model->kategori_koefisien_program_studi_mata_kuliah_pengajar, 'nama_mata_kuliah_pengajar' => $model->nama_mata_kuliah_pengajar, 'jenis_mata_kuliah_pengajar' => $model->jenis_mata_kuliah_pengajar]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Jadwal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string $periode_dosen_pengajar
     * @param string $departemen_dosen_pengajar
     * @param string $nip_nidn_dosen_pengajar
     * @param string $periode_mata_kuliah_pengajar
     * @param string $program_studi_mata_kuliah_pengajar
     * @param string $kategori_koefisien_program_studi_mata_kuliah_pengajar
     * @param string $nama_mata_kuliah_pengajar
     * @param string $jenis_mata_kuliah_pengajar
     * @return mixed
     */
    public function actionUpdate($id, $periode_dosen_pengajar, $departemen_dosen_pengajar, $nip_nidn_dosen_pengajar, $periode_mata_kuliah_pengajar, $program_studi_mata_kuliah_pengajar, $kategori_koefisien_program_studi_mata_kuliah_pengajar, $nama_mata_kuliah_pengajar, $jenis_mata_kuliah_pengajar)
    {
        $model = $this->findModel($id, $periode_dosen_pengajar, $departemen_dosen_pengajar, $nip_nidn_dosen_pengajar, $periode_mata_kuliah_pengajar, $program_studi_mata_kuliah_pengajar, $kategori_koefisien_program_studi_mata_kuliah_pengajar, $nama_mata_kuliah_pengajar, $jenis_mata_kuliah_pengajar);

        $per = Periode::find()
            ->where(
                ['nama' => $periode_dosen_pengajar]
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
            if ($program_studi_mata_kuliah_pengajar != 'S1 Pendidikan Dokter Gigi') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS S3') {
            if ($program_studi_mata_kuliah_pengajar != 'S3 Ilmu Kedokteran Gigi') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS Profesi') {
            if ((($departemen_dosen_pengajar == 'Oral Biologi') ||
                ($departemen_dosen_pengajar == 'Ilmu Material Kedokteran Gigi') ||
                ($departemen_dosen_pengajar == 'Ilmu Kesehatan Gigi Masyarakat Pencegahan'))
                && 
                ($program_studi_mata_kuliah != 'Profesi Dokter Gigi')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS S2 IKGD') {
            if ((($departemen_dosen_pengajar != 'Oral Biologi') && 
                ($departemen_dosen_pengajar != 'Ilmu Material Kedokteran Gigi')) 
                && 
                ($program_studi_mata_kuliah_pengajar != 'S2 IKGD')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS S2 IKGK') {
            if (($departemen_dosen_pengajar != 'Ilmu Kesehatan Gigi Masyarakat Pencegahan') && 
                ($program_studi_mata_kuliah_pengajar != 'S2 IKGK')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp BM') {
            if (($departemen_dosen_pengajar != 'Ilmu Bedah Mulut') && 
                ($program_studi_mata_kuliah_pengajar != 'Spesialis Bedah Mulut')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp IPM') {
            if (($departemen_dosen_pengajar != 'Ilmu Penyakit Mulut') && 
                ($program_studi_mata_kuliah_pengajar != 'Spesialis Ilmu Penyakit Mulut')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');   
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp Orto') {
            if (($departemen_dosen_pengajar != 'Ortodonsia') && 
                ($program_studi_mata_kuliah_pengajar != 'Spesialis Ortodonsia')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');   
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp IKGA') {
            if (($departemen_dosen_pengajar != 'Ilmu Kedokteran Gigi Anak') && 
                ($program_studi_mata_kuliah_pengajar != 'Spesialis IKGA')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp Perio') {
            if (($departemen_dosen_pengajar != 'Periodonsia') && 
                ($program_studi_mata_kuliah_pengajar != 'Spesialis Periodonsia')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp Konservasi') {
            if (($departemen_dosen_pengajar != 'Ilmu Koservasi Gigi') && 
                ($program_studi_mata_kuliah_pengajar != 'Spesialis Ilmu Konservasi Gigi')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp Prosto') {
            if (($departemen_dosen_pengajar != 'Prostodonsia') && 
                ($program_studi_mata_kuliah_pengajar != 'Spesialis Prostodonsia')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            $dos = Dosen::find()->where([
                'nip_nidn' => $model->nip_nidn_dosen_pengajar,
                'periode' => $model->periode_dosen_pengajar,
            ])->one();

            if ($dos == null) {
                Yii::$app->session->setFlash('danger', '<b>GAGAL UPDATE</b> <br> Input <i>Nama Dosen</i> yang diberikan tidak terdaftar pada <i>Periode: ' . $model->periode_dosen_pengajar . '</i>');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>Nama Dosen</i> yang sudah terdaftar pada <i>Periode</i> tertentu jika ingin melakukan update jadwal');
                return $this->redirect(Url::current());
            }

            $matkul = MataKuliah::find()->where(['nama' => $model->nama_mata_kuliah_pengajar])->one();

            # AccessControl Detail
            if ((Yii::$app->user->identity->role=='Manajer Pendidikan') ||
                (Yii::$app->user->identity->role=='Manajer Umum') ||
                (Yii::$app->user->identity->role=='Tenaga Kependidikan SDM')) {
                true;
            } else if (Yii::$app->user->identity->role=='KPS S1') {
                if ($matkul->program_studi != 'S1 Pendidikan Dokter Gigi') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS S3') {
                if ($matkul->program_studi != 'S3 Ilmu Kedokteran Gigi') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS Profesi') {
                if ((($dos->departemen == 'Oral Biologi') ||
                    ($dos->departemen == 'Ilmu Material Kedokteran Gigi') ||
                    ($dos->departemen == 'Ilmu Kesehatan Gigi Masyarakat Pencegahan'))
                    && 
                    ($matkul->program_studi != 'Profesi Dokter Gigi')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS S2 IKGD') {
                if ((($dos->departemen != 'Oral Biologi') && 
                    ($dos->departemen != 'Ilmu Material Kedokteran Gigi')) 
                    && 
                    ($matkul->program_studi != 'S2 IKGD')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS S2 IKGK') {
                if (($dos->departemen != 'Ilmu Kesehatan Gigi Masyarakat Pencegahan') && 
                    ($matkul->program_studi != 'S2 IKGK')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp BM') {
                if (($dos->departemen != 'Ilmu Bedah Mulut') && 
                    ($matkul->program_studi != 'Spesialis Bedah Mulut')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp IPM') {
                if (($dos->departemen != 'Ilmu Penyakit Mulut') && 
                    ($matkul->program_studi != 'Spesialis Ilmu Penyakit Mulut')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');   
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp Orto') {
                if (($dos->departemen != 'Ortodonsia') && 
                    ($matkul->program_studi != 'Spesialis Ortodonsia')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');   
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp IKGA') {
                if (($dos->departemen != 'Ilmu Kedokteran Gigi Anak') && 
                    ($matkul->program_studi != 'Spesialis IKGA')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp Perio') {
                if (($dos->departemen != 'Periodonsia') && 
                    ($matkul->program_studi != 'Spesialis Periodonsia')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp Konservasi') {
                if (($dos->departemen != 'Ilmu Koservasi Gigi') && 
                    ($matkul->program_studi != 'Spesialis Ilmu Konservasi Gigi')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp Prosto') {
                if (($dos->departemen != 'Prostodonsia') && 
                    ($matkul->program_studi != 'Spesialis Prostodonsia')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            }

            $jad = Jadwal::find()
                ->where(
                    ['and',
                        ['and',
                            ['!=', 'id', $model->id],
                            ['and',
                                ['periode_dosen_pengajar' => $model->periode_dosen_pengajar,],
                                ['nip_nidn_dosen_pengajar' => $model->nip_nidn_dosen_pengajar]
                            ]
                        ],
                        ['or',
                            ['and', ['>=', 'jadwal_start', $model->jadwal_start], ['<=', 'jadwal_end', $model->jadwal_end]],
                            ['or',
                                ['and', ['<=', 'jadwal_start', $model->jadwal_start], ['>=', 'jadwal_end', $model->jadwal_start]],
                                ['and', ['<=', 'jadwal_start', $model->jadwal_end], ['>=', 'jadwal_end', $model->jadwal_end]]
                            ]
                        ]
                    ]
                )
            ->all();

            if ($model->jadwal_start > $model->jadwal_end) {
                Yii::$app->session->setFlash('danger', "<b>GAGAL UPDATE</b> <br> input <i>Jadwal Start</i> dan <i>Jadwal End</i> tidak valid");
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>Jadwal Start</i> yang lebih kecil dari input <i>Jadwal End</i> untuk melakukan update jadwal');
                return $this->redirect(Url::current());
            }

            if ($jad != null) {
                $print = "<b>GAGAL UPDATE</b>";
                foreach ($jad as $key) {
                    $print = $print . "<br>Terdapat konflik jadwal dengan Mata Kuliah <i>" . $matkul->nama . "</i> pada <i>" . $key->jadwal_start . "</i> sampai dengan <i>" . $key->jadwal_end . "</i>";
                }
                Yii::$app->session->setFlash('danger', $print);
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>Jadwal Start</i> dan <i>Jadwal End</i> yang berbeda dan tidak memiliki konflik untuk melakukan update jadwal');
                return $this->redirect(Url::current());
            } 

            $pen = Pengajar::find()
                ->where(
                    ['and',
                        ['periode_dosen' => $model->periode_dosen_pengajar],
                        ['and',
                            ['nip_nidn_dosen' => $model->nip_nidn_dosen_pengajar],
                            ['nama_mata_kuliah' => $model->nama_mata_kuliah_pengajar]
                        ]
                    ]
                )
            ->one();

            if ($pen == null) {
                Yii::$app->session->setFlash('danger', '<b>GAGAL UPDATE</b> <br> Input <i>Nama Dosen</i> yang diberikan tidak terdaftar sebagai pengajar <i>Mata Kuliah: ' . $matkul->nama . '</i>');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>Nama Dosen</i> yang sudah terdaftar pada <i>Mata Kuliah</i> tertentu jika ingin melakukan update jadwal');
                return $this->redirect(Url::current());
            }

            $model->periode_mata_kuliah_pengajar = $model->periode_dosen_pengajar;
            $model->departemen_dosen_pengajar = $dos->departemen;
            $model->program_studi_mata_kuliah_pengajar = $matkul->program_studi;
            $model->kategori_koefisien_program_studi_mata_kuliah_pengajar = $matkul->kategori_koefisien_program_studi;
            $model->jenis_mata_kuliah_pengajar = $matkul->jenis;
            $model->last_updated_by = Yii::$app->user->identity->nama;
            $model->last_updated_time = date('Y-m-d H:i:s');

            $model->save();
            return $this->redirect(['view', 'id' => $model->id, 'periode_dosen_pengajar' => $model->periode_dosen_pengajar, 'departemen_dosen_pengajar' => $model->departemen_dosen_pengajar, 'nip_nidn_dosen_pengajar' => $model->nip_nidn_dosen_pengajar, 'periode_mata_kuliah_pengajar' => $model->periode_mata_kuliah_pengajar, 'program_studi_mata_kuliah_pengajar' => $model->program_studi_mata_kuliah_pengajar, 'kategori_koefisien_program_studi_mata_kuliah_pengajar' => $model->kategori_koefisien_program_studi_mata_kuliah_pengajar, 'nama_mata_kuliah_pengajar' => $model->nama_mata_kuliah_pengajar, 'jenis_mata_kuliah_pengajar' => $model->jenis_mata_kuliah_pengajar]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Jadwal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param string $periode_dosen_pengajar
     * @param string $departemen_dosen_pengajar
     * @param string $nip_nidn_dosen_pengajar
     * @param string $periode_mata_kuliah_pengajar
     * @param string $program_studi_mata_kuliah_pengajar
     * @param string $kategori_koefisien_program_studi_mata_kuliah_pengajar
     * @param string $nama_mata_kuliah_pengajar
     * @param string $jenis_mata_kuliah_pengajar
     * @return mixed
     */
    public function actionDelete($id, $periode_dosen_pengajar, $departemen_dosen_pengajar, $nip_nidn_dosen_pengajar, $periode_mata_kuliah_pengajar, $program_studi_mata_kuliah_pengajar, $kategori_koefisien_program_studi_mata_kuliah_pengajar, $nama_mata_kuliah_pengajar, $jenis_mata_kuliah_pengajar)
    {
        $per = Periode::find()
            ->where(
                ['nama' => $periode_dosen_pengajar]
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
            if ($program_studi_mata_kuliah_pengajar != 'S1 Pendidikan Dokter Gigi') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS S3') {
            if ($program_studi_mata_kuliah_pengajar != 'S3 Ilmu Kedokteran Gigi') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS Profesi') {
            if ((($departemen_dosen_pengajar == 'Oral Biologi') ||
                ($departemen_dosen_pengajar == 'Ilmu Material Kedokteran Gigi') ||
                ($departemen_dosen_pengajar == 'Ilmu Kesehatan Gigi Masyarakat Pencegahan'))
                && 
                ($program_studi_mata_kuliah != 'Profesi Dokter Gigi')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS S2 IKGD') {
            if ((($departemen_dosen_pengajar != 'Oral Biologi') && 
                ($departemen_dosen_pengajar != 'Ilmu Material Kedokteran Gigi')) 
                && 
                ($program_studi_mata_kuliah_pengajar != 'S2 IKGD')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS S2 IKGK') {
            if (($departemen_dosen_pengajar != 'Ilmu Kesehatan Gigi Masyarakat Pencegahan') && 
                ($program_studi_mata_kuliah_pengajar != 'S2 IKGK')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp BM') {
            if (($departemen_dosen_pengajar != 'Ilmu Bedah Mulut') && 
                ($program_studi_mata_kuliah_pengajar != 'Spesialis Bedah Mulut')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp IPM') {
            if (($departemen_dosen_pengajar != 'Ilmu Penyakit Mulut') && 
                ($program_studi_mata_kuliah_pengajar != 'Spesialis Ilmu Penyakit Mulut')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');   
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp Orto') {
            if (($departemen_dosen_pengajar != 'Ortodonsia') && 
                ($program_studi_mata_kuliah_pengajar != 'Spesialis Ortodonsia')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');   
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp IKGA') {
            if (($departemen_dosen_pengajar != 'Ilmu Kedokteran Gigi Anak') && 
                ($program_studi_mata_kuliah_pengajar != 'Spesialis IKGA')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp Perio') {
            if (($departemen_dosen_pengajar != 'Periodonsia') && 
                ($program_studi_mata_kuliah_pengajar != 'Spesialis Periodonsia')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp Konservasi') {
            if (($departemen_dosen_pengajar != 'Ilmu Koservasi Gigi') && 
                ($program_studi_mata_kuliah_pengajar != 'Spesialis Ilmu Konservasi Gigi')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp Prosto') {
            if (($departemen_dosen_pengajar != 'Prostodonsia') && 
                ($program_studi_mata_kuliah_pengajar != 'Spesialis Prostodonsia')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
            }
        }
        
        $this->findModel($id, $periode_dosen_pengajar, $departemen_dosen_pengajar, $nip_nidn_dosen_pengajar, $periode_mata_kuliah_pengajar, $program_studi_mata_kuliah_pengajar, $kategori_koefisien_program_studi_mata_kuliah_pengajar, $nama_mata_kuliah_pengajar, $jenis_mata_kuliah_pengajar)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Jadwal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $periode_dosen_pengajar
     * @param string $departemen_dosen_pengajar
     * @param string $nip_nidn_dosen_pengajar
     * @param string $periode_mata_kuliah_pengajar
     * @param string $program_studi_mata_kuliah_pengajar
     * @param string $kategori_koefisien_program_studi_mata_kuliah_pengajar
     * @param string $nama_mata_kuliah_pengajar
     * @param string $jenis_mata_kuliah_pengajar
     * @return Jadwal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $periode_dosen_pengajar, $departemen_dosen_pengajar, $nip_nidn_dosen_pengajar, $periode_mata_kuliah_pengajar, $program_studi_mata_kuliah_pengajar, $kategori_koefisien_program_studi_mata_kuliah_pengajar, $nama_mata_kuliah_pengajar, $jenis_mata_kuliah_pengajar)
    {
        if (($model = Jadwal::findOne(['id' => $id, 'periode_dosen_pengajar' => $periode_dosen_pengajar, 'departemen_dosen_pengajar' => $departemen_dosen_pengajar, 'nip_nidn_dosen_pengajar' => $nip_nidn_dosen_pengajar, 'periode_mata_kuliah_pengajar' => $periode_mata_kuliah_pengajar, 'program_studi_mata_kuliah_pengajar' => $program_studi_mata_kuliah_pengajar, 'kategori_koefisien_program_studi_mata_kuliah_pengajar' => $kategori_koefisien_program_studi_mata_kuliah_pengajar, 'nama_mata_kuliah_pengajar' => $nama_mata_kuliah_pengajar, 'jenis_mata_kuliah_pengajar' => $jenis_mata_kuliah_pengajar])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
