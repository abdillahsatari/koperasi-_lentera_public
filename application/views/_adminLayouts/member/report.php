<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item"><a href="#">Keanggotaan</a></li>
			<li class="breadcrumb-item active" aria-current="page">
				Semua Transaksi Anggota
			</li>
		</ol>
	</nav>
</div>

<div class="main-wrapper">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card">
				<div class="card-header">
					<h5>Semua Transaksi Anggota</h5>
				</div>
				<div class="card-body">
					<?= $this->session->flashdata('message');?>
					<table class="default-table display" style="width: 100%;">
						<thead>
						<tr>
							<th>No.</th>
							<th>Tanggal</th>
							<th>Anggota</th>
							<th>No. Ref</th>
							<th>Debit</th>
							<th>Credit</th>
							<th>Saldo</th>
							<th>Deskripsi</th>
						</tr>
						</thead>
						<tbody>
						<?php $no=1; foreach($dataTransactionLog as $log){?>
							<tr>
								<td><?= $no++; ?></td>
								<td><?= date('Y M d', strtotime($log->created_at)) ?></td>
								<td>
									<strong>
										<span class="text-info"><?= $log->member_referal_code ?></span>
									</strong>
									<br/>
									<?= $log->member_full_name ?>
								</td>
								<td><?= $log->transaction_code ?></td>
								<td class="js-currencyFormatter" data-price="<?= $log->debit ?>"></td>
								<td class="js-currencyFormatter" data-price="<?= $log->credit ?>"></td>
								<td class="js-currencyFormatter" data-price="<?= $log->balance ?>"></td>
								<td><?= $log->description ?></td>
							</tr>
						<?php }?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
