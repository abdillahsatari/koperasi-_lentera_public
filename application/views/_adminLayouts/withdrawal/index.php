<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item"><a href="#">Withdrawal</a></li>
			<li class="breadcrumb-item active" aria-current="page"></li>
			<?php
			switch ($pageType){
				case strtolower(MemberWdStatus::WD_NEW):
					echo "Withdrawal Baru";
					break;
				case strtolower(MemberWdStatus::WD_PROCESSED):
					echo "Withdrawal Telah Diproses";
					break;
				case strtolower(MemberWdStatus::WD_CANCEL):
					echo "Withdrawal Dibatalkan";
					break;
			}
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
						<?php
						switch ($pageType){
							case strtolower(MemberWdStatus::WD_NEW):
								echo "Withdrawal Baru";
								break;
							case strtolower(MemberWdStatus::WD_PROCESSED):
								echo "Withdrawal Telah Diproses";
								break;
							case strtolower(MemberWdStatus::WD_CANCEL):
								echo "Withdrawal Dibatalkan";
								break;
						}
						?>
					</h5>
				</div>
				<div class="card-body">
					<?= $this->session->flashdata('message')?>
					<table class="default-table display" style="width: 100%;">
						<thead>
						<tr>
							<th>No.</th>
							<th>Anggota</th>
							<th>Nominal WD</th>
							<th>Kode Transaksi</th>
							<th>Gateway</th>
							<th>Tgl Pengajuan</th>
							<th>Status</th>
							<?php if ($pageType == strtolower(MemberWdStatus::WD_NEW)) {?>
								<th>Aksi</th>
							<?php }?>
						</tr>
						</thead>
						<tbody>
						<?php

						$no = 1;
						$depositId="";
						$withdrawalStatus="";
						$withdrawalStatusBadge="";

						foreach($dataWithdrawal as $wd){
							$depositId = $wd->id;
							$withdrawalStatus = $wd->wd_status;

							switch ($withdrawalStatus){
								case MemberWdStatus::WD_NEW:
									$withdrawalStatusBadge = 'badge-info';
									break;
								case MemberWdStatus::WD_PROCESSED:
									$withdrawalStatusBadge = 'badge-primary';
									break;
								case MemberWdStatus::WD_PENDING:
									$withdrawalStatusBadge = 'badge-light';
									break;
								case MemberWdStatus::WD_CANCEL:
									$withdrawalStatusBadge = 'badge-danger';
									break;
							}
							?>
							<tr>
								<td><?= $no++; ?></td>
								<td>
									<strong>
										<span class="text-info"><?= $wd->member_referal_code ?></span>
									</strong>
									<br/>
									<?= $wd->member_full_name ?>
								</td>
								<td class="js-currencyFormatter" data-price="<?= $wd->wd_ammount?>"></td>
								<td><?= $wd->wd_transaction_code ?></td>
								<td><?= $wd->bank_account_name ?></td>
								<td><?= date('d M Y ', strtotime($wd->created_at)) ?></td>
								<td>
									<span class="badge <?= $withdrawalStatusBadge ?> js_withdrawal-status">
										<?= $withdrawalStatus == MemberWdStatus::WD_NEW ? "Pengajuan Baru" : $wd->wd_status ?>
									</span>
								</td>
								<?php if ($pageType == strtolower(MemberWdStatus::WD_NEW)) {?>
									<td>
										<button type="button" class="btn btn-primary btn-sm" id="js_member-withdrawal-detail" data-id="<?= $wd->id ?>" data-toggle="modal" data-target="#exampleModal">Detail</button>
									</td>
								<?php }?>
							</tr>
						<?php }?>
						</tbody>
					</table>

					<?php if ($pageType == strtolower(MemberWdStatus::WD_NEW)) {?>
						<!-- Modal -->
						<div class="modal fade js_show-admin-member-withdrawal-detail" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalDetail" aria-hidden="true"
							 data-url="<?=base_url('admin/AdminAjax/showMemberWithdrawal/'); ?>">
							<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
								<div class="modal-content" id="block-modal">
									<form action="<?= base_url('admin/withdrawal-update')?>" method="POST" class="js_form-admin-member-withdrawal">
										<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
										<input type="hidden" name="suffix" value="<?= $withdrawalStatus ?>">
										<input type="hidden" name="withdrawal_id" value="">
										<div class="modal-header">
											<h5 class="modal-title" id="exampleModalCenterTitle">Detail Withdrawal</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<i class="material-icons">close</i>
											</button>
										</div>
										<div class="modal-body">
											<div class="text-center">
												<h3><span class="badge <?= $withdrawalStatusBadge ?> js_withdrawal-status"></span></h3>
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
													<td class="js_withdrawal-member-full-name"></td>
												</tr>
												<tr>
													<td>Nomor Hp</td>
													<td class="js_withdrawal-member-phone-number"></td>
												</tr>
												<tr>
													<td>Email</td>
													<td class="js_withdrawal-member-email"></td>
												</tr>
												</tbody>
											</table>
											<br/>
											<h3 class="card-title">Transfer Info</h3>
											<table class="table table-striped table-bordered table-condensed">
												<tbody>
												<tr>
													<td>Status</td>
													<td>
														<select name="wd_status" class="modal-select form-control" id="selectDepositStatusModal" style="display: none;width: 100%">
															<optgroup label="--Pilih Status WD--">
																<option value="<?= MemberWdStatus::WD_NEW ?>" <?= $withdrawalStatus == MemberWdStatus::WD_NEW ? "selected": "";?>>Withdrawal Baru</option>
																<option value="<?= MemberWdStatus::WD_PROCESSED ?>" <?= $withdrawalStatus == MemberWdStatus::WD_PROCESSED ? "selected": "";?> >Diproses</option>
																<option value="<?= MemberWdStatus::WD_CANCEL ?>" <?= $withdrawalStatus == MemberWdStatus::WD_CANCEL ? "selected": "";?> >Cancel</option>
															</optgroup>
														</select>
														<small class="text-danger"><?= form_error('wd_status')?></small>
													</td>
												</tr>
												<tr>
													<td>Kode Transaksi</td>
													<td class="js_withdrawal-code"></td>
												</tr>
												<tr>
													<td>Gateway</td>
													<td class="js_withdrawal-gateway"></td>
												</tr>
												<tr>
													<td>Nomor Rekening</td>
													<td class="js_withdrawal-gateway-number"></td>
												</tr>
												<tr>
													<td>Nama Pemilik Akun</td>
													<td class="js_withdrawal-gateway-account"></td>
												</tr>
												<tr>
													<td>Nominal WD</td>
													<td class="js_withdrawal-ammount"></td>
												</tr>
												</tbody>
											</table>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
											<button type="button" class="btn btn-primary js-form_action_btn">Update</button>
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
