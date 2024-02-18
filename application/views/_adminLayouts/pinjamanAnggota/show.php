<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item"><a href="#">Pinjaman</a></li>
			<li class="breadcrumb-item active" aria-current="page">
				Detail Pinjaman
			</li>
		</ol>
	</nav>
</div>
<div class="main-wrapper">
	<?= $this->session->flashdata("message")?>
	<div class="row">
		<div class="col-lg-6 col-md-12">
			<div class="card savings-card">
				<div class="card-body">
					<h5 class="card-title">Pinjaman Dibayar</h5>
					<div class="savings-stats">
						<h5 class="js-currencyFormatter" data-price="<?= $dataPaidPinjaman ?>"></h5>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-md-12">
			<div class="card savings-card">
				<div class="card-body">
					<h5 class="card-title">Pinjaman Belum Dibayar</h5>
					<div class="savings-stats">
						<h5 class="js-currencyFormatter" data-price="<?= $dataUnpaidPinjaman ?>"></h5>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-12">
		<div class="card">
			<div class="alert alert-success no-m">
				Detail Pembayaran Pinjaman
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<div class="card">
				<div class="card-header">
					<h5>Detail Pembayaran Pinjaman</h5>
				</div>
				<div class="card-body">
					<table class="default-table display" style="width: 100%;">
						<thead>
						<tr>
							<th>No.</th>
							<th>Tanggal Jatuh Tempo</th>
							<th>No. Anggota</th>
							<th>No. Ref</th>
							<th>Jumlah Pinjaman</th>
							<th>Tenor</th>
							<th>Status</th>
						</tr>
						</thead>
						<tbody>
						<?php

						$no=1;
						$approvedStatus = "";
						foreach($dataMemberPinjamanDetailList as $pinjamanDetail){

							if ($pinjamanDetail->pinjaman_payment_status == MemberPinjamanStatus::PINJAMAN_PAID){
								$paymentStatus = "Telah Dibayar";
							} else {
								$paymentStatus = "Belum Dibayar";
							}

							?>
							<tr>
								<td><?= $no++; ?></td>
								<td><?= date('d M Y ', strtotime($pinjamanDetail->pinjaman_due_date)) ?></td>
								<td>
									<span class="text-info"><?= $pinjamanDetail->member_referal_code ?></span><br/>
									<?= $pinjamanDetail->member_full_name ?>
								</td>
								<td><?= $pinjamanDetail->pinjaman_transaction_code ?></td>
								<td class="js-currencyFormatter" data-price="<?= $pinjamanDetail->pinjaman_member_ammount ?>"></td>
								<td><?= substr($pinjamanDetail->pinjaman_member_tenor_type,0,-2)." ke - ".$pinjamanDetail->pinjaman_tenor_number ?></td>
								<td><?= $paymentStatus?></td>
							</tr>
						<?php }?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
