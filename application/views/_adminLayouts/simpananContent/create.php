<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item"><a href="#">Simpanan</a></li>
			<li class="breadcrumb-item"><a href="#">Program Simpanan</a></li>
			<li class="breadcrumb-item active" aria-current="page">Tambah Program Simpanan</li>
		</ol>
	</nav>
</div>
<div class="main-wrapper">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="alert alert-warning" role="alert">
				<h6 class="alert-heading">Mohon Diperhatikan!</h6>
				<p>Sebelum Anda membuat program simpanan baru yang akan digunakan oleh anggota anda.
					Mohon perhatikan dengan seksama seluruh pengaturan program simpanan yang dibuat. Pastikan seluruh inputan yang anda masukkan telah sesuai dengan tujuan program simpanan yang direncakanan.
					Berikut adalah beberapa pengaturan penting yang perlu anda ketahui :
				</p>
				<hr>
				<ul>
					<li>
						<strong class="text-primary">Harga pasti</strong> hanya digunakan untuk program simpanan yang tidak memiliki range nominal pembayaran (ex. simpanan wajib dan simpanan pokok)
					</li>
					<li>
						<strong class="text-primary">Promo cashback</strong> hanya diisi diaktifkan bila anda menginginkan program simpanan anda memiliki promo
					</li>
					<li>
						<strong class="text-primary">Tipe Simpanan Keanggotaan</strong> adalah tipe simpanan yang wajib dimiliki oleh setiap anggota koperasi
					</li>
					<li>
						<strong class="text-primary">Tipe Simpanan Funding</strong> adalah tipe simpanan yang dapat dipilih oleh anggota koperasi
					</li>
				</ul>
			</div>
			<div class="card">
				<form action="<?= base_url('admin/simpanan/content/save')?>" method="POST" class="js_admin-post-simpanan-content">
					<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
					<div class="card-header">
						<h5>Tambah Program Simpanan</h5>
					</div>
					<div class="card-body">
						<?= $this->session->flashdata('message') ?>
						<div class="form-row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="simpanan_name">Nama Program Simpanan *</label>
									<input type="text" class="form-control" id="simpanan_name" name="simpanan_name" placeholder="Nama Program Simpanan" value="<?= set_value('simpanan_name') ?>">
									<small class="text-danger"><?= form_error('simpanan_name')?></small>
								</div>
								<div class="form-group">
									<label for="simpanan_minimum_price">Minimal Pembayaran *</label>
									<input type="number" class="form-control" id="simpanan_minimum_price" name="simpanan_minimum_price" placeholder="Rp. 50.000" value="<?= set_value('simpanan_minimum_price') ?>">
									<small class="text-danger"><?= form_error('simpanan_minimum_price')?></small>
								</div>
								<div class="form-group">
									<label for="simpanan_maximum_price">Maximal Pembayaran</label>
									<input type="number" class="form-control" id="simpanan_maximum_price" name="simpanan_maximum_price" placeholder="Rp. 50.000" value="<?= set_value('simpanan_maximum_price') ?>">
									<small class="text-danger"><?= form_error('simpanan_maximum_price')?></small>
								</div>
								<div class="form-group">
									<label for="simpanan_fixed_price">Harga Pasti</label>
									<input type="number" class="form-control" id="simpanan_fixed_price" name="simpanan_fixed_price" placeholder="Rp 50.000" value="<?= set_value('simpanan_fixed_price') ?>">
									<small class="text-danger"><?= form_error('simpanan_fixed_price')?></small>
								</div>
								<div class="form-group">
									<label for="simpanan_duration">Durasi Simpanan*</label>
									<div class="input-group">
										<input type="number" class="form-control js-input-group" id="simpanan_duration" name="simpanan_duration" placeholder="1" value="<?= set_value('simpanan_duration') ?>">
										<div class="input-group-append">
											<span class="input-group-text btn btn-outline-primary" id="inputGroupPrepend">
												<div class="col-sm-1">
													Bulan
												</div>
											</span>
										</div>
									</div>
									<small class="js_input-error-placement"><?= form_error('simpanan_duration')?></small>
								</div>
								<div class="form-group">
									<label for="simpanan_content_status">Status Program Simpanan*</label>
									<select name="simpanan_content_status" id="simpanan_content_status" class="single-select form-control js-input-with-plugin" style="display: none;width: 100%">
										<optgroup label="--Pilih Status Program Simpanan--">
											<option value="1">Aktif</option>
											<option value="0">Non-Aktif</option>
										</optgroup>
									</select>
									<small class="text-danger js_input-error-placement"><?= form_error('simpanan_content_status')?></small>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="simpanan_balas_jasa_percentage">Persentase Balas Jasa*</label>
									<div class="input-group">
										<input type="number" class="form-control js-input-group" id="simpanan_balas_jasa_percentage" name="simpanan_balas_jasa_percentage" placeholder="1" value="<?= set_value('simpanan_balas_jasa_percentage') ?>">
										<div class="input-group-append">
											<span class="input-group-text btn btn-outline-primary" id="inputGroupPrepend">
												<div class="col-sm-1">
													%
												</div>
											</span>
										</div>
									</div>
									<small class="js_input-error-placement"><?= form_error('simpanan_balas_jasa_percentage')?></small>
								</div>
								<div class="form-group">
									<label for="simpanan_balas_jasa_perdays">Durasi Pembayaran Balas Jasa*</label>
									<div class="input-group">
										<input type="number" class="form-control js-input-group" id="simpanan_balas_jasa_perdays" name="simpanan_balas_jasa_perdays" placeholder="1" value="<?= set_value('simpanan_balas_jasa_perdays') ?>">
										<div class="input-group-append">
											<span class="input-group-text btn btn-outline-primary" id="inputGroupPrepend">
												<div class="col-sm-1">
													Bulan
												</div>
											</span>
										</div>
									</div>
									<small class="js_input-error-placement"><?= form_error('simpanan_balas_jasa_perdays')?></small>
								</div>
								<div class="form-group">
									<label for="simpanan_lpoint_percentage">Persentase Lentera Point</label>
									<div class="input-group">
										<input type="number" class="form-control js-input-group" id="simpanan_lpoint_percentage" name="simpanan_lpoint_percentage" placeholder="1" value="<?= set_value('simpanan_lpoint_percentage') ?>">
										<div class="input-group-append">
											<span class="input-group-text btn btn-outline-primary" id="inputGroupPrepend">
												<div class="col-sm-1">
													%
												</div>
											</span>
										</div>
									</div>
									<small class="js_input-error-placement"><?= form_error('simpanan_lpoint_percentage')?></small>
								</div>
								<div class="form-group">
									<label for="simpanan_promo_cashback_percentage">Persentase Promo Cashback</label>
									<div class="input-group">
										<input type="number" class="form-control js-input-group" id="simpanan_promo_cashback_percentage" name="simpanan_promo_cashback_percentage" placeholder="1" value="<?= set_value('simpanan_promo_cashback_percentage') ?>">
										<div class="input-group-append">
											<span class="input-group-text btn btn-outline-primary" id="inputGroupPrepend">
												<div class="col-sm-1">
													%
												</div>
											</span>
										</div>
									</div>
									<small class="js_input-error-placement"><?= form_error('simpanan_promo_cashback_percentage')?></small>
								</div>
								<div class="form-group">
									<label for="simpanan_is_promo_active">Status Promo Cashback</label>
									<select name="simpanan_is_promo_active" id="simpanan_is_promo_active" class="single-select form-control js-input-with-plugin" style="display: none;width: 100%">
										<optgroup label="--Pilih Satatus Promo--">
											<option value="0">Non-Aktif</option>
											<option value="1">Aktif</option>
										</optgroup>
									</select>
									<small class="text-danger js_input-error-placement"><?= form_error('simpanan_is_promo_active')?></small>
								</div>
								<div class="form-group">
									<label for="simpanan_content_type">Tipe Program Simpanan*</label>
									<select name="simpanan_content_type" id="simpanan_content_type" class="single-select form-control js-input-with-plugin" style="display: none;width: 100%">
										<optgroup label="--Pilih Tipe Program Simpanan--">
											<option value="<?= SimpananContentType::SIMPANAN_KEANGGOTAAN?>"><?= SimpananContentType::SIMPANAN_KEANGGOTAAN?></option>
											<option value="<?= SimpananContentType::SIMPANAN_FUNDING?>"><?= SimpananContentType::SIMPANAN_FUNDING?></option>
										</optgroup>
									</select>
									<small class="text-danger js_input-error-placement"><?= form_error('simpanan_content_type')?></small>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<a href="javascript:window.history.go(-1);" class="btn btn-light">Kembali</a>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Submit</button>

						<!-- Modal Tambah Konten Simpanan -->
						<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered" role="document">
								<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="exampleModalCenterTitle">Apakah Anda Yakin?</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<i class="material-icons">close</i>
											</button>
										</div>
										<div class="modal-body">
											<p>
												Mohon perhatikan dengan seksama seluruh pengaturan program simpanan yang akan dibuat. Pastikan seluruh inputan yang anda masukkan telah sesuai dengan tujuan program simpanan yang direncakanan.
												Jika anda yakin silahkan tekan tombol <strong>Submit</strong> jika tidak silahkan tekan tombol <strong>Batal</strong>
											</p>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-primary js-form_action_btn" data-dismiss="modal">Submit</button>
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
										</div>
								</div>
							</div>
						</div>
						<!-- ./Modal Tambah Konten Simpanan -->
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
