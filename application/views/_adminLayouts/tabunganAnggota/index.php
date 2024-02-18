<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item"><a href="#">Tabungan</a></li>
			<li class="breadcrumb-item"><a href="#">Tabungan Anggota</a></li>
			<li class="breadcrumb-item active" aria-current="page"></li>
			<?php
			switch ($pageType){
				case MemberTabunganApproval::TABUNGAN_NEW:
					$headline = "Permintaan Buka Tabungan Baru";
					break;
				case MemberTabunganApproval::TABUNGAN_PROCESSED:
					$headline = "Tabungan Aktif";
					break;
				case MemberTabunganApproval::TABUNGAN_REJECTED:
					$headline = "Tabungan Ditolak";
					break;
			}
			echo $headline;
			?>
		</ol>
	</nav>
</div>

<div class="main-wrapper">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card">
				<div class="card-header">
					<h5>
						<?= $headline ?>
					</h5>
				</div>
				<div class="card-body">
					<?= $this->session->flashdata('message')?>
					<table class="default-table display" style="width: 100%;">
						<thead>
						<tr>
							<th>No.</th>
							<th>Anggota</th>
							<th>No. Ref</th>
							<th>Jumlah</th>
							<th>Tgl</th>
							<th>Status</th>
							<?php if ($pageType == MemberTabunganApproval::TABUNGAN_NEW) {?>
								<th>Aksi</th>
							<?php }?>
						</tr>
						</thead>
						<tbody>
						<?php

						$no = 1;
						$tabunganStatus="";
						$tabunganStatusBadge="";

						foreach($dataTabungan as $tabungan){
							$tabunganStatus = $tabungan->tabungan_approval;

							switch ($tabunganStatus){
								case MemberTabunganApproval::TABUNGAN_NEW:
									$tabunganStatusBadge = 'badge-info';
									break;
								case MemberTabunganApproval::TABUNGAN_PROCESSED:
									$tabunganStatusBadge = 'badge-primary';
									break;
								case MemberTabunganApproval::TABUNGAN_REJECTED:
									$tabunganStatusBadge = 'badge-danger';
									break;
							}
							?>
							<tr>
								<td><?= $no++; ?></td>
								<td>
									<strong>
										<span class="text-info"><?= $tabungan->member_referal_code ?></span>
									</strong>
									<br/>
									<?= $tabungan->member_full_name ?>
								</td>
								<td><?= $tabungan->tabungan_tr_code ?></td>
								<td class="js-currencyFormatter" data-price="<?= $tabungan->tabungan_ammount?>"></td>
								<td>
									<?= $pageType == MemberTabunganApproval::TABUNGAN_NEW ?
										date('d M Y ', strtotime($tabungan->created_at)) : date('d M Y ', strtotime($tabungan->tabungan_approved_date)) ; ?>
								</td>
								<td>
									<span class="badge <?= $tabunganStatusBadge ?> js_tabungan-status">
										<?= $tabunganStatus ?>
									</span>
								</td>
								<?php if ($pageType == MemberTabunganApproval::TABUNGAN_NEW) {?>
									<td>
										<button type="button" class="btn btn-primary btn-sm" id="js_member-tabungan-detail" data-id="<?= $tabungan->id ?>" data-toggle="modal" data-target="#exampleModal">Detail</button>
									</td>
								<?php }?>
							</tr>
						<?php }?>
						</tbody>
					</table>

					<?php if ($pageType == MemberTabunganApproval::TABUNGAN_NEW) {?>
						<!-- Modal -->
						<div class="modal fade js_admin-tabungan-anggota-approval" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalDetail" aria-hidden="true"
							 data-url="<?=base_url('admin/AdminAjax/showMemberTabunganDetail/'); ?>">
							<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
								<div class="modal-content" id="block-modal">
									<form action="<?= base_url('admin/tabungan/anggota/update')?>" method="POST" class="js_form-admin-tabungan-anggota-approval">
										<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
										<input type="hidden" name="tabungan_member_id" class="form-control" value="">
										<input type="hidden" name="member_id" class="form-control" value="">
										<input type="hidden" name="tabungan_tr_code" class="form-control" value="">
										<div class="modal-header">
											<h5 class="modal-title" id="exampleModalCenterTitle">Detail Tabungan</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<i class="material-icons">close</i>
											</button>
										</div>
										<div class="modal-body">
											<div class="text-center">
												<h3><span class="badge <?= $tabunganStatusBadge ?> js_tabungan-status"></span></h3>
											</div>
											<h3 class="card-title">Personal Info</h3>
											<table class="table table-striped table-bordered table-condensed">
												<tbody>
												<tr>
													<td>Kode Anggota</td>
													<td class="js_member-uid"></td>
												</tr>
												<tr>
													<td>Nama Anggota</td>
													<td class="js_tabungan-member-full-name"></td>
												</tr>
												<tr>
													<td>Nomor Hp</td>
													<td class="js_tabungan-member-phone-number"></td>
												</tr>
												<tr>
													<td>Email</td>
													<td class="js_tabungan-member-email"></td>
												</tr>
												</tbody>
											</table>
											<br/>
											<h3 class="card-title">Tabungan Info</h3>
											<table class="table table-striped table-bordered table-condensed">
												<tbody>
												<tr>
													<td>Status</td>
													<td>
														<select name="tabungan_approval" class="modal-select form-control" id="selectTabunganApprovalModal" style="display: none;width: 100%">
															<optgroup label="--Pilih Status Tabungan--">
																<option value="<?= MemberTabunganApproval::TABUNGAN_NEW ?>">Tabungan Baru</option>
																<option value="<?= MemberTabunganApproval::TABUNGAN_PROCESSED ?>" >Tabungan Diproses</option>
																<option value="<?= MemberTabunganApproval::TABUNGAN_REJECTED ?>" >Tabungan Ditolak</option>
															</optgroup>
														</select>
														<small class="text-danger"><?= form_error('tabungan_approval')?></small>
													</td>
												</tr>
												<tr>
													<td>Kode tabungan</td>
													<td class="js_tabungan-code"></td>
												</tr>
												<tr>
													<td>Gateway</td>
													<td class="js_tabungan-gateway"></td>
												</tr>
												<tr>
													<td>Nomor Rekening</td>
													<td class="js_tabungan-gateway-number"></td>
												</tr>
												<tr>
													<td>Nama Pemilik Akun</td>
													<td class="js_tabungan-gateway-account"></td>
												</tr>
												<tr>
													<td>Jumlah Tabungan</td>
													<td class="js_tabungan-ammount"></td>
												</tr>
												</tbody>
											</table>
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
					<?php }?>
				</div>
			</div>
		</div>
	</div>
</div>
