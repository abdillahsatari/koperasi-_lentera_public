<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item"><a href="#">Simpanan</a></li>
			<li class="breadcrumb-item"><a href="#">Program Pinjaman</a></li>
			<li class="breadcrumb-item active" aria-current="page">Tambah Program</li>
		</ol>
	</nav>
</div>
<div class="main-wrapper">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="alert alert-warning" role="alert">
				<h6 class="alert-heading">Mohon Diperhatikan!</h6>
				<p>Sebelum Anda membuat program pinjaman baru yang akan digunakan oleh anggota anda.
					Mohon perhatikan dengan seksama seluruh pengaturan program pinjaman yang dibuat. Pastikan seluruh inputan yang anda masukkan telah sesuai dengan tujuan program pinjaman yang direncakanan.
					Berikut adalah beberapa pengaturan penting yang perlu anda ketahui :
				</p>
				<hr>
				<ul>
					<li>
						<strong class="text-primary">Nama Program Pinjaman</strong> diisi dengan awalan kata "Pinjaman" diikuti dengan program pinjaman yang akan dibuat
					</li>
					<li>
						<strong class="text-primary">Input Tenor</strong> hanya diisi dengan " 0 " apabila program pinjaman tidak memiliki durasi tenor
					</li>
				</ul>
			</div>
			<div class="card">
				<form action="<?= base_url('admin/pinjaman/content/update')?>" method="POST" class="js_admin-post-pinjaman-content"
					  data-url="<?=base_url('admin/AdminAjax/deletePinjamanContentDetail/'); ?>">
					<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
					<input type="hidden" name="content_pinjaman_id" value="<?= $dataPinjamanContent->pinjaman_id?>">
					<div class="card-header">
						<h5>Tambah Program Pinjaman</h5>
					</div>
					<div class="card-body">
						<?= $this->session->flashdata('message') ?>
						<div class="form-row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="pinjaman_name">Nama Program Pinjaman *</label>
									<input type="text" class="form-control" id="pinjaman_name" name="pinjaman_name" placeholder="Nama Program Pinjaman" value="<?= set_value('pinjaman_name') ?: $dataPinjamanContent->pinjaman_name ?>">
									<small class="text-danger"><?= form_error('pinjaman_name')?></small>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="pinjaman_content_tenor_type">Jenis Tenor*</label>
									<select name="pinjaman_content_tenor_type" id="pinjaman_content_tenor_type" class="single-select form-control js-input-with-plugin js_select-tenor-type" style="width: 100%">
										<optgroup label="--Pilih Status Program Pinjaman--">
											<option value="<?= MemberPinjamanTenorType::TENOR_DAILY?>" <?= $dataPinjamanContent->pinjaman_content_tenor_type == MemberPinjamanTenorType::TENOR_DAILY ? "selected":"" ?> >Harian</option>
											<option value="<?= MemberPinjamanTenorType::TENOR_MONTHLY?>" <?= $dataPinjamanContent->pinjaman_content_tenor_type == MemberPinjamanTenorType::TENOR_MONTHLY ? "selected":"" ?>>Bulanan</option>
											<option value="<?= MemberPinjamanTenorType::TENOR_YEARLY?>" <?= $dataPinjamanContent->pinjaman_content_tenor_type == MemberPinjamanTenorType::TENOR_YEARLY ? "selected":"" ?>>Tahunan</option>
										</optgroup>
									</select>
									<small class="text-danger js_input-error-placement"><?= form_error('pinjaman_content_tenor_type')?></small>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="pinjaman_content_status">Status Program Pinjaman*</label>
									<select name="pinjaman_content_status" id="pinjaman_content_status" class="single-select form-control js-input-with-plugin" style="width: 100%">
										<optgroup label="--Pilih Status Program Pinjaman--">
											<option value="1" <?= $dataPinjamanContent->pinjaman_content_status ? "selected":"" ?>>Aktif</option>
											<option value="0" <?= $dataPinjamanContent->pinjaman_content_status ? "":"selected" ?>>Non-Aktif</option>
										</optgroup>
									</select>
									<small class="text-danger js_input-error-placement"><?= form_error('pinjaman_content_status')?></small>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-row col-lg-12">
								<div class="card-body">
									<button type="button" class="btn btn-dark js_btn-add-tenor-setting">
										Tambah Setting Tenor
									</button>
									<table class="clean-table display" style="width: 100%;" id="dynamic_field">
										<thead>
										<tr>
											<th>Bunga</th>
											<th>Tenor</th>
											<th>Aksi</th>
										</tr>
										</thead>
										<tbody>
										<?php foreach ($dataPinjamanContent->pinjaman_setting as $setting){?>
											<tr id="row0">
												<td>
													<div class="input-group">
														<input type="number" name="current_pinjaman_bunga[]" value="<?= $setting->pinjaman_content_bunga ?>" class="form-control" id="pinjaman_bunga" placeholder="3" aria-describedby="pinjaman_bunga">
														<input type="hidden" name="content_pinjaman_detail_id[]" value="<?= $setting->id?>">
														<div class="input-group-append">
														<span class="input-group-text btn btn-outline-primary" id="inputGroupPrepend">
															<div class="col-sm-1">
																<span>%</span>
															</div>
														</span>
														</div>
													</div>
												</td>
												<td>
													<div class="input-group">
														<input type="number" name="current_pinjaman_tenor[]" value="<?= $setting->pinjaman_content_tenor ?>" class="form-control" id="pinjaman_tenor" placeholder="3" aria-describedby="pinjaman_tenor">
														<div class="input-group-append">
														<span class="input-group-text btn btn-outline-primary" id="inputGroupPrepend">
															<div class="col-sm-1">
																<span class="js_target-tenor-type">Hari</span>
															</div>
														</span>
														</div>
													</div>
												</td>
												<td>
													<button type="button" name="remove" id="0" class="btn btn-danger btn_remove-setting" data-id="<?= $setting->id?>">X</button>
												</td>
											</tr>
										<?php }?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<a href="javascript:window.history.go(-1);" class="btn btn-light">Kembali</a>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Update</button>

						<!-- Modal Tambah Pinjaman -->
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
											Mohon perhatikan dengan seksama seluruh pengaturan program pinjaman yang akan dibuat. Pastikan seluruh inputan yang anda masukkan telah sesuai dengan tujuan program pinjaman yang direncakanan.
											Jika anda yakin silahkan tekan tombol <strong>Update</strong> jika tidak silahkan tekan tombol <strong>Batal</strong>
										</p>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-primary js-form_action_btn" data-dismiss="modal">Update</button>
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
									</div>
								</div>
							</div>
						</div>
						<!-- ./Modal Pengaturan Pinjaman -->
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
