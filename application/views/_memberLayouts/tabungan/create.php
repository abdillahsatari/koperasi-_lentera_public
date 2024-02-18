<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item"><a href="#">Tabungan</a></li>
			<li class="breadcrumb-item active" aria-current="page">Pembukan Tabungan Baru</li>
		</ol>
	</nav>
</div>
<div class="main-wrapper">
	<?php if (!$memberIsSimwa || !$memberIsActivated){?>
		<div class="col-lg-12">
			<div class="alert alert-warning" role="alert">
				<h6 class="alert-heading">Dear <?= $this->session->userdata("member_full_name")?></h6>
				<p>Untuk dapat menggunakan pinjaman, melakukan tabungan, mendaftarkan anggota, dan transaksi lainnya. Mohon segera melakukan langkah-langkah berikut</p>
				<hr>
				<ul>
					<?php if (!$memberIsKyc){?>
						<li>
							<a href="<?= base_url('member-profile/edit/'); ?><?= $this->session->userdata('member_id');?>">verifikasi data</a>
						</li>
					<?php }?>
					<?php if (!$memberIsSimwa){?>
						<li>
							<a href="<?= base_url('member/simpanan/index')?>">Membayar Simpanan Wajib</a>
						</li>
					<?php }?>
					<?php if (!$memberIsSimpo){?>
						<li>
							<a href="<?= base_url('member/simpanan/index')?>">Membayar Simpanan Pokok</a>
						</li>
					<?php }?>
				</ul>
			</div>
		</div>
	<?php }?>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card">
				<div class="card-header">
					<h5>Form Pembukaan Tabungan baru</h5>
				</div>
				<form action="<?= base_url('member/tabungan/buka-tabungan/save')?>" method="POST" class="js_member-post-tabungan">
					<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
					<div class="card-body">
						<?= $this->session->flashdata('message') ?>
						<div class="form-row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="tabungan_ammount">Nominal Tabungan</label>
									<div class="input-group">
										<div class="input-group-prepend">
										<span class="input-group-text btn btn-outline-primary" id="inputGroupPrepend">
											<div class="col-sm-1">
												Rp.
											</div>
										</span>
										</div>
										<input type="number" class="form-control" id="tabungan_ammount" name="tabungan_ammount" placeholder="100.000" value="<?= set_value('tabungan_ammount') ?>" <?= !$memberIsActivated ? "disabled readonly": ""?>>
									</div>
								</div>
								<small class="text-danger"><?= form_error('tabungan_ammount')?></small>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="lentera_bank_id">Pilih Rekening Tabungan</label>
									<select name="lentera_bank_id" id="js_status-gateway" class="single-select form-control js-input-with-plugin" style="display: none;width: 100%" <?= !$memberIsActivated ? "disabled readonly": ""?>>
										<optgroup label="--Pilih Rekening Tabungan--">
											<?php
											foreach($dataGateway as $gateway) {?>
												<option value="<?= $gateway->id ?>"><?= $gateway->nama_bank?></option>
											<?php }?>
										</optgroup>
									</select>
									<small class="text-danger js_input-error-placement"><?= form_error('lentera_bank_id')?></small>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<a href="javascript:window.history.go(-1);" class="btn btn-light">Kembali</a>
						<?php if ($memberIsActivated){?>
						<button type="button" class="btn btn-primary js-form_action_btn">Update</button>
						<?php }?>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
