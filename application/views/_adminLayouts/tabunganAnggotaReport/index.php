<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item"><a href="#">Tabungan</a></li>
			<li class="breadcrumb-item"><a href="#">Laporan</a></li>
			<li class="breadcrumb-item active" aria-current="page"></li>
			<?=$pageType ?>
		</ol>
	</nav>
</div>

<div class="main-wrapper">
	<div class="row">
		<div class="col-lg-6 col-md-12">
			<div class="card savings-card">
				<div class="card-body">
					<h5 class="card-title">Total Tabungan</h5>
					<div class="savings-stats">
						<h5 class="js-currencyFormatter" data-price="<?= $dataTotalTabungan ?>"></h5>
					</div>
				</div>
				<div class="card-footer">
					<div class="float-right">
						<a href="<?= base_url('admin/tabungan/laporan?type=') . strtolower(MemberTabunganReportType::TABUNGAN_REPORT_MUTASI_TABUNGAN) ?>" class="btn btn-success">Detail</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-md-12">
			<div class="card savings-card">
				<div class="card-body">
					<h5 class="card-title">Total Imbal Jasa</h5>
					<div class="savings-stats">
						<h5 class="js-currencyFormatter" data-price="<?= $dataTotalImbalJasa ?>"></h5>
					</div>
				</div>
				<div class="card-footer">
					<div class="float-right">
						<a href="<?= base_url('admin/tabungan/laporan?type=') . strtolower(MemberTabunganReportType::TABUNGAN_REPORT_IMBAL_JASA) ?>" class="btn btn-success">Detail</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-12">
		<div class="card">
			<div class="alert alert-success no-m">
				<?= $pageType ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card">
				<div class="card-header">
					<h5>
						<?= $pageType ?>
					</h5>
				</div>
				<div class="card-body">
					<?= $this->session->flashdata('message')?>

					<?php if($pageType == MemberTabunganReportType::TABUNGAN_REPORT_MUTASI_TABUNGAN){?>
						<table class="default-table display" style="width: 100%;">
							<thead>
							<tr>
								<th>No.</th>
								<th>Anggota</th>
								<th>No. Ref</th>
								<th>Debit</th>
								<th>Credit</th>
								<th>Tgl Transaksi</th>
								<th>Deskripsi</th>
							</tr>
							</thead>
							<tbody>
							<?php

							$no = 1;
							foreach($dataReportTabungan as $tabungan){?>
								<tr>
									<td><?= $no++; ?></td>
									<td>
										<strong>
											<span class="text-info"><?= $tabungan->member_referal_code ?></span>
										</strong>
										<br/>
										<?= $tabungan->member_full_name ?>
									</td>
									<td><?= $tabungan->transaction_code ?></td>
									<td class="js-currencyFormatter" data-price="<?= $tabungan->debit ?: 0?>"></td>
									<td class="js-currencyFormatter" data-price="<?= $tabungan->credit ?: 0?>"></td>
									<td><?= date("Y m d ", strtotime($tabungan->created_at)) ?></td>
									<td><?= $tabungan->description ?></td>
								</tr>
							<?php }?>
							</tbody>
						</table>

					<?php }else{?>

						<table class="default-table display" style="width: 100%;">
							<thead>
							<tr>
								<th>No.</th>
								<th>Anggota</th>
								<th>No. Ref</th>
								<th>Imbal Jasa</th>
								<th>Biaya Admin</th>
								<th>Akumulasi</th>
								<th>Tgl Terima</th>
							</tr>
							</thead>
							<tbody>
							<?php

							$no = 1;
							foreach($dataReportTabungan as $tabungan){?>
								<tr>
									<td><?= $no++; ?></td>
									<td>
										<strong>
											<span class="text-info"><?= $tabungan->member_referal_code ?></span>
										</strong>
										<br/>
										<?= $tabungan->member_full_name ?>
									</td>
									<td><?= $tabungan->tij_transfer_code ?></td>
									<td class="js-currencyFormatter" data-price="<?= $tabungan->tij_ammount?>"></td>
									<td class="js-currencyFormatter" data-price="<?= $tabungan->tij_admin_fee?>"></td>
									<td class="js-currencyFormatter" data-price="<?= $tabungan->tij_accumulation?>"></td>
									<td><?= date("Y m d ", strtotime($tabungan->created_at)) ?></td>
								</tr>
							<?php }?>
							</tbody>
						</table>
					<?php }?>

				</div>
			</div>
		</div>
	</div>
</div>
