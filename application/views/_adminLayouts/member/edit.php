<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item"><a href="#">Keanggotaan</a></li>
			<li class="breadcrumb-item active" aria-current="page">Edit Anggota Koperasi</li>
		</ol>
	</nav>
</div>
<div class="main-wrapper">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card">
				<div class="card-header">
					<h5>Form Edit Data Anggota Koperasi</h5>
				</div>
				<div class="card-body">
					<?= $this->session->flashdata('message') ?>

					<form action="<?= base_url('admin/keanggotaan/update') ?>" method="POST" class="js_admin-post-member">
						<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
						<input type="hidden" name="member_id" value="<?= $dataMember->member_id?>">
						<input type="hidden" name="member_kyc_id" value="<?= $dataMember->member_kyc_id?>">
						<div class="form-row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="inputEmail4">Email</label>
									<input type="email" class="form-control" id="member_email" name="member_email" placeholder="Email" value="<?= set_value('member_email') ?: $dataMember->member_email ?>" >
									<small class="text-danger"><?= form_error('member_email')?></small>
								</div>
								<div class="form-group">
									<label for="inputEmail4">No. Hp</label>
									<input type="number" class="form-control" id="member_phone_number" name="member_phone_number" placeholder="No. Hp" value="<?= set_value('member_phone_number') ?: $dataMember->member_phone_number ?>">
									<small class="text-danger"><?= form_error('member_phone_number')?></small>
								</div>
								<div class="form-group">
									<label for="inputEmail4">No. KTP</label>
									<input type="number" class="form-control" id="member_ktp_number" name="member_ktp_number" placeholder="No. KTP" value="<?= set_value('member_ktp_number') ?: $dataMember->member_ktp_number ?>">
									<small class="text-danger"><?= form_error('member_ktp_number')?></small>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="member_ktp_image" class="form-label">Upload KTP</label><br>
									<img src="<?= $dataMember->member_ktp_image ? base_url('assets/resources/images/uploads/').$dataMember->member_ktp_image : "https://via.placeholder.com/200x225.png?text=Preview" ?>" alt="ktp"
										 class="image-preview" width="100%" height="155">
									<br><br>
									<input type="file" class="form-control js-image_upload" accept=".png, .jpg, .jpeg" data-url-upload="<?= base_url('admin/AdminAjax/postMemberKtpImg');?>">
									<input type="hidden" name="member_ktp_image" id="member_ktp_image" value="<?= $dataMember->member_ktp_image?>">
									<small class="text-danger"><?= form_error('member_ktp_image')?></small>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="inputEmail4">Nama Lengkap</label>
									<input type="text" class="form-control" id="member_full_name" name="member_full_name" placeholder="Nama Lengkap" value="<?= set_value('member_full_name') ?: $dataMember->member_full_name ?>">
									<small class="text-danger"><?= form_error('member_full_name')?></small>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="member_birth_place">Tempat Lahir</label>
									<input type="text" class="form-control" id="member_birth_place" name="member_birth_place" placeholder="Tempat Lahir" value="<?= set_value('member_birth_place') ?: $dataMember->member_birth_place ?>">
									<small class="text-danger"><?= form_error('member_birth_place')?></small>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="member_birth_date">Tanggal Lahir</label>
									<input type="date" class="form-control" id="member_birth_date" name="member_birth_date" placeholder="Tgl Lahir" value="<?= set_value('member_birth_date') ? date("Y-m-d\TH:i:s", strtotime(set_value('member_birth_date'))) : date("Y-m-d\TH:i:s", strtotime($dataMember->member_birth_date)) ?>">
									<small class="text-danger"><?= form_error('member_birth_date')?></small>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="member_last_education">Pendidikan Terakhir</label>
									<input type="text" class="form-control" id="member_last_education" name="member_last_education" placeholder="Pendidikan Terakhir" value="<?= set_value('member_last_education') ?: $dataMember->member_last_education ?>">
									<small class="text-danger"><?= form_error('member_last_education')?></small>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="member_job">Pekerjaan</label>
									<input type="text" class="form-control" id="member_job" name="member_job" placeholder="Pekerjaan" value="<?= set_value('member_job') ?: $dataMember->member_job ?>">
									<small class="text-danger"><?= form_error('member_job')?></small>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="member_address">Alamat</label>
									<input type="text" class="form-control" id="member_address" name="member_address" placeholder="Alamat" value="<?= set_value('member_address') ?: $dataMember->member_address ?>">
									<small class="text-danger"><?= form_error('member_address')?></small>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="member_kelurahan">Kelurahan</label>
									<input type="text" class="form-control" id="member_kelurahan" name="member_kelurahan" placeholder="Kelurahan" value="<?= set_value('member_kelurahan') ?: $dataMember->member_kelurahan ?>">
									<small class="text-danger"><?= form_error('member_kelurahan')?></small>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="member_kecamatan">Kecamatan</label>
									<input type="text" class="form-control" id="member_kecamatan" name="member_kecamatan" placeholder="Kecamatan" value="<?= set_value('member_kecamatan') ?: $dataMember->member_kecamatan ?>">
									<small class="text-danger"><?= form_error('member_kecamatan')?></small>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="member_kota">Kabupaten / Kota</label>
									<input type="text" class="form-control" id="member_kota" name="member_kota" placeholder="Kabupaten / Kota" value="<?= set_value('member_kota') ?: $dataMember->member_kota ?>">
									<small class="text-danger"><?= form_error('member_kota')?></small>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="member_provinsi">Provinsi</label>
									<input type="text" class="form-control" id="member_provinsi" name="member_provinsi" placeholder="Provinsi" value="<?= set_value('member_provinsi') ?: $dataMember->member_provinsi ?>">
									<small class="text-danger"><?= form_error('member_provinsi')?></small>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for="member_kode_pos">Kode Pos</label>
									<input type="number" class="form-control" id="member_kode_pos" name="member_kode_pos" placeholder="Kode Pos" value="<?= set_value('member_kode_pos') ?: $dataMember->member_kode_pos ?>">
									<small class="text-danger"><?= form_error('member_kode_pos')?></small>
								</div>
							</div>
						</div>
						<a href="javascript:window.history.go(-1);" class="btn btn-light">Kembali</a>
						<button type="button" class="btn btn-primary js-form_action_btn">Update</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
