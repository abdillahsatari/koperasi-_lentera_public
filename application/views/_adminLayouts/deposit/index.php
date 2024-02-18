<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item"><a href="#">Deposit</a></li>
			<li class="breadcrumb-item active" aria-current="page"></li>
			<?php
			switch ($pageType){
				case strtolower(MemberDepositStatus::DEPOSIT_NEW):
					echo "Deposit Baru";
					break;
				case strtolower(MemberDepositStatus::DEPOSIT_PROCESSED):
					echo "Deposit Telah Diproses";
					break;
				case strtolower(MemberDepositStatus::DEPOSIT_PENDING):
					echo "Deposit Dipending";
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
								case strtolower(MemberDepositStatus::DEPOSIT_NEW):
									echo "Deposit Baru";
									break;
								case strtolower(MemberDepositStatus::DEPOSIT_PROCESSED):
									echo "Deposit Telah Diproses";
									break;
								case strtolower(MemberDepositStatus::DEPOSIT_PENDING):
									echo "Deposit Dipending";
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
							<th>Anggota</th>
							<th>Jumlah</th>
							<th>Kode Unik</th>
							<th>Total Transfer</th>
							<th>Gateway</th>
							<th>Kode Deposit</th>
							<th>Tgl Deposit</th>
							<th>Status</th>
							<?php if ($pageType == strtolower(MemberDepositStatus::DEPOSIT_NEW)) {?>
								<th>Aksi</th>
							<?php }?>
						</tr>
						</thead>
						<tbody>
						<?php

						$depositStatus="";
						$depositStatusBadge="";

						foreach($dataDeposit as $deposit){
							$depositStatus = $deposit->deposit_status;

							switch ($deposit->deposit_status){
								case MemberDepositStatus::DEPOSIT_NEW:
									$depositStatusBadge = 'badge-info';
									break;
								case MemberDepositStatus::DEPOSIT_PROCESSED:
									$depositStatusBadge = 'badge-primary';
									break;
								case MemberDepositStatus::DEPOSIT_PENDING:
									$depositStatusBadge = 'badge-light';
									break;
								case MemberDepositStatus::DEPOSIT_CANCEL:
									$depositStatusBadge = 'badge-danger';
									break;
							}

							$totalAmmount = $deposit->deposit_ammount + $deposit->deposit_last_code;
							?>
							<tr>
								<td>
									<strong>
										<span class="text-info"><?= $deposit->member_referal_code ?></span>
									</strong>
									<br/>
									<?= $deposit->member_full_name ?>
								</td>
								<td class="js-currencyFormatter" data-price="<?= $deposit->deposit_ammount?>"></td>
								<td><?= $deposit->deposit_last_code ?></td>
								<td class="js-currencyFormatter" data-price="<?= $deposit->deposit_ammount + $deposit->deposit_last_code ?>"></td>
								<td><?= $deposit->nama_bank ?></td>
								<td><?= $deposit->deposit_code ?></td>
								<td><?= date('d M Y ', strtotime($deposit->created_at)) ?></td>
								<td>
									<span class="badge <?= $depositStatusBadge ?> js_deposit-status">
										<?= $depositStatus == MemberDepositStatus::DEPOSIT_NEW ? "Deposit Baru" : $deposit->deposit_status ?>
									</span>
								</td>
								<?php if ($pageType == strtolower(MemberDepositStatus::DEPOSIT_NEW)) {?>
									<td>
										<button type="button" class="btn btn-primary btn-sm" id="js_member-deposit-detail" data-id="<?= $deposit->id ?>" data-toggle="modal" data-target="#exampleModal">Detail</button>
									</td>
								<?php }?>
							</tr>
						<?php }?>
						</tbody>
					</table>

					<?php if ($pageType == strtolower(MemberDepositStatus::DEPOSIT_NEW)) {?>
						<!-- Modal -->
						<div class="modal fade js_show-admin-member-deposit-detail" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalDetail" aria-hidden="true"
							 data-url="<?=base_url('admin/AdminAjax/showMemberDeposit/'); ?>">
							<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
								<div class="modal-content" id="block-modal">
									<form action="<?= base_url('admin/deposit-update')?>" method="POST" class="js_form-admin-member-deposit">
										<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
										<input type="hidden" name="suffix" value="<?= $depositStatus ?>">
										<input type="hidden" name="deposit_id" value="">
										<div class="modal-header">
											<h5 class="modal-title" id="exampleModalCenterTitle">Detail Deposit</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<i class="material-icons">close</i>
											</button>
										</div>
										<div class="modal-body">
											<div class="text-center">
												<h3><span class="badge <?= $depositStatusBadge ?> js_deposit-status"></span></h3>
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
													<td class="js_deposit-member-full-name"></td>
												</tr>
												<tr>
													<td>Nomor Hp</td>
													<td class="js_deposit-member-phone-number"></td>
												</tr>
												<tr>
													<td>Email</td>
													<td class="js_deposit-member-email"></td>
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
														<select name="deposit_status" class="modal-select form-control" id="selectDepositStatusModal" style="display: none;width: 100%">
															<optgroup label="--Pilih Status Deposit--">
																<option value="<?= MemberDepositStatus::DEPOSIT_NEW ?>" <?= $depositStatus == MemberDepositStatus::DEPOSIT_NEW ? "selected": "";?>>Deposit Baru</option>
																<option value="<?= MemberDepositStatus::DEPOSIT_PROCESSED ?>" <?= $depositStatus == MemberDepositStatus::DEPOSIT_PROCESSED ? "selected": "";?> >Diproses</option>
																<option value="<?= MemberDepositStatus::DEPOSIT_PENDING ?>" <?= $depositStatus == MemberDepositStatus::DEPOSIT_PENDING ? "selected": "";?> >Pending</option>
																<option value="<?= MemberDepositStatus::DEPOSIT_CANCEL ?>" <?= $depositStatus == MemberDepositStatus::DEPOSIT_CANCEL ? "selected": "";?> >Cancel</option>
															</optgroup>
														</select>
														<small class="text-danger"><?= form_error('deposit_status')?></small>
													</td>
												</tr>
												<tr>
													<td>Kode Deposit</td>
													<td class="js_deposit-code"></td>
												</tr>
												<tr>
													<td>Gateway</td>
													<td class="js_deposit-gateway"></td>
												</tr>
												<tr>
													<td>Nomor Rekening</td>
													<td class="js_deposit-gateway-number"></td>
												</tr>
												<tr>
													<td>Nama Pemilik Akun</td>
													<td class="js_deposit-gateway-account"></td>
												</tr>
												<tr>
													<td>Kode Unik</td>
													<td class="js_deposit-last-code"></td>
												</tr>
												<tr>
													<td>Total Transfer</td>
													<td class="js_deposit-ammount"></td>
												</tr>
												<tr>
													<td>Actual Ammount</td>
													<td class="js_deposit-actual-ammount"></td>
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
