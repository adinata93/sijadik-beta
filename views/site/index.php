<?php

/* @var $this yii\web\View */

$this->title = 'Welcome';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mata-kuliah-index">

	<h1 class="text-center bg-success">
		<img src="dist/img/sijadik.png">
    	SIJADIK FKG UI
    </h1>
    <hr>
    <div class="body-content">
        <div class="row">
            <div class="col-lg-4">
                <h3>Pengguna</h3>

              <div class="box box-success">
                <div class="box-body no-padding">
                  <table class="table table-striped">
                    <tr>
                      <td>Nama</td>
                      <td><?= Yii::$app->user->identity->nama ?></td>
                    </tr>
                    <tr>
                      <td>NIP</td>
                      <td><?= Yii::$app->user->identity->nip ?></td>
                    </tr>
                    <tr>
                      <td>Role</td>
                      <td><?= Yii::$app->user->identity->role ?></td>
                    </tr>
                    <tr>
                      <td>Username</td>
                      <td><?= Yii::$app->user->identity->username ?></td>
                    </tr>
                    <tr>
                      <td>email</td>
                      <td><?= Yii::$app->user->identity->email ?></td>
                    </tr>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
            <div class="col-lg-8">
                <h3>Berita/Pengumuman</h3>
                <p>
                	Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.
                </p>
                <p>
                	Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.
                </p>
            </div>
        </div>
    </div>
    <hr>
 	<h5 class="text-center bg-success ">
    	Simulasi Imbal Jasa dan Jadwal Akademik Dosen Fakultas Kedokteran Gigi Universitas Indonesia
	</h5>
</div>
