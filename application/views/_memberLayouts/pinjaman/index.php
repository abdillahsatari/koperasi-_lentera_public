<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item"><a href="#">Pinjaman</a></li>
			<li class="breadcrumb-item active" aria-current="page">
				<?= $pageType ?>
			</li>
		</ol>
	</nav>
</div>
<?php if ($pageType == "Pinjaman Saya") { ?>
	<div class="main-wrapper">
		<?= $this->session->flashdata("message")?>
		<div class="col-lg-12">
			<div class="card">
				<div class="alert alert-info no-m">
					Pinjaman Saya.
				</div>
			</div>
		</div>
		<br/>
		<div class="row">
			<div class="col-lg-4 col-md-12">
				<div class="card savings-card">
					<div class="card-body">
						<h5 class="card-title">Jumlah Pinjaman</h5>
						<div class="savings-stats">
							<h5 class="js-currencyFormatter" data-price="<?= $dataAllPinjaman ?>"></h5>
						</div>
					</div>
					<div class="card-footer">
						<div class="float-right">
							<a href="<?= base_url('member/pinjaman/pengajuan')?>" class="btn btn-success">Ajukan Pinjaman</a>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-12">
				<div class="card savings-card">
					<div class="card-body">
						<h5 class="card-title">Pinjaman Sudah Dibayar</h5>
						<div class="savings-stats">
							<h5 class="js-currencyFormatter" data-price="<?= $dataPaidPinjaman ?>"></h5>
						</div>
					</div>
					<div class="card-footer">
						<div class="float-right">
							<a href="<?= base_url('member/pinjaman/laporan/pembayaran')?>" class="btn btn-success">Detail</a>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-12">
				<div class="card savings-card">
					<div class="card-body">
						<h5 class="card-title">Pinjaman Belum Dibayar</h5>
						<div class="savings-stats">
							<h5 class="js-currencyFormatter" data-price="<?= $dataUnpaidPinjaman ?>"></h5>
						</div>
					</div>
					<div class="card-footer">
						<div class="float-right">
							<a href="<?= base_url('member/pinjaman/pembayaran')?>" class="btn btn-success">Bayar Pinjaman</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php if ($dataMemberPinjamanContent){?>
			<div class="col-lg-12">
				<div class="card">
					<div class="alert alert-info no-m">
						Detail Pinjaman Saya
					</div>
				</div>
			</div>
			<br/>
			<div class="row">
				<?php foreach ($dataMemberPinjamanContent as $dataPinjamanMember) {?>
					<div class="col-lg-4 col-md-12">
						<div class="card savings-card">
							<div class="card-body">
								<h5 class="card-title"><?= $dataPinjamanMember->member_pinjaman_content_name?></h5>
								<div class="savings-stats">
									<h5 class="js-currencyFormatter" data-price="<?= $dataPinjamanMember->member_pinjaman_total_ammount?>"></h5>
								</div>
							</div>
						</div>
					</div>
				<?php }?>
			</div>
			<?php if ($this->session->userdata("member_is_kyc") && $this->session->userdata("member_is_activated")) {?>
				<!-- Modal Bayar  -->
				<div class="modal fade js_member-post-simpanan-funding" id="simpananFunding" tabindex="-1" role="dialog" aria-labelledby="detailDepositModal" aria-hidden="true"
					 data-url="<?=base_url('member/MemberAjax/showLenteraSimpananContentDetail/'); ?>">
					<div class="modal-dialog modal-dialog-centered modal-md" role="document">
						<div class="modal-content" id="block-modal">
							<form action="<?= base_url('member/simpanan/program-funding/save') ?>" method="POST" class="js_form-member-deposit">
								<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
								<input type="hidden" name="lentera_simpanan_content_id" value="">
								<input type="hidden" name="simpanan_content_type" value="<?=SimpananContentType::SIMPANAN_FUNDING?>">
								<input type="hidden" name="simpanan_input_type" value="ammount">
								<input type="hidden" name="lentera_simpanan_content_name" value="">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalCenterTitle">Detail Program</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<i class="material-icons">close</i>
									</button>
								</div>
								<div class="modal-body">
									<div class="col-lg-12">
										<div class="alert alert-warning" role="alert">
											<ul>
												<li>
													<span class="js_simpanan-name"></span> Minimal <span class="js_simpanan-price"></span>
												</li>
												<li>
													Anda akan mendapatkan bagi untung sebesar <span class="js_simpanan-bagi-untung"></span> perbulan selama <span class="js_simpanan-duration"></span> hari
												</li>
												<li>
													Saldo anda saat ini <span class="js-currencyFormatter" data-price="<?= current($this->FinanceModel->memberFinance($this->session->userdata("member_id")))->totalWalletBalance; ?>"></span>
												</li>
											</ul>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group js-simpanan_member_input_ammount">

										</div>

										<input type="hidden" class="form-control" name="simpanan_member_ammount" value="" placeholder="Nominal Simpanan">

										<label>Kode Transaksi</label>
										<div class="form-group input-group">
											<div class="input-group-prepend">
											<span class="input-group-text btn btn-primary" id="inputGroupPrepend">
												<div class="col-sm-1 fas fa-lock">
												</div>
											</span>
											</div>
											<input type="password" class="form-control" id="member_transaction_code" name="member_transaction_code"
												   placeholder="*****" aria-describedby="member_transaction_code">
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="submit" class="btn btn-primary">Bayar</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!--./Modal-->
			<?php }?>
		<?php }?>

		<div class="col-lg-12">
			<div class="card">
				<div class="alert alert-success no-m">
					Mutasi Pinjaman.
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<div class="card">
					<div class="card-header">
						<h5>Daftar Pinjaman</h5>
					</div>
					<div class="card-body">
						<table class="default-table display" style="width: 100%;">
							<thead>
							<tr>
								<th>No.</th>
								<th>Tanggal</th>
								<th>No. Ref</th>
								<th>Jumlah</th>
								<th>Tenor</th>
								<th>Status</th>
							</tr>
							</thead>
							<tbody>
							<?php $no=1; foreach($dataMemberPinjamanList as $pinjaman){

								switch ($pinjaman->approved_status){
									case MemberPinjamanApproval::PINJAMAN_NEW:
										$approvedStatus = "Menunggu persetujuan";
										break;
									case MemberPinjamanApproval::PINJAMAN_PROCESSED:
										$approvedStatus = "Telah di konfirmasi";
										break;
									case MemberPinjamanApproval::PINJAMAN_CANCEL:
										$approvedStatus = "Dibatalkan";
										break;
									case MemberPinjamanApproval::PINJAMAN_PENDING:
										$approvedStatus = "Ditunda";
										break;
									case MemberPinjamanApproval::PINJAMAN_REJECTED:
										$approvedStatus = "Ditolak";
										break;
									default:
										$approvedStatus = "Menunggu Persetujuamn";
										break;
								}

								?>
								<tr>
									<td><?= $no++; ?></td>
									<td><?= date('d M Y ', strtotime($pinjaman->created_at)) ?></td>
									<td><?= $pinjaman->pinjaman_transaction_code ?></td>
									<td class="js-currencyFormatter" data-price="<?= $pinjaman->pinjaman_member_ammount ?>"></td>
									<td><?= $pinjaman->pinjaman_member_tenor." ".substr($pinjaman->pinjaman_content_tenor_type, 0, -2) ?></td>
									<td><?= $approvedStatus ?></td>
								</tr>
							<?php }?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php } elseif ($pageType == "Pembayaran Pinjaman"){?>
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
					<div class="card-footer">
						<div class="float-right">
							<a href="<?= base_url('member/pinjaman/laporan/pembayaran')?>" class="btn btn-success">Detail</a>
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
					<div class="card-footer">
						<div class="float-right">
							<a href="<?= base_url('member/pinjaman/pembayaran')?>" class="btn btn-success">Detail</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-12">
			<div class="card">
				<div class="alert alert-success no-m">
					Mutasi Pinjaman.
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<div class="card">
					<div class="card-header">
						<h5>Daftar Pinjaman Yang Harus Dibayar</h5>
					</div>
					<div class="card-body">
						<table class="default-table display" style="width: 100%;">
							<thead>
							<tr>
								<th>No.</th>
								<th>Jenis Pinjaman</th>
								<th>Jatuh Tempo</th>
								<th>No. Ref</th>
								<th>Jumlah</th>
								<th>Tenor</th>
								<th>Aksi</th>
							</tr>
							</thead>
							<tbody>
							<?php $no=1; foreach($dataPinjamanList as $pinjamanUnpaid){?>
								<tr>
									<td><?= $no++ ?></td>
									<td><?= $pinjamanUnpaid->pinjaman_name?></td>
									<td><?= date("Y m d ", strtotime($pinjamanUnpaid->pinjaman_due_date)) ?></td>
									<td><?= $pinjamanUnpaid->pinjaman_detail_transaction_code ?></td>
									<td class="js-currencyFormatter" data-price="<?= $pinjamanUnpaid->pinjaman_bunga_ammount ?>"></td>
									<td><?= substr($pinjamanUnpaid->pinjaman_content_tenor_type,0,-2)." ke - ".$pinjamanUnpaid->pinjaman_tenor_number ?></td>
									<td>
										<button class="btn btn-primary"
												data-id="<?=$pinjamanUnpaid->member_pinjaman_detail_id?>" data-toggle="modal" data-target="#exampleModal"
										>Bayar</button>
									</td>
								</tr>
							<?php }?>
							</tbody>
						</table>
						<!-- Modal Bayar  -->
						<div class="modal fade js_member-post-pinjaman-detail" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalDetail" aria-hidden="true"
							 data-url="<?=base_url('member/MemberAjax/showMemberPinjamanRequest'); ?>">
							<div class="modal-dialog modal-dialog-centered modal-md" role="document">
								<div class="modal-content" id="block-modal">
									<form action="<?= base_url('member/pinjaman/pembayaran/update')?>" method="POST" class="js_form-member-pinjaman-detail">
										<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
										<input type="hidden" name="pinjaman_detail_id" value="">
										<input type="hidden" name="member_id" value="">
										<input type="hidden" name="pinjaman_id" value="">
										<input type="hidden" name="pinjaman_tenor_number" value="">
										<input type="hidden" name="pinjaman_bunga_ammount" value="">
										<div class="modal-header">
											<h5 class="modal-title" id="exampleModalCenterTitle">Detail Pinjaman</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<i class="material-icons">close</i>
											</button>
										</div>
										<div class="modal-body">
											<div class="text-center">
												<h3>Bayar Pinjaman ?</h3>
											</div>
											<div class="col-md-6 col-lg-12">
												<table class="table table-striped table-bordered table-condensed">
													<tbody>
													<tr>
														<td>No. Ref</td>
														<td class="js_pinjaman-ref-number"></td>
													</tr>
													<tr>
														<td>No. Pembayaran</td>
														<td class="js_pinjaman-paid-number"></td>
													</tr>
													<tr>
														<td>Pembayaran Ke</td>
														<td class="js_pinjaman-tenor-number"></td>
													</tr>
													<tr>
														<td>Tanggal Jatuh Tempo</td>
														<td class="js_pinjaman-due-date"></td>
													</tr>
													<tr>
														<td>Jumlah</td>
														<td class="js_pinjaman-bunga-ammount"></td>
													</tr>
													</tbody>
												</table>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
											<button type="submit" class="btn btn-primary">Bayar</button>
										</div>
									</form>
								</div>
							</div>
						</div>
						<!-- ./Modal -->
					</div>
				</div>
			</div>
		</div>
	</div>

<?php } elseif ($pageType == "Laporan Pinjaman Dibayar"){?>
	<div class="main-wrapper">
		<?= $this->session->flashdata("message")?>
		<div class="row">
			<div class="col-lg-12 col-md-12">
				<div class="card savings-card">
					<div class="card-body">
						<h5 class="card-title">Pinjaman Dibayar</h5>
						<div class="savings-stats">
							<h5 class="js-currencyFormatter" data-price="<?= $dataPaidPinjaman ?>"></h5>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-12">
			<div class="card">
				<div class="alert alert-success no-m">
					Mutasi Pinjaman.
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<div class="card">
					<div class="card-header">
						<h5>Daftar Pinjaman Yang Sudah Dibayar</h5>
					</div>
					<div class="card-body">
						<table class="default-table display" style="width: 100%;">
							<thead>
							<tr>
								<th>No.</th>
								<th>Jenis Pinjaman</th>
								<th>Tanggal Bayar</th>
								<th>Kode Transaksi</th>
								<th>Jumlah</th>
								<th>Tenor</th>
							</tr>
							</thead>
							<tbody>
							<?php $no=1; foreach($dataPinjamanList as $pinjamanPaid){?>
								<tr>
									<td><?= $no++ ?></td>
									<th><?= $pinjamanPaid->pinjaman_name ?></th>
									<td><?= date("Y m d ", strtotime($pinjamanPaid->pinjaman_payment_date)) ?></td>
									<td><?= $pinjamanPaid->pinjaman_detail_transaction_code ?></td>
									<td class="js-currencyFormatter" data-price="<?= $pinjamanPaid->pinjaman_bunga_ammount ?>"></td>
									<td><?= substr($pinjamanPaid->pinjaman_content_tenor_type,0,-2)." ke - ".$pinjamanPaid->pinjaman_tenor_number ?></td>
								</tr>
							<?php }?>
							</tbody>
						</table>
						<!-- Modal Bayar  -->
						<div class="modal fade js_member-post-simpanan-anggota" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="detailDepositModal" aria-hidden="true"
							 data-url="<?=base_url('member/MemberAjax/showLenteraSimpananContentDetail/'); ?>">
							<div class="modal-dialog modal-dialog-centered modal-md" role="document">
								<div class="modal-content" id="block-modal">
									<form action="<?= base_url('member/simpanan/save')?>" method="POST" class="js_member-post-simpanan-anggota">
										<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
										<input type="hidden" name="lentera_simpanan_content_id" value="">
										<input type="hidden" name="simpanan_content_type" value="<?=SimpananContentType::SIMPANAN_KEANGGOTAAN?>">
										<input type="hidden" name="simpanan_input_type" value="pokok">
										<input type="hidden" name="lentera_simpanan_content_name" value="">
										<div class="modal-header">
											<h5 class="modal-title" id="exampleModalCenterTitle">Bayar Simpanan</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<i class="material-icons">close</i>
											</button>
										</div>
										<div class="modal-body">
											<div class="col-lg-12">
												<div class="alert alert-warning" role="alert">
													<ul>
														<li class="js_detail-simpanan_anggota"></li>
														<li>
															Saldo anda saat ini <span class="js-currencyFormatter" data-price="<?= current($this->FinanceModel->memberFinance($this->session->userdata("member_id")))->totalWalletBalance; ?>"></span>
														</li>
													</ul>
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group js-simpanan_member_input_ammount">

												</div>

												<input type="hidden" class="form-control" name="simpanan_member_ammount" value="" placeholder="Nominal Simpanan">
												<input type="hidden" class="form-control" name="simpanan_member_month" value="" placeholder="Nominal Simpanan">

												<label>Kode Transaksi</label>
												<div class="form-group input-group">
													<div class="input-group-prepend">
												<span class="input-group-text btn btn-primary" id="inputGroupPrepend">
													<div class="col-sm-1 fas fa-lock">
													</div>
												</span>
													</div>
													<input type="password" class="form-control" id="member_transaction_code" name="member_transaction_code"
														   placeholder="*****" aria-describedby="member_transaction_code">
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="submit" class="btn btn-primary">Bayar</button>
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
										</div>
									</form>
								</div>
							</div>
						</div>
						<!-- ./Modal -->
					</div>
				</div>
			</div>
		</div>
	</div>

<?php }?>
