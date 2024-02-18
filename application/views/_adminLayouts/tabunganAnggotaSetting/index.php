<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item"><a href="#">Tabungan</a></li>
			<li class="breadcrumb-item active" aria-current="page">Pengaturan Program Tabungan</li>
		</ol>
	</nav>
</div>
<div class="main-wrapper">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card">
				<div class="card-header">
					<h5>Form Pengaturan Program Tabungan</h5>
				</div>
				<form action="<?= base_url('admin/tabungan/pengaturan-program/update')?>" method="POST" class="js_admin-post-tabungan-anggota-setting">
					<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
					<input type="hidden" name="id" value="<?= $dataTabunganSetting->id ?>">
					<div class="card-body">
						<?= $this->session->flashdata('message') ?>
						<div class="form-row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="required_ammount">Min. Tabungan</label>
									<div class="input-group">
										<div class="input-group-prepend">
										<span class="input-group-text btn btn-outline-primary" id="inputGroupPrepend">
											<div class="col-sm-1">
												Rp.
											</div>
										</span>
										</div>
										<input type="number" class="form-control" id="required_ammount" name="required_ammount" placeholder="10.000" value="<?= set_value('required_ammount') ?: $dataTabunganSetting->required_ammount ?>">
										<small><?= form_error('required_ammount')?></small>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="interest_percentage">Persentase Imbal Jasa</label>
									<div class="input-group">
										<input type="number" class="form-control js-input-group" id="interest_percentage" name="interest_percentage" placeholder="1" value="<?= set_value('interest_percentage') ?: $dataTabunganSetting->interest_percentage ?>">
										<div class="input-group-append">
											<span class="input-group-text btn btn-outline-primary" id="inputGroupPrepend">
												<div class="col-sm-1">
													%
												</div>
											</span>
										</div>
									</div>
									<small class="js_input-error-placement"><?= form_error('interest_percentage')?></small>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="admin_fee">Biaya Admin</label>
									<div class="input-group">
										<div class="input-group-prepend">
										<span class="input-group-text btn btn-outline-primary" id="inputGroupPrepend">
											<div class="col-sm-1">
												Rp.
											</div>
										</span>
										</div>
										<input type="number" class="form-control" id="admin_fee" name="admin_fee" placeholder="2.000" value="<?= set_value('admin_fee') ?: $dataTabunganSetting->admin_fee ?>">
										<small><?= form_error('admin_fee')?></small>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<a href="javascript:window.history.go(-1);" class="btn btn-light">Kembali</a>
						<button type="button" class="btn btn-primary js-form_action_btn">Update</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
