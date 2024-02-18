<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item active" aria-current="page">Withdrawal</li>
		</ol>
	</nav>
	<div class="page-options">
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Withdraw</button>

		<!-- Modal -->
		<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
				<div class="modal-content">
					<form action="<?=base_url('member/withdrawal/save'); ?>" method="POST" class="js_form-member-withdrawal"
					data-url="<?= base_url("member/MemberAjax/validateBalanceInputAmmount")?>">
						<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalCenterTitle">Deposit Baru</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<i class="material-icons">close</i>
							</button>
						</div>
						<div class="modal-body">
							<div class="col-lg-12">
								<div class="alert alert-warning" role="alert">
									<ul>
										<li>
											<p>
												Ketika Anda mengirim permintaan withdraw, saldo Anda akan berkurang secara otomatis sesuai jumlah withdraw. Admin akan melakukan konfirmasi untuk withdraw terlebih dahulu. Saldo Anda akan kembali apabila Admin membatalkan permintaan withdraw Anda. Dan saldo akan di kirim ke rekening Anda apabila withdraw sukses.
											</p>
										</li>
										<li>
											<p>
												Mohon pastikan anda telah mengisi info rekening bank Anda. Jika belum, klik <a href="<?= base_url('member/profile/password')?>">disini</a>
											</p>
										</li>
									</ul>
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail4">Saldo Dompet</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text btn btn-outline-primary" id="inputGroupPrepend">
											<div class="col-sm-1">
												Rp.
											</div>
										</span>
									</div>
									<input type="number" class="form-control" id="saldo_deposit" value="<?= current($this->FinanceModel->memberFinance($this->session->userdata("member_id")))->totalWalletBalance ?: 0?>"
										   aria-describedby="deposit_ammount" readonly disabled>
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail4">Nominal Withdraw</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text btn btn-outline-primary" id="inputGroupPrepend">
											<div class="col-sm-1">
												Rp.
											</div>
										</span>
									</div>
									<input type="number" class="form-control" id="wd_ammount" name="wd_ammount" value="<?= set_value('wd_ammount')?>" placeholder="50.000"
										   aria-describedby="deposit_ammount">
									<small class="text-danger"><?= form_error('wd_ammount')?></small>
								</div>
							</div>
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
					<?= $this->session->flashdata("message") ?>
					<table class="default-table display" style="width: 100%;">
						<thead>
						<tr>
							<th>No.</th>
							<th>Tgl Transaksi</th>
							<th>Kode Transaksi</th>
							<th>Jumlah</th>
							<th>Gateway</th>
							<th>Status</th>
						</tr>
						</thead>
						<tbody>
						<?php $no=1; foreach($dataWithdrawal as $data){
							switch ($data->wd_status){
								case MemberWdStatus::WD_NEW:
									$wdStatus = "Sedang di proses";
									break;
								case MemberWdStatus::WD_PROCESSED:
									$wdStatus = "Selesai";
									break;
								case MemberWdStatus::WD_CANCEL:
									$wdStatus = "Dibatalkan";
									break;
								case MemberWdStatus::WD_PENDING:
									$wdStatus = "Pending";
									break;
								default:
									$wdStatus = "Sedang di proses";
							}
							?>
							<tr>
								<td><?= $no++ ?></td>
								<td><?= date('d M Y ', strtotime($data->created_at)) ?></td>
								<td><?= $data->wd_transaction_code ?></td>
								<td class="js-currencyFormatter" data-price="<?= $data->wd_ammount?>"></td>
								<td><?= $data->bank_account_name ?></td>
								<td><?= $wdStatus ?></td>
							</tr>
						<?php }?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
