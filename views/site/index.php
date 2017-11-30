<?php

/* @var $this yii\web\View */

$this->title = 'Welcome';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mata-kuliah-index">

	<h1 class="text-center bg-success">
        <img src="dist/img/boxed-bg.png" height="80" width="0">
		<img src="dist/img/sijadik.png" height="64" width="64">
        <b>SIJADIK</b> FKG UI
    </h1>
 	<h5 class="text-center bg-success ">
        <img src="dist/img/boxed-bg.png" height="32" width="0">
    	Simulasi Imbal Jasa dan Jadwal Akademik Dosen Fakultas Kedokteran Gigi Universitas Indonesia
	</h5>
    <hr>
    <div class="body-content">
        <div class="row">
            <div class="col-lg-4">
                <h3 class="text-center">Pengguna</h3>
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
	                      <td>Email</td>
	                      <td><?= Yii::$app->user->identity->email ?></td>
	                    </tr>
	                  </table>
	                </div><!-- /.box-body -->
	            </div><!-- /.box -->
            </div>
            <div class="col-lg-8">
                <h3 class="text-center">Berita/Pengumuman</h3>
				<ul style="list-style-type:circle">
        			<!-- MAINTENANCE CODE -->
					<li>
					  	Ketua Program Studi (KPS) hanya dapat mengolah data yang berkaitan dengan Departemen dan Program Studi sesuai wewenangnya sebagai KPS.
	                </li>
					<li>
						Periode pengisian SIJADIK berakhir 1 bulan sejak semester berjalan. Setiap KPS harap menyelesaikan pengisian sebelum periode pengisian berakhir. 
					</li>
        			<!-- /.MAINTENANCE CODE -->
				</ul>  
            </div>
        </div>
    </div>
</div>
