<div class="profile-content">
	<div class="row">
		<div class="col-xl">
			<div class="card">
				<div class="card-body">

					<?= $this->session->flashdata('message') ?>

					<form action="<?= base_url('admin-profile/update')?>" method="POST" class="js_admin-profile-edit">
						<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
						<input type="hidden" name="admin_kyc_id" value="<?= current($dataAdmin)->admin_kyc_id ?>">
						<div class="form-row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="inputEmail4">Email</label>
									<input type="email" class="form-control" id="admin_email" name="admin_email" placeholder="Email" value="<?= set_value('admin_email') ?: current($dataAdmin)->admin_email ?>" readonly>
									<small><?= form_error('admin_email')?></small>
								</div>
								<div class="form-group">
									<label for="inputEmail4">No. Hp</label>
									<input type="number" class="form-control" id="admin_phone_number" name="admin_phone_number" placeholder="No. Hp" value="<?= set_value('admin_phone_number') ?: current($dataAdmin)->admin_phone_number ?>">
									<small><?= form_error('admin_phone_number')?></small>
								</div>
								<div class="form-group">
									<label for="inputEmail4">No. KTP</label>
									<input type="number" class="form-control" id="admin_ktp_number" name="admin_ktp_number" placeholder="No. KTP" value="<?= set_value('admin_ktp_number') ?: current($dataAdmin)->admin_ktp_number ?>">
									<small><?= form_error('admin_ktp_number')?></small>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="admin_ktp_image" class="form-label">Upload KTP</label><br>
									<img src="<?= current($dataAdmin)->admin_ktp_image ? base_url('assets/resources/images/uploads/').current($dataAdmin)->admin_ktp_image : "https://via.placeholder.com/200x155.png?text=Preview" ?>" alt="ktp"
										 class="image-preview" width="100%" height="155">
									<br><br>
									<input type="file" class="form-control js-image_upload" accept=".png, .jpg, .jpeg" data-url-upload="<?= base_url('admin/adminAjax/postadminKtpImg');?>">
									<input type="hidden" name="admin_ktp_image" id="admin_ktp_image" value="<?= current($dataAdmin)->admin_ktp_image?>">
									<small><?= form_error('admin_ktp_image')?></small>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="inputEmail4">Nama Lengkap</label>
									<input type="text" class="form-control" id="admin_full_name" name="admin_full_name" placeholder="Nama Lengkap" value="<?= set_value('admin_full_name') ?: current($dataAdmin)->admin_full_name ?>">
									<small><?= form_error('admin_full_name')?></small>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="admin_birth_place">Tempat Lahir</label>
									<input type="text" class="form-control" id="admin_birth_place" name="admin_birth_place" placeholder="Tempat Lahir" value="<?= set_value('admin_birth_place') ?: current($dataAdmin)->admin_birth_place ?>">
									<small><?= form_error('admin_birth_place')?></small>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="admin_birth_date">Tanggal Lahir</label>
									<input type="datetime-local" class="form-control" id="admin_birth_date" name="admin_birth_date" placeholder="Tgl Lahir" value="<?= set_value('admin_birth_date') ? date("Y-m-d\TH:i:s", strtotime(set_value('admin_birth_date'))) : date("Y-m-d\TH:i:s", strtotime(current($dataAdmin)->admin_birth_date)) ?>">
									<small><?= form_error('admin_birth_date')?></small>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="admin_last_education">Pendidikan Terakhir</label>
									<input type="text" class="form-control" id="admin_last_education" name="admin_last_education" placeholder="Pendidikan Terakhir" value="<?= set_value('admin_last_education') ?: current($dataAdmin)->admin_last_education ?>">
									<small><?= form_error('admin_last_education')?></small>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="admin_job">Pekerjaan</label>
									<input type="text" class="form-control" id="admin_job" name="admin_job" placeholder="Pekerjaan" value="<?= set_value('admin_job') ?: current($dataAdmin)->admin_job ?>">
									<small><?= form_error('admin_job')?></small>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="admin_address">Alamat</label>
									<input type="text" class="form-control" id="admin_address" name="admin_address" placeholder="Alamat" value="<?= set_value('admin_address') ?: current($dataAdmin)->admin_address ?>">
									<small><?= form_error('admin_address')?></small>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="admin_kelurahan">Kelurahan</label>
									<input type="text" class="form-control" id="admin_kelurahan" name="admin_kelurahan" placeholder="Kelurahan" value="<?= set_value('admin_kelurahan') ?: current($dataAdmin)->admin_kelurahan ?>">
									<small><?= form_error('admin_kelurahan')?></small>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="admin_kecamatan">Kecamatan</label>
									<input type="text" class="form-control" id="admin_kecamatan" name="admin_kecamatan" placeholder="Kecamatan" value="<?= set_value('admin_kecamatan') ?: current($dataAdmin)->admin_kecamatan ?>">
									<small><?= form_error('admin_kecamatan')?></small>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="admin_kota">Kabupaten / Kota</label>
									<input type="text" class="form-control" id="admin_kota" name="admin_kota" placeholder="Kabupaten / Kota" value="<?= set_value('admin_kota') ?: current($dataAdmin)->admin_kota ?>">
									<small><?= form_error('admin_kota')?></small>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="admin_provinsi">Provinsi</label>
									<input type="text" class="form-control" id="admin_provinsi" name="admin_provinsi" placeholder="Provinsi" value="<?= set_value('admin_provinsi') ?: current($dataAdmin)->admin_provinsi ?>">
									<small><?= form_error('admin_provinsi')?></small>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for="admin_kode_pos">Kode Pos</label>
									<input type="number" class="form-control" id="admin_kode_pos" name="admin_kode_pos" placeholder="Kode Pos" value="<?= set_value('admin_kode_pos') ?: current($dataAdmin)->admin_kode_pos ?>">
									<small><?= form_error('admin_kode_pos')?></small>
								</div>
							</div>
						</div>
						<button type="button" class="btn btn-primary js-form_action_btn">Update</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
