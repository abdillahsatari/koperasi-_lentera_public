<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
			<li class="breadcrumb-item active" aria-current="page">Pengaturan Applikasi</li>
		</ol>
	</nav>
</div>
<div class="main-wrapper">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card">
				<div class="card-header">
					<h5>Pengaturan Gateway</h5>
				</div>
				<div class="card-body">

					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus"></i> Tambah Gateway</button>
					<!-- Modal Tambah Gateway -->

					<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered" role="document">
							<div class="modal-content">
								<form action="<?=base_url('admin/setting/basic/save'); ?>" method="POST" id="js_form-modal" class="js_form-payment-gateway">
									<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
									<input type="hidden" name="site_setting_type" value="<?= SiteSettingType::PAYMENT_GATEWAY ?>">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalCenterTitle">Payment Gateway Baru</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<i class="material-icons">close</i>
										</button>
									</div>
									<div class="modal-body">
										<div class="form-group">
											<label for="nama_bank">Gateway</label>
											<input type="text" class="form-control" id="nama_bank" name="nama_bank" placeholder="Gateway" value="<?= set_value('nama_bank') ?>">
											<small class="text-danger"><?= form_error('nama_bank')?></small>
										</div>
										<div class="form-group">
											<label for="nomor_rekening">Nomor Rekening</label>
											<input type="number" class="form-control" id="nomor_rekening" name="nomor_rekening" placeholder="Nomor Rekening" value="<?= set_value('admin_full_name') ?>">
											<small><?= form_error('nomor_rekening')?></small>
										</div>
										<div class="form-group">
											<label for="nama_pemilik_account">Atas Nama</label>
											<input type="text" class="form-control" id="nama_pemilik_account" name="nama_pemilik_account" placeholder="Atas Nama" value="<?= set_value('admin_full_name') ?>">
											<small class="text-danger"><?= form_error('nama_pemilik_account')?></small>
										</div>
										<div class="form-group">
											<label for="status_is_active">Pilih Status Gateway</label>
											<select name="status_is_active" id="js_status-gateway" class="modal-select form-control js-input-with-plugin" style="display: none;width: 100%">
												<optgroup label="--Status Gateway--">
													<option value="1">Aktif</option>
													<option value="0">Tidak Aktif</option>
												</optgroup>
											</select>
											<small class="text-danger js_input-error-placement"><?= form_error('status_is_active')?></small>
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

					<!-- ./Modal Pengaturan Gateway -->

					<br/><br/><br/>
					<table class="default-table display" style="width: 100%;">
						<thead>
						<tr>
							<th>No.</th>
							<th>Gateway</th>
							<th>No. Rekening</th>
							<th>Atas Nama</th>
							<th>Status</th>
							<th>Aksi</th>
						</tr>
						</thead>
						<tbody>
						<?php
						$no = 1;
						foreach($dataPaymentGateway as $dataGateway){
							?>
							<tr>
								<td><?= $no++ ?></td>
								<td><?= $dataGateway->nama_bank ?></td>
								<td><?= $dataGateway->nomor_rekening ?></td>
								<td><?= $dataGateway->nama_pemilik_account ?></td>
								<td><?= $dataGateway->status_is_active ? "Aktif":"Tidak Aktif"; ?></td>
								<td><a href="<?= base_url('admin/setting/basic/edit/').SiteSettingType::PAYMENT_GATEWAY."/".$dataGateway->id?>" type="button" class="btn btn-info btn-sm">Detail</a></td>
							</tr>
						<?php }?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
