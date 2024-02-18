<div class="profile-content">
	<div class="row">
		<div class="col-xl">
			<div class="card">
				<div class="card-body">
					<?php if (!current($dataMember)->member_is_kyc){?>
						<div class="alert alert-warning" role="alert">
							<h6 class="alert-heading">Dear, <?= current($dataMember)->member_full_name?></h6>
							<p>Silahkan lakukan verifikasi data dengan melengkapi informasi diri anda di bawah ini.</p>
							<hr>
						</div> <br/>
					<?php }?>

					<?= $this->session->flashdata('message') ?>

					<div class="form-row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="inputEmail4">Email</label>
								<input type="email" class="form-control" id="member_email" name="member_email" placeholder="Email" value="<?= set_value('member_email') ?: current($dataMember)->member_email ?>" disabled readonly>
								<small><?= form_error('member_email')?></small>
							</div>
							<div class="form-group">
								<label for="inputEmail4">No. Hp</label>
								<input type="number" class="form-control" id="member_phone_number" name="member_phone_number" placeholder="No. Hp" value="<?= set_value('member_phone_number') ?: current($dataMember)->member_phone_number ?>" disabled readonly>
								<small><?= form_error('member_phone_number')?></small>
							</div>
							<div class="form-group">
								<label for="inputEmail4">No. KTP</label>
								<input type="number" class="form-control" id="member_ktp_number" name="member_ktp_number" placeholder="No. KTP" value="<?= set_value('member_ktp_number') ?: current($dataMember)->member_ktp_number ?>" disabled readonly>
								<small><?= form_error('member_ktp_number')?></small>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="member_ktp_image" class="form-label">Upload KTP</label><br>
								<img src="<?= current($dataMember)->member_ktp_image ? base_url('assets/resources/images/uploads/').current($dataMember)->member_ktp_image : "https://via.placeholder.com/200x225.png?text=Preview" ?>" alt="ktp"
									 class="image-preview" width="100%" height="225">
								<br><br>
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="inputEmail4">Nama Lengkap</label>
								<input type="text" class="form-control" id="member_full_name" name="member_full_name" placeholder="Nama Lengkap" value="<?= set_value('member_full_name') ?: current($dataMember)->member_full_name ?>" disabled readonly>
								<small><?= form_error('member_full_name')?></small>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="member_birth_place">Tempat Lahir</label>
								<input type="text" class="form-control" id="member_birth_place" name="member_birth_place" placeholder="Tempat Lahir" value="<?= set_value('member_birth_place') ?: current($dataMember)->member_birth_place ?>" disabled readonly>
								<small><?= form_error('member_birth_place')?></small>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="member_birth_date">Tanggal Lahir</label>
								<input type="datetime-local" class="form-control" id="member_birth_date" name="member_birth_date" placeholder="Tgl Lahir" value="<?= set_value('member_birth_date') ? date("Y-m-d\TH:i:s", strtotime(set_value('member_birth_date'))) : date("Y-m-d\TH:i:s", strtotime(current($dataMember)->member_birth_date)) ?>" disabled readonly>
								<small><?= form_error('member_birth_date')?></small>
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="member_last_education">Pendidikan Terakhir</label>
								<input type="text" class="form-control" id="member_last_education" name="member_last_education" placeholder="Pendidikan Terakhir" value="<?= set_value('member_last_education') ?: current($dataMember)->member_last_education ?>" disabled readonly>
								<small><?= form_error('member_last_education')?></small>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="member_job">Pekerjaan</label>
								<input type="text" class="form-control" id="member_job" name="member_job" placeholder="Pekerjaan" value="<?= set_value('member_job') ?: current($dataMember)->member_job ?>" disabled readonly>
								<small><?= form_error('member_job')?></small>
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="member_address">Alamat</label>
								<input type="text" class="form-control" id="member_address" name="member_address" placeholder="Alamat" value="<?= set_value('member_address') ?: current($dataMember)->member_address ?>" disabled readonly>
								<small><?= form_error('member_address')?></small>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="member_kelurahan">Kelurahan</label>
								<input type="text" class="form-control" id="member_kelurahan" name="member_kelurahan" placeholder="Kelurahan" value="<?= set_value('member_kelurahan') ?: current($dataMember)->member_kelurahan ?>" disabled readonly>
								<small><?= form_error('member_kelurahan')?></small>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="member_kecamatan">Kecamatan</label>
								<input type="text" class="form-control" id="member_kecamatan" name="member_kecamatan" placeholder="Kecamatan" value="<?= set_value('member_kecamatan') ?: current($dataMember)->member_kecamatan ?>" disabled readonly>
								<small><?= form_error('member_kecamatan')?></small>
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="member_kota">Kabupaten / Kota</label>
								<input type="text" class="form-control" id="member_kota" name="member_kota" placeholder="Kabupaten / Kota" value="<?= set_value('member_kota') ?: current($dataMember)->member_kota ?>" disabled readonly>
								<small><?= form_error('member_kota')?></small>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="member_provinsi">Provinsi</label>
								<input type="text" class="form-control" id="member_provinsi" name="member_provinsi" placeholder="Provinsi" value="<?= set_value('member_provinsi') ?: current($dataMember)->member_provinsi ?>" disabled readonly>
								<small><?= form_error('member_provinsi')?></small>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="member_kode_pos">Kode Pos</label>
								<input type="number" class="form-control" id="member_kode_pos" name="member_kode_pos" placeholder="Kode Pos" value="<?= set_value('member_kode_pos') ?: current($dataMember)->member_kode_pos ?>" disabled readonly>
								<small><?= form_error('member_kode_pos')?></small>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
