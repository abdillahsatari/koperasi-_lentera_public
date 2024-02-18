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
<?php if ($pageType == "Permohonan Pengajuan Pinjaman") { ?>
	<div class="main-wrapper">
		<?= $this->session->flashdata("message")?>
		<div class="row">
			<div class="col-lg-6 col-md-12">
				<div class="card savings-card">
					<div class="card-body">
						<h5 class="card-title">Jumlah Pinjaman</h5>
						<div class="savings-stats">
							<h5 class="js-currencyFormatter" data-price="<?= $dataAllPinjaman ?>"></h5>
						</div>
					</div>
					<div class="card-footer">
						<div class="float-right">
							<a href="<?= base_url('admin/pinjaman/semua-pinjaman')?>" class="btn btn-success">Detail</a>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-12">
				<div class="card savings-card">
					<div class="card-body">
						<h5 class="card-title">Pinjaman Sudah Dibayar</h5>
						<div class="savings-stats">
							<h5 class="js-currencyFormatter" data-price="<?= $dataPaidPinjaman ?>"></h5>
						</div>
					</div>
					<div class="card-footer">
						<div class="float-right">
							<a href="<?= base_url('admin/pinjaman/pembayaran-pinjaman')?>" class="btn btn-success">Detail</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-12">
			<div class="card">
				<div class="alert alert-success no-m">
					Permohonan Pinjaman.
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<div class="card">
					<div class="card-header">
						<h5>Daftar Permohonan Pinjaman</h5>
					</div>
					<div class="card-body">
						<table class="default-table display" style="width: 100%;">
							<thead>
							<tr>
								<th>No.</th>
								<th>Tanggal</th>
								<th>No. Anggota</th>
								<th>No. Ref</th>
								<th>Jumlah Pinjaman</th>
								<th>Tenor</th>
								<th>Status</th>
								<th>Aksi</th>
							</tr>
							</thead>
							<tbody>
							<?php

							$no=1;
							$approvedStatus = "";
							foreach($dataMemberPinjamanList as $pinjaman){

								switch ($pinjaman->approved_status){
									case MemberPinjamanApproval::PINJAMAN_NEW:
										$approvedStatus = "Pengajuan Baru";
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
									<td>
										<span class="text-info"><?= $pinjaman->member_referal_code ?></span><br/>
										<?= $pinjaman->member_full_name ?>
									</td>
									<td><?= $pinjaman->pinjaman_transaction_code ?></td>
									<td class="js-currencyFormatter" data-price="<?= $pinjaman->pinjaman_member_ammount ?>"></td>
									<td><?= $pinjaman->pinjaman_member_tenor." ".substr($pinjaman->pinjaman_content_tenor_type, 0, -2) ?></td>
									<td><?= $approvedStatus?></td>
									<td>
										<button class="btn btn-primary"
												data-id="<?=$pinjaman->member_pinjaman_id?>" data-toggle="modal" data-target="#exampleModal"
												>Edit</button>
									</td>
								</tr>
							<?php }?>
							</tbody>
						</table>
						<!-- Modal Approval  -->
						<div class="modal fade js_admin-post-pinjaman-anggota" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalDetail" aria-hidden="true"
							 data-url="<?=base_url('admin/AdminAjax/showMemberPinjamanRequest'); ?>">
							<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
								<div class="modal-content" id="block-modal">
									<form action="<?= base_url('admin/pinjaman/permohonan-pinjaman/update')?>" method="POST" class="js_form-admin-pinjaman-anggota">
										<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
										<input type="hidden" name="member_id" value="">
										<input type="hidden" name="pinjaman_id" value="">
										<input type="hidden" name="pinjaman_transaction_code" value="">
										<input type="hidden" name="pinjaman_ammount" value="">
										<input type="hidden" name="pinjaman_tenor" value="">
										<input type="hidden" name="pinjaman_tenor_type" value="">
										<input type="hidden" name="pinjaman_bunga" value="">
										<div class="modal-header">
											<h5 class="modal-title" id="exampleModalCenterTitle">Detail Pinjaman</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<i class="material-icons">close</i>
											</button>
										</div>
										<div class="modal-body">
											<div class="text-center">
												<h3><span class="badge <?= $approvedStatus ?> js_withdrawal-status"></span></h3>
											</div>
											<div class="col-md-6 col-lg-12">
												<h3 class="card-title">Personal Info</h3>
												<table class="table table-striped table-bordered table-condensed">
													<tbody>
													<tr>
														<td>Kode Anggota</td>
														<td class="js_pinjaman-member-uid"></td>
													</tr>
													<tr>
														<td>Nama Anggota</td>
														<td class="js_pinjaman-member-full-name"></td>
													</tr>
													<tr>
														<td>Tgl Permohonan</td>
														<td class="js_pinjaman-created-at"></td>
													</tr>
													<tr>
														<td>Deskripsi</td>
														<td class="js_pinjaman-description"></td>
													</tr>
													</tbody>
												</table>
											</div>
											<div class="col-md-6 col-lg-12">
												<br>
												<h3 class="card-title">Pinjaman Info</h3>
												<table class="table table-striped table-bordered table-condensed">
													<tbody>
													<tr>
														<td>No. Pinjaman</td>
														<td class="js_pinjaman-transaction-code"></td>
													</tr>
													<tr>
														<td>Jumlah Pinjaman</td>
														<td class="js_pinjaman-member-ammount"></td>
													</tr>
													<tr>
														<td>Periode</td>
														<td class="js_pinjaman-tenor"></td>
													</tr>
													<tr>
														<td>Bunga</td>
														<td class="js_pinjaman-bunga"></td>
													</tr>
													<tr>
														<td>Status</td>
														<td>
															<select name="pinjaman_status_approval" class="modal-select form-control" id="pinjaman_status_approval" style="display: none;width: 100%">
																<optgroup label="--Pilih Status Pengajuan Pinjaman--">
																	<option value="<?= MemberPinjamanApproval::PINJAMAN_NEW ?>" ><?= MemberPinjamanApproval::PINJAMAN_NEW ?></option>
																	<option value="<?= MemberPinjamanApproval::PINJAMAN_PROCESSED ?>" ><?= MemberPinjamanApproval::PINJAMAN_PROCESSED ?></option>
																	<option value="<?= MemberPinjamanApproval::PINJAMAN_REJECTED ?>" ><?= MemberPinjamanApproval::PINJAMAN_REJECTED ?></option>
																	<option value="<?= MemberPinjamanApproval::PINJAMAN_CANCEL ?>"  ><?= MemberPinjamanApproval::PINJAMAN_CANCEL ?></option>
																</optgroup>
															</select>
															<small class="text-danger"><?= form_error('pinjaman_status_approval')?></small>
														</td>
													</tr>
													</tbody>
												</table>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
											<button type="submit" class="btn btn-primary">Update</button>
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

<?php } elseif ($pageType == "Semua Pinjaman"){?>
	<div class="main-wrapper">
		<?= $this->session->flashdata("message")?>
		<div class="row">
			<div class="col-lg-12 col-md-12">
				<div class="card savings-card">
					<div class="card-body">
						<h5 class="card-title">Jumlah Pinjaman</h5>
						<div class="savings-stats">
							<h5 class="js-currencyFormatter" data-price="<?= $dataAllPinjaman ?>"></h5>
						</div>
					</div>
					<div class="card-footer">
						<div class="float-right">
							<a href="<?= base_url('admin/pinjaman/semua-pinjaman')?>" class="btn btn-success">Detail</a>
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
						<h5>Daftar Semua Pinjaman</h5>
					</div>
					<div class="card-body">
						<table class="default-table display" style="width: 100%;">
							<thead>
							<tr>
								<th>No.</th>
								<th>Tanggal</th>
								<th>No. Anggota</th>
								<th>No. Ref</th>
								<th>Jumlah Pinjaman</th>
								<th>Tenor</th>
								<th>Status</th>
								<th>Aksi</th>
							</tr>
							</thead>
							<tbody>
							<?php $no=1; foreach($dataMemberPinjamanList as $pinjaman){

								switch ($pinjaman->approved_status){
									case MemberPinjamanApproval::PINJAMAN_NEW:
										$approvedStatus = "Pengajuan Baru";
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
								<tr class="text-center">
									<td><?= $no++; ?></td>
									<td><?= date('d M Y ', strtotime($pinjaman->created_at)) ?></td>
									<td>
										<span class="text-info"><?= $pinjaman->member_referal_code ?></span><br/>
										<?= $pinjaman->member_full_name ?>
									</td>
									<td><?= $pinjaman->pinjaman_transaction_code ?></td>
									<td class="js-currencyFormatter" data-price="<?= $pinjaman->pinjaman_member_ammount ?>"></td>
									<td><?= $pinjaman->pinjaman_member_tenor." ".substr($pinjaman->pinjaman_content_tenor_type, 0, -2) ?></td>
									<td><?= $approvedStatus?></td>
									<td>
										<?php if ($approvedStatus == "Telah di konfirmasi"){?>
											<a href="<?= base_url('admin/pinjaman/detail/'.$pinjaman->member_pinjaman_id)?>" class="btn btn-primary">Detail</a>
										<?php } else {?>
											<span>-</span>
										<?php }?>
									</td>
								</tr>
							<?php }?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php } elseif ($pageType == "Semua Pembayaran Pinjaman"){?>
	<div class="main-wrapper">
		<?= $this->session->flashdata("message")?>
		<div class="row">
			<div class="col-lg-12 col-md-12">
				<div class="card savings-card">
					<div class="card-body">
						<h5 class="card-title">Pinjaman Sudah Dibayar</h5>
						<div class="savings-stats">
							<h5 class="js-currencyFormatter" data-price="<?= $dataPaidPinjaman ?>"></h5>
						</div>
					</div>
					<div class="card-footer">
						<div class="float-right">
							<a href="<?= base_url('admin/pinjaman/pembayaran-pinjaman')?>" class="btn btn-success">Detail</a>
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
						<h5>Laporan Pinjaman Yang Telah Dibayar</h5>
					</div>
					<div class="card-body">
						<table class="default-table display" style="width: 100%;">
							<thead>
							<tr>
								<th>No.</th>
								<th>Tanggal Pembayaran</th>
								<th>Nama Anggota</th>
								<th>No. Ref</th>
								<th>No. Pembayaran</th>
								<th>Jumlah</th>
								<th>Tenor Ke</th>
							</tr>
							</thead>
							<tbody>
							<?php $no=1; foreach($dataMemberPinjamanList as $pinjaman){

								switch ($pinjaman->approved_status){
									case MemberPinjamanApproval::PINJAMAN_NEW:
										$approvedStatus = "Pengajuan Baru";
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
									<td><?= date('d M Y ', strtotime($pinjaman->pinjaman_payment_date)) ?></td>
									<td>
										<span class="text-info"><?= $pinjaman->member_referal_code ?></span><br/>
										<?= $pinjaman->member_full_name ?>
									</td>
									<td><?= $pinjaman->pinjaman_detail_transaction_code ?></td>
									<td><?= $pinjaman->pinjaman_detail_payment_code ?></td>
									<td class="js-currencyFormatter" data-price="<?= $pinjaman->pinjaman_bunga_ammount ?>"></td>
									<td><?= substr($pinjaman->pinjaman_content_tenor_type, 0, -2)." ke - ".$pinjaman->pinjaman_tenor_number ?></td>
								</tr>
							<?php }?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php }?>
