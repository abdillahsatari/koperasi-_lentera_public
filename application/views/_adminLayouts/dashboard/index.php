<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
		</ol>
	</nav>
</div>
<div class="main-wrapper">
	<div class="col-lg-12">
		<div class="card">
			<div class="alert alert-info no-m">
				Kode Referal.
			</div>
		</div>
	</div>
	<br/>
	<div class="row">
		<div class="col-lg-4 col-md-12">
			<div class="card savings-card">
				<div class="card-body">
					<h5 class="card-title">Anggota Aktif</h5>
					<div class="savings-stats">
						<h5><?= $dataCountActiveMember?></h5>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-12">
			<div class="card savings-card">
				<div class="card-body">
					<h5 class="card-title">Anggota Register</h5>
					<div class="savings-stats">
						<h5><?= $dataCountRegisteredMember ?></h5>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-12">
			<div class="card savings-card">
				<div class="card-body">
					<h5 class="card-title">Total Simpanan</h5>
					<div class="savings-stats">
						<h5 class="js-currencyFormatter" data-price="<?= $dataSimpanan ?>"></h5>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-4 col-md-12">
			<div class="card savings-card">
				<div class="card-body">
					<h5 class="card-title">Total Tabungan</h5>
					<div class="savings-stats">
						<h5 class="js-currencyFormatter" data-price="<?= $dataTabungan ?>"></h5>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-12">
			<div class="card savings-card">
				<div class="card-body">
					<h5 class="card-title">Alokasi Pinjaman</h5>
					<div class="savings-stats">
						<h5 class="js-currencyFormatter" data-price="<?= $dataPinjaman ?>"></h5>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-12">
			<div class="card savings-card">
				<div class="card-body">
					<h5 class="card-title">Total Deposito</h5>
					<div class="savings-stats">
						<h5 class="js-currencyFormatter" data-price="<?= $dataDeposito ?>"></h5>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-12">
		<div class="card">
			<div class="alert alert-success no-m">
				Chart Perbandingan
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<div class="card">
				<div class="card-body">
					<div class="col-lg">
						<h4 class="text-center">Simpanan - Pinjaman Tahun <?= date("Y", strtotime(date('Y'))); ?></h4>
						<div id="js_admin-dashboard-chart" data-url="<?= base_url('admin/AdminAjax/generateDataChart')?>">
							<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken?>">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card">
				<div class="card-header">
					<h5>Semua Aktifitas Anggota <br/>
				</div>
				<div class="card-body">
					<?= $this->session->flashdata('message');?>
					<table class="default-table display" style="width: 100%;">
						<thead>
						<tr>
							<th>No.</th>
							<th>Tanggal</th>
							<th>ID</th>
							<th>Pengguna</th>
							<th>Deskripsi</th>
						</tr>
						</thead>
						<tbody>
						<?php $no = 1; foreach($dataActivity as $activity){?>
							<tr>
								<td>
									<?= $no++;?>
								</td>
								<td><?= date('Y M d h:m:s', strtotime($activity->created_at))?></td>
								<td><?= $activity->admin_id ?: $activity->member_id ?></td>
								<td><?= $activity->user_type ?></td>
								<td><?= $activity->report_description ?></td>
							</tr>
						<?php }?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
