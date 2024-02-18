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
	<div class="row">
		<div class="col">
			<div class="card">
				<div class="card-header">
					<h5>Semua <?= $dataType?></h5>
				</div>
				<div class="card-body">
					<table class="default-table display" style="width: 100%;">
						<thead>
						<tr>
							<th>No.</th>
							<th>Tanggal Pembayaran</th>
							<th>Anggota</th>
							<th>Deskripsi</th>
							<th>Kode Transaksi</th>
							<th>Jumlah</th>
						</tr>
						</thead>
						<tbody>
						<?php $no=1; foreach($dataSimpananMemberType as $simpanan){?>
							<tr>
								<td><?= $no++; ?></td>
								<td><?= date('d M Y ', strtotime($simpanan->created_at)) ?></td>
								<td>
									<span class="text-info"><?= $simpanan->member_referal_code ?></span><br/>
									<?= $simpanan->member_full_name ?>
								</td>
								<td><?= $simpanan->simpanan_lentera_content_name ?></td>
								<td><?= $simpanan->simpanan_transaction_code ?></td>
								<td class="js-currencyFormatter" data-price="<?=  $simpanan->simpanan_member_ammount?>"></td>
							</tr>
						<?php }?>
						</tbody>
					</table>
				</div>
				<div class="card-footer">
					<a href="javascript:window.history.go(-1);" class="btn btn-light">Kembali</a>
				</div>
			</div>
		</div>
	</div>
</div>
