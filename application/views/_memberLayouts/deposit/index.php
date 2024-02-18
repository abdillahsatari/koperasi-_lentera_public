<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item active" aria-current="page">Deposit</li>
		</ol>
	</nav>
	<div class="page-options">
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Tambah Deposit</button>

		<!-- Modal -->
		<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<form action="<?=base_url('member-deposit/save'); ?>" method="POST" class="js_form-member-deposit">
						<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalCenterTitle">Deposit Baru</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<i class="material-icons">close</i>
							</button>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<label for="inputEmail4">Pilih Payment Gateway</label>
								<select name="deposit_lentera_bank_id" class="modal-select form-control" style="display: none;width: 100%">
									<optgroup label="--Payment Gateway--">
										<?php foreach($dataPaymentGateway as $data){?>
										<option value="<?= $data->id ?>"><?= $data->nama_bank ?></option>
										<?php }?>
									</optgroup>
								</select>
								<small class="text-danger"><?= form_error('deposit_lentera_bank_id')?></small>
							</div>
							<div class="form-group">
								<label for="inputEmail4">Nominal Deposit</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text btn btn-outline-primary" id="inputGroupPrepend">
											<div class="col-sm-1">
												Rp.
											</div>
										</span>
									</div>
									<input type="number" class="form-control" id="deposit_ammount" name="deposit_ammount" value="<?= set_value('deposit_ammount')?>" placeholder="500.000"
										   aria-describedby="deposit_ammount">
									<small class="text-danger"><?= form_error('deposit_ammount')?></small>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary js-form_action_btn">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- ./Modal -->
	</div>
</div>
<div class="main-wrapper">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card">
				<div class="card-header">
					<h5>deposit history</h5>
				</div>
				<div class="card-body">
					<table class="default-table display" style="width: 100%;">
						<thead>
						<tr>
							<th>Tgl Transaksi</th>
							<th>Kode Transaksi</th>
							<th>Jumlah</th>
							<th>Kode Unik</th>
							<th>Total Transfer</th>
							<th>kode Transfer</th>
							<th>Bank</th>
							<th>Keterangan</th>
							<th>Aksi</th>
						</tr>
						</thead>
						<tbody>
						<?php foreach($dataDeposit as $deposit){
							$depositId = $deposit->id;
							$depositStatus = $deposit->deposit_status;
							$depositStatusBadge="";
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
							<td><?= date('d M Y ', strtotime($deposit->created_at)) ?></td>
							<td><?= $deposit->deposit_transaction_code ?></td>
							<td class="js-currencyFormatter" data-price="<?= $deposit->deposit_ammount?>"></td>
							<td><?= $deposit->deposit_last_code ?></td>
							<td class="js-currencyFormatter" data-price="<?= $deposit->deposit_ammount?>"></td>
							<td><?= $deposit->deposit_code ?></td>
							<td><?= $deposit->nama_bank ?></td>
							<td><?= $deposit->deposit_status == MemberDepositStatus::DEPOSIT_NEW ? "Menunggu Konifrmasi" : $deposit->deposit_status ?></td>
							<td>
								<button type="button" class="btn btn-primary" id="js_member-deposit-detail" data-deposit-id="<?= $depositId ?>" data-toggle="modal" data-target="#detailDepositModal">Detail</button>
							</td>
						</tr>
						<?php }?>
						</tbody>
					</table>

					<?php if ($dataDeposit){?>
					<!-- Modal -->
					<div class="modal fade js_member-deposit-detail" id="detailDepositModal" tabindex="-1" role="dialog" aria-labelledby="detailDepositModal" aria-hidden="true"
						data-url="<?=base_url('member/MemberAjax/showMemberDeposit/'); ?>"
						data-deposit-id="<?= $depositId?>">
						<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
							<div class="modal-content" id="block-modal">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalCenterTitle">Detail Deposit</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<i class="material-icons">close</i>
									</button>
								</div>
								<div class="modal-body">
									<div class="text-center">
										<h3><span class="badge" id="js_deposit-status-badge">-- Statusnya --</span></h3>
									</div>
									<table class="table table-striped table-bordered table-condensed">
										<tbody>
											<tr>
												<td>Kode Deposit</td>
												<td class="js_deposit-code"> -- Kodenya --</td>
											</tr>
											<tr>
												<td>Gateway</td>
												<td class="js_deposit-gateway">-- Nama Bank --</td>
											</tr>
											<tr>
												<td>No. Rekening</td>
												<td class="js_deposit-gateway-number">-- Nomor Rekening --</td>
											</tr>
											<tr>
												<td>Nama</td>
												<td class="js_deposit-gateway-account">-- Nama Pemilik Account --</td>
											</tr>
											<tr>
												<td>Nilai</td>
												<td class="js_deposit-ammount">-- deposit ammount --</td>
											</tr>
										</tbody>
									</table>
									<br/>
									<?php if ($depositStatus == MemberDepositStatus::DEPOSIT_NEW) {?>
										<div class="text-center">
											<span>Mohon melakukan transfer ke <strong class="js_gateway-full-info"> </strong></span><br/>
											<span>Gunakan kode unik <strong class="js_last-code"> </strong> untuk memudahkan proses verifikasi.</span><br/>
											<span>Total transfer Anda yaitu <strong class="js_deposit-ammount"></strong>.</span><br/>
											<span>Mohon masukkan kode deposit <strong class="js_deposit-code"> </strong> dan No. Anggota <strong class="js_member-uid"> </strong> pada deskripsi.</span><br/>
											<span>Apabila deposit tidak di verifikasi dalam 1x24 jam (jam kerja), mohon hubungi via email <strong>cs@lenteradigitalindonesia.com</strong> atau via Whatsapp <strong>+6285732598556</strong>.</span>
										</div>
									<?php }?>
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
