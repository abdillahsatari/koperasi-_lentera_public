<div class="profile-content">
	<?php if ($this->session->userdata("admin_is_kyc") == false){?>
		<div class="alert alert-warning" role="alert">
			<h6 class="alert-heading">Dear, <?= $this->session->userdata("admin_full_name")?></h6>
			<p>Mohon segera lakukan verifikasi data diri anda.</strong></p>
			<hr>
		</div>
	<?php }?>
	<?= $this->session->flashdata('message') ?>

	<div class="row">
		<div class="col-xl">
			<div class="card">
				<form action="<?= base_url('pengurus/profile/password/update')?>" method="POST" class="js_admin-profile-password-edit">
					<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
					<input type="hidden" name="suffix" value="reset_password">
					<div class="card-header">
						<h5>Ganti Password</h5>
					</div>
					<div class="card-body">
						<div class="form-row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="admin_password">Masukkan Password</label>
									<input type="text" class="form-control" id="admin_password" name="admin_password" placeholder="password" value="">
									<small><?= form_error('admin_password')?></small>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="admin_password_retype">Ketik Ulang Password Baru</label>
									<input type="text" class="form-control" id="admin_password_retype" name="admin_password_retype" placeholder="password" value="">
									<small><?= form_error('admin_password_retype')?></small>
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
				<form action="<?= base_url('pengurus/profile/password/update')?>" method="POST" class="js_admin-profile-transaction-code-edit">
					<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
					<input type="hidden" name="suffix" value="reset_transaction_code">
					<div class="card-header">
						<h5>Ganti Kode Transaksi</h5>
					</div>
					<div class="card-body">
						<div class="form-row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="admin_transaction_code">Masukkan Kode Transaksi Baru</label>
									<input type="text" class="form-control" id="admin_transaction_code" name="admin_transaction_code" placeholder="Kode Transaksi" value="">
									<small><?= form_error('admin_transaction_code')?></small>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="admin_transaction_code_retype">Ketik Ulang Kode Transaksi Baru</label>
									<input type="text" class="form-control" id="admin_transaction_code_retype" name="admin_transaction_code_retype" placeholder="Kode Transaksi" value="">
									<small><?= form_error('admin_transaction_code_retype')?></small>
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
