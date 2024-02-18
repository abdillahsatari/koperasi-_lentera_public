<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item"><a href="#">Tabungan</a></li>
			<li class="breadcrumb-item active" aria-current="page"></li>
			Tabungan Saya
		</ol>
	</nav>
</div>

<div class="main-wrapper">
	<?= $this->session->flashdata('message')?>
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
						<a href="<?= base_url('member/tabungan/laporan?type=') . strtolower(MemberTabunganReportType::TABUNGAN_REPORT_MUTASI_TABUNGAN) ?>" class="btn btn-success">Detail</a>
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
						<a href="<?= base_url('member/tabungan/laporan?type=') . strtolower(MemberTabunganReportType::TABUNGAN_REPORT_IMBAL_JASA) ?>" class="btn btn-success">Detail</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-12">
		<div class="card">
			<div class="alert alert-success no-m">
				Daftar Pengajuan Tabungan Saya
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card">
				<div class="card-header">
					<h5>
						Daftar Pengajuan Tabungan Saya
					</h5>
				</div>
				<div class="card-body">
					<table class="default-table display" style="width: 100%;">
						<thead>
						<tr>
							<th>No.</th>
							<th>Jumlah Tabungan</th>
							<th>No. Ref</th>
							<th>Kode Tabungan</th>
							<th>Bank</th>
							<th>Tgl</th>
							<th>Status</th>
							<th>Aksi</th>

						</tr>
						</thead>
						<tbody>
						<?php
						$no = 1;
						foreach($dataTabungan as $tabungan){
							$tabunganId = $tabungan->id;
							$tabunganApproval = $tabungan->tabungan_approval;
							?>
							<tr>
								<td><?= $no++; ?></td>
								<td class="js-currencyFormatter" data-price="<?= $tabungan->tabungan_ammount?>"></td>
								<td><?= $tabungan->tabungan_tr_code ?></td>
								<td><?= $tabungan->tabungan_code ?></td>
								<td><?= $tabungan->nama_bank ?></td>
								<td><?= date('d M Y ', strtotime($tabungan->created_at)) ?></td>
								<td><?= $tabunganApproval == MemberTabunganApproval::TABUNGAN_NEW ? "Menunggu Konifrmasi" : $tabunganApproval ?></td>
								<td>
									<button type="button" class="btn btn-primary" id="js_member-tabungan-detail"
											data-toggle="modal" data-target="#detailTabunganModal" data-tabungan-id="<?= $tabunganId ?>">Detail</button>
								</td>
							</tr>
						<?php }?>
						</tbody>
					</table>

					<?php if ($dataTabungan){?>
						<!-- Modal -->
						<div class="modal fade js_member-tabungan-detail" id="detailTabunganModal" tabindex="-1" role="dialog" aria-labelledby="detailTabunganModal" aria-hidden="true"
							 data-url="<?=base_url('member/MemberAjax/showMemberTabunganDetail/'); ?>">
							<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
							<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
								<div class="modal-content" id="block-modal">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalCenterTitle">Detail Tabungan</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<i class="material-icons">close</i>
										</button>
									</div>
									<div class="modal-body">
										<div class="text-center">
											<h3><span class="badge" id="js-tabungan-status-badge">-- Statusnya --</span></h3>
										</div>
										<table class="table table-striped table-bordered table-condensed">
											<tbody>
											<tr>
												<td>Kode Deposit</td>
												<td class="js_tabungan-code"> -- Kodenya --</td>
											</tr>
											<tr>
												<td>Gateway</td>
												<td class="js_tabungan-gateway">-- Nama Bank --</td>
											</tr>
											<tr>
												<td>No. Rekening</td>
												<td class="js_tabungan-gateway-number">-- Nomor Rekening --</td>
											</tr>
											<tr>
												<td>Nama</td>
												<td class="js_tabungan-gateway-account">-- Nama Pemilik Account --</td>
											</tr>
											<tr>
												<td>Nominal Tabungan</td>
												<td class="js_tabungan-ammount">-- deposit ammount --</td>
											</tr>
											</tbody>
										</table>
										<br/>
										<div class="text-center js_tabungan-instructions">

										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									</div>
								</div>
							</div>
						</div>
						<!-- ./Modal -->
					<?php }?>
				</div>
			</div>
		</div>
	</div>
</div>
