<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item"><a href="#">Pengurus</a></li>
			<li class="breadcrumb-item active" aria-current="page">
				Tambah Pengurus
			</li>
		</ol>
	</nav>
</div>
<div class="main-wrapper">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card">
				<div class="card-header">
					<h5>Form Tambah Pengurus Baru</h5>
				</div>
				<div class="card-body">
					<?= $this->session->flashdata('message') ?>

					<form action="<?= base_url('admin/pengurus/save')?>" method="POST" class="js_admin-pengurus-post">
						<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
						<div class="form-row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="inputEmail4">Email</label>
									<input type="email" class="form-control" id="admin_email" name="admin_email" placeholder="Email" value="<?= set_value('admin_email')?>">
									<small class="text-danger"><?= form_error('admin_email')?></small>
								</div>
								<div class="form-group">
									<label for="inputEmail4">No. Hp</label>
									<input type="number" class="form-control" id="admin_phone_number" name="admin_phone_number" placeholder="No. Hp" value="<?= set_value('admin_phone_number') ?>">
									<small class="text-danger"><?= form_error('admin_phone_number')?></small>
								</div>
								<div class="form-group">
									<label for="inputEmail4">No. KTP</label>
									<input type="number" class="form-control" id="admin_ktp_number" name="admin_ktp_number" placeholder="No. KTP" value="<?= set_value('admin_ktp_number') ?>">
									<small class="text-danger"><?= form_error('admin_ktp_number')?></small>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="admin_ktp_image" class="form-label">Upload KTP</label><br>
									<img src="https://via.placeholder.com/200x155.png?text=Preview" alt="ktp"
										 class="image-preview" width="100%" height="155">
									<br><br>
									<input type="file" class="form-control js-image_upload" accept=".png, .jpg, .jpeg" data-url-upload="<?= base_url('admin/adminAjax/postadminKtpImg');?>">
									<input type="hidden" name="admin_ktp_image" id="admin_ktp_image">
									<small class="text-danger"><?= form_error('admin_ktp_image')?></small>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="inputEmail4">Nama Lengkap</label>
									<input type="text" class="form-control" id="admin_full_name" name="admin_full_name" placeholder="Nama Lengkap" value="<?= set_value('admin_full_name') ?>">
									<small class="text-danger"><?= form_error('admin_full_name')?></small>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="admin_birth_place">Tempat Lahir</label>
									<input type="text" class="form-control" id="admin_birth_place" name="admin_birth_place" placeholder="Tempat Lahir" value="<?= set_value('admin_birth_place') ?>">
									<small class="text-danger"><?= form_error('admin_birth_place')?></small>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="admin_birth_date">Tanggal Lahir</label>
									<input type="datetime-local" class="form-control" id="admin_birth_date" name="admin_birth_date" placeholder="Tgl Lahir" value="<?= set_value('admin_birth_date') ? date("Y-m-d\TH:i:s", strtotime(set_value('admin_birth_date'))) : "" ?>">
									<small class="text-danger"><?= form_error('admin_birth_date')?></small>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="admin_last_education">Pendidikan Terakhir</label>
									<input type="text" class="form-control" id="admin_last_education" name="admin_last_education" placeholder="Pendidikan Terakhir" value="<?= set_value('admin_last_education') ?>">
									<small class="text-danger"><?= form_error('admin_last_education')?></small>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="admin_job">Pekerjaan</label>
									<input type="text" class="form-control" id="admin_job" name="admin_job" placeholder="Pekerjaan" value="<?= set_value('admin_job') ?>">
									<small class="text-danger"><?= form_error('admin_job')?></small>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="admin_address">Alamat</label>
									<input type="text" class="form-control" id="admin_address" name="admin_address" placeholder="Alamat" value="<?= set_value('admin_address') ?>">
									<small class="text-danger"><?= form_error('admin_address')?></small>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="admin_kelurahan">Kelurahan</label>
									<input type="text" class="form-control" id="admin_kelurahan" name="admin_kelurahan" placeholder="Kelurahan" value="<?= set_value('admin_kelurahan') ?>">
									<small><?= form_error('admin_kelurahan')?></small>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="admin_kecamatan">Kecamatan</label>
									<input type="text" class="form-control" id="admin_kecamatan" name="admin_kecamatan" placeholder="Kecamatan" value="<?= set_value('admin_kecamatan') ?>">
									<small class="text-danger"><?= form_error('admin_kecamatan')?></small>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="admin_kota">Kabupaten / Kota</label>
									<input type="text" class="form-control" id="admin_kota" name="admin_kota" placeholder="Kabupaten / Kota" value="<?= set_value('admin_kota') ?>">
									<small class="text-danger"><?= form_error('admin_kota')?></small>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="admin_provinsi">Provinsi</label>
									<input type="text" class="form-control" id="admin_provinsi" name="admin_provinsi" placeholder="Provinsi" value="<?= set_value('admin_provinsi') ?>">
									<small class="text-danger"><?= form_error('admin_provinsi')?></small>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for="admin_kode_pos">Kode Pos</label>
									<input type="number" class="form-control" id="admin_kode_pos" name="admin_kode_pos" placeholder="Kode Pos" value="<?= set_value('admin_kode_pos') ?>">
									<small class="text-danger"><?= form_error('admin_kode_pos')?></small>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="admin_role_id">Pilih Admin Role</label>
									<select name="admin_role_id" id="js_status-gateway" class="single-select form-control js-input-with-plugin" style="display: none;width: 100%">
										<optgroup label="--Pilih Satatus Pengurus--">
											<?php
											$dataRole = $dataAdminRole;
											if ($this->session->userdata("admin_role_type") != AdminRoleType::ADMIN) {
												$dataRole = array_slice($dataRole, 1);
											}

											foreach($dataRole as $role) {?>
											<option value="<?= $role->id ?>"><?= $role->role_type?></option>
											<?php }?>
										</optgroup>
									</select>
									<small class="text-danger js_input-error-placement"><?= form_error('admin_role_id')?></small>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="admin_status">Pilih Status Admin</label>
									<select name="admin_status" id="js_admin_status" class="single-select form-control js-input-with-plugin" style="display: none;width: 100%">
										<optgroup label="--Pilih Satatus Pengurus--">
											<option value="1">Aktif</option>
											<option value="0">Non-Aktif</option>
										</optgroup>
									</select>
									<small class="text-danger js_input-error-placement"><?= form_error('admin_status')?></small>
								</div>
							</div>
						</div>
						<a href="javascript:window.history.go(-1);" class="btn btn-light">Kembali</a>
						<button type="button" class="btn btn-primary js-form_action_btn">Submit</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
