<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item"><a href="#">Testing</a></li>
			<li class="breadcrumb-item"><a href="#">Cron Jobs</a></li>
			<li class="breadcrumb-item active" aria-current="page">Tabungan Imbal Jasa</li>
		</ol>
	</nav>
</div>
<div class="main-wrapper">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card">
				<div class="card-header">
					<h5>Tabungan Imbal Jasa history</h5>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-12">
							<a href="<?= base_url('cronJobs/imbal-jasa/simpanan/jobs')?>" type="button" class="btn btn-primary">Do Jobs Simpanan Imbal Jasa</a>
							<a href="<?= base_url('cronJobs/imbal-jasa/tabungan/jobs')?>" type="button" class="btn btn-primary">Do Jobs Tabungan Imbal Jasa</a>
							<a href="<?= base_url('cronJobs/simpanan/expired-tenor/jobs')?>" type="button" class="btn btn-primary">Do Jobs Simpanan Expired Tenor</a>
						</div>
					</div>
					<hr/>
					<table class="default-table display" style="width: 100%;">
						<thead>
						<tr>
							<th>No.</th>
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
								<td><?= $tabungan->tij_transfer_code ?></td>
								<td class="js-currencyFormatter" data-price="<?= $tabungan->tij_ammount?>"></td>
								<td class="js-currencyFormatter" data-price="<?= $tabungan->tij_admin_fee?>"></td>
								<td class="js-currencyFormatter" data-price="<?= $tabungan->tij_accumulation?>"></td>
								<td><?= date("Y m d ", strtotime($tabungan->created_at)) ?></td>
							</tr>
						<?php }?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
