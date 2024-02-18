<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item"><a href="#">Simpanan</a></li>
			<li class="breadcrumb-item active" aria-current="page">Laporan</li>
		</ol>
	</nav>
</div>
<div class="main-wrapper">
	<?php if ($dataContentSimpananAnggota){?>
		<div class="col-lg-12">
			<div class="card">
				<div class="alert alert-info no-m">
					Simpanan Keanggotaan.
				</div>
			</div>
		</div>
		<br/>
		<div class="row">
		<?php foreach ($dataContentSimpananAnggota as $simpananAnggota) {?>
				<div class="col-lg-4 col-md-12">
					<div class="card savings-card">
						<div class="card-body">
							<h5 class="card-title"><?= $simpananAnggota->simpanan_name?></h5>
							<div class="savings-stats">
								<h5 class="js-currencyFormatter" data-price="<?= $simpananAnggota->totalSimpanan?>"></h5>
							</div>
						</div>
						<div class="card-footer">
							<div class="float-right">
								<a href="<?= base_url('admin/simpanan/laporan/show/').$simpananAnggota->simpanan_name ?>" class="btn btn-info">Detail</a>
							</div>
						</div>
					</div>
				</div>
		<?php }}?>
		</div>
	<?php if ($dataContentSimpananFunding){?>
		<div class="col-lg-12">
			<div class="card">
				<div class="alert alert-info no-m">
					Simpanan Funding.
				</div>
			</div>
		</div>
		<br/>
		<div class="row">
		<?php foreach ($dataContentSimpananFunding as $simpananFunding) {?>
			<div class="col-lg-4 col-md-12">
				<div class="card savings-card">
					<div class="card-body">
						<h5 class="card-title"><?= $simpananFunding->simpanan_name?></h5>
						<div class="savings-stats">
							<h5 class="js-currencyFormatter" data-price="<?= $simpananFunding->totalSimpanan?>"></h5>
						</div>
					</div>
					<div class="card-footer">
						<div class="float-right">
							<a href="<?= base_url('admin/simpanan/laporan/show/').$simpananFunding->simpanan_name ?>" class="btn btn-info">Detail</a>
						</div>
					</div>
				</div>
			</div>
		<?php }}?>
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
				<div class="card-header">
					<h5>Semua Simpanan Anggota</h5>
				</div>
				<div class="card-body">
					<table class="default-table display" style="width: 100%;">
						<thead>
						<tr>
							<th>No.</th>
							<th>Tanggal Pembayaran</th>
							<th>Anggota</th>
							<th>Deskripsi</th>
							<th>Jumlah</th>
						</tr>
						</thead>
						<tbody>
						<?php $no=1; foreach($dataAllSimpanan as $simpanan){?>
							<tr>
								<td><?= $no++; ?></td>
								<td><?= date('d M Y ', strtotime($simpanan->created_at)) ?></td>
								<td>
									<span class="text-info"><?= $simpanan->member_referal_code ?></span><br/>
									<?= $simpanan->member_full_name ?>
								</td>
								<td><?= $simpanan->simpanan_name ?></td>
								<td class="js-currencyFormatter" data-price="<?=  $simpanan->simpanan_member_ammount?>"></td>
							</tr>
						<?php }?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
