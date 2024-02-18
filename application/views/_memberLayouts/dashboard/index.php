<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
		</ol>
	</nav>
<!--	<div class="page-options">-->
<!--		<a href="#" class="btn btn-secondary">Settings</a>-->
<!--		<a href="#" class="btn btn-primary">Upgrade</a>-->
<!--	</div>-->
</div>
<div class="main-wrapper">
	<?php if (!current($dataMember)->member_is_kyc || !current($dataMember)->member_is_activated){?>
	<div class="col-lg-12">
		<div class="alert alert-warning" role="alert">
			<h6 class="alert-heading">Selamat Datang!</h6>
			<p>Terima kasih telah mendaftar sebagai anggota Lentera Digital Indonesia. Untuk dapat menggunakan pinjaman, melakukan tabungan, mendaftarkan anggota, dan transaksi lainnya. Mohon segera melakukan langkah-langkah berikut</p>
			<hr>
			<ul>
				<?php if (!current($dataMember)->member_is_kyc){?>
				<li>
					<a href="<?= base_url('member-profile/edit/'); ?><?= $this->session->userdata('member_id');?>">verifikasi data</a>
				</li>
				<?php }?>
				<?php if (!current($dataMember)->member_is_simwa){?>
				<li>
					<a href="<?= base_url('member/simpanan/index')?>">Membayar Simpanan Wajib</a>
				</li>
				<?php }?>
				<?php if (!current($dataMember)->member_is_simpo){?>
				<li>
					<a href="<?= base_url('member/simpanan/index')?>">Membayar Simpanan Pokok</a>
				</li>
				<?php }?>
			</ul>
		</div>
	</div>
	<?php }?>
	<div class="col-lg-12">
		<div class="card">
			<div class="alert alert-info no-m">
				Kode Referal.
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="input-group">
			<input type="text" class="form-control js-targeted_copy_value" id="refferal_code"
				   value="<?= base_url('member/register?r=')?><?= $dataMember[0]->member_referal_code?>"
				   placeholder="refferal_code" aria-describedby="refferal_code" disabled readonly>
			<div class="input-group-append">
				<span class="input-group-text btn btn-outline-primary js-copy_btn" id="inputGroupPrepend">
					<div class="col-sm-1">
						<i class="fas fa-copy"></i>
					</div>
				</span>
			</div>
		</div>
	</div>
	<br/>
	<div class="row">
		<div class="col-lg-4 col-md-12">
			<div class="card savings-card">
				<div class="card-body">
					<h5 class="card-title">Saldo Dompet</h5>
					<div class="savings-stats">
						<h5 class="js-currencyFormatter" data-price="<?= $dataBalance ?>"></h5>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-12">
			<div class="card savings-card">
				<div class="card-body">
					<h5 class="card-title">L-Poin</h5>
					<div class="savings-stats">
						<h5 class="js-currencyFormatter" data-price="<?= $dataLpoint?>"></h5>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-12">
			<div class="card savings-card">
				<div class="card-body">
					<h5 class="card-title">Imbal Jasa</h5>
					<div class="savings-stats">
						<h5 class="js-currencyFormatter" data-price="<?= $dataImbalJasa ?>"></h5>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-4 col-md-12">
			<div class="card savings-card">
				<div class="card-body">
					<h5 class="card-title">Tabungan</h5>
					<div class="savings-stats">
						<h5 class="js-currencyFormatter" data-price="<?= $dataTabungan ?>"></h5>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-12">
			<div class="card savings-card">
				<div class="card-body">
					<h5 class="card-title">Simpanan</h5>
					<div class="savings-stats">
						<h5 class="js-currencyFormatter" data-price="<?= $dataSimpanan ?>"></h5>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-12">
			<div class="card savings-card">
				<div class="card-body">
					<h5 class="card-title">Hutang</h5>
					<div class="savings-stats">
						<h5 class="js-currencyFormatter" data-price="<?= $dataHutang ?>"></h5>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-12">
		<div class="card">
			<div class="alert alert-success no-m">
				Mutasi Dompet.
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<div class="card">
				<div class="card-body">
					<form action="<?=base_url('member/dashboard'); ?>" method="POST">
						<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="inputEmail4">From:</label>
								<input type="date" class="form-control" id="inputEmail4" name="start_date" value="<?= set_value('start_date')?>">
							</div>
							<div class="form-group col-md-6">
								<label for="inputPassword4">To:</label>
								<input type="date" class="form-control" id="inputPassword4" name="end_date" value="<?= set_value('end_date')?>">
							</div>
						</div>
						<div class="float-right">
							<button type="submit" class="btn btn-primary">Filter</button>
						</div>
					</form>
					<br/><br/><br/>
					<table class="default-table display" style="width: 100%;">
						<thead>
						<tr>
							<th>No.</th>
							<th>Tanggal</th>
							<th>No. Ref</th>
							<th>Deskripsi</th>
							<th>Debit</th>
							<th>Credit</th>
							<th>Saldo</th>
						</tr>
						</thead>
						<tbody>
						<?php $no=1; foreach($dataMutasi as $mutasi){?>
							<tr>
								<td><?= $no++; ?></td>
								<td><?= date('Y M d', strtotime($mutasi->created_at)) ?></td>
								<td><?= $mutasi->transaction_code ?></td>
								<td><?= $mutasi->description ?></td>
								<td class="js-currencyFormatter" data-price="<?= $mutasi->debit ?>"></td>
								<td class="js-currencyFormatter" data-price="<?= $mutasi->credit ?>"></td>
								<td class="js-currencyFormatter" data-price="<?= $mutasi->balance ?>"></td>
							</tr>
						<?php }?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
