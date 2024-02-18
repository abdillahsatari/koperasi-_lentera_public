<div class="profile-content">
	<?php if ($this->session->userdata("member_is_kyc") == false){?>
		<div class="alert alert-warning" role="alert">
			<h6 class="alert-heading">Dear, <?= $this->session->userdata("member_full_name")?></h6>
			<p>Mohon segera lakukan verifikasi data diri anda agar dapat menggunakan seluruh layanan koperasi lentera digital indonesia.</strong></p>
			<hr>
		</div>
	<?php }?>
	<?= $this->session->flashdata('message') ?>

	<div class="row">
		<div class="col-xl">
			<div class="card">
				<form action="<?= base_url('member/profile/password/update')?>" method="POST" class="js_member-profile-password-edit">
					<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
					<input type="hidden" name="suffix" value="reset_password">
					<div class="card-header">
						<h5>Ganti Password</h5>
					</div>
					<div class="card-body">
						<div class="form-row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="member_password">Masukkan Password</label>
									<input type="text" class="form-control" id="member_password" name="member_password" placeholder="password" value="">
									<small><?= form_error('member_password')?></small>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="member_password_retype">Ketik Ulang Password Baru</label>
									<input type="text" class="form-control" id="member_password_retype" name="member_password_retype" placeholder="password" value="">
									<small><?= form_error('member_password_retype')?></small>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<button type="button" class="btn btn-primary js-form_action_btn">Update</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xl">
			<div class="card">
				<form action="<?= base_url('member/profile/password/update')?>" method="POST" class="js_member-profile-transaction-code-edit">
					<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
					<input type="hidden" name="suffix" value="reset_transaction_code">
					<div class="card-header">
						<h5>Ganti Kode Transaksi</h5>
					</div>
					<div class="card-body">
						<div class="form-row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="member_transaction_code">Masukkan Kode Transaksi Baru</label>
									<input type="text" class="form-control" id="member_transaction_code" name="member_transaction_code" placeholder="Kode Transaksi" value="">
									<small><?= form_error('member_transaction_code')?></small>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="member_password_retype">Ketik Ulang Kode Transaksi Baru</label>
									<input type="text" class="form-control" id="member_transaction_code_retype" name="member_transaction_code_retype" placeholder="Kode Transaksi" value="">
									<small><?= form_error('member_transaction_code_retype')?></small>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<button type="button" class="btn btn-primary js-form_action_btn">Update</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xl">
			<div class="card">
				<form action="<?= base_url('member/profile/password/update')?>" method="POST" class="js_member-profile-bank-account-edit">
					<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
					<input type="hidden" name="suffix" value="set_member_bank_account_info">
					<div class="card-header">
						<h5>Edit Data Rekening</h5>
					</div>
					<div class="card-body">
						<div class="form-row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="member_transaction_code">Nama Bank</label>
									<input type="text" class="form-control" id="bank_account_name" name="bank_account_name" placeholder="BCA" value="<?= set_value("bank_account_name") ?: current($dataBankAccount)->bank_account_name?>">
									<small><?= form_error('bank_account_name')?></small>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="member_password_retype">No. Rekening</label>
									<input type="text" class="form-control" id="bank_account_number" name="bank_account_number" placeholder="92198374" value="<?= set_value("bank_account_number") ?: current($dataBankAccount)->bank_account_number?>">
									<small><?= form_error('bank_account_number')?></small>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="member_password_retype">Nama Pemilik Akun</label>
									<input type="text" class="form-control" id="bank_account_owner" name="bank_account_owner" placeholder="Name" value="<?= set_value("bank_account_owner") ?: current($dataBankAccount)->bank_account_owner?>">
									<small><?= form_error('bank_account_owner')?></small>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<button type="button" class="btn btn-primary js-form_action_btn">Update</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
