<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item"><a href="#">Pinjaman</a></li>
			<li class="breadcrumb-item active" aria-current="page">
				Pengajuan Pinjaman
			</li>
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

	<?= $this->session->flashdata("message")?>
	<?php if ($dataProgramPinjaman){?>
		<div class="col-lg-12">
			<div class="card">
				<div class="alert alert-info no-m">
					Detail Pinjaman Saya
				</div>
			</div>
		</div>
		<br/>
		<div class="row">
			<?php foreach ($dataProgramPinjaman as $dataProgram) {?>
				<div class="col-lg-4 col-md-12">
					<div class="card savings-card">
						<div class="card-body">
							<h5 class="card-title"><?= $dataProgram->pinjaman_name?></h5>
						</div>
						<div class="card-footer">
							<div class="float-right">
								<button class="btn <?= $memberIsActivated ? "btn-primary" : "btn-dark";?>"
										data-id="<?=$dataProgram->id?>" data-toggle="modal" data-target="#exampleModal"
										<?= $memberIsActivated ? "" : "disabeled"; ?>>Ajukan Pinjaman</button>
							</div>
						</div>
					</div>
				</div>
			<?php }?>
			<?php if ($memberIsSimwa || $memberIsActivated) {?>
			<!-- Modal Bayar  -->
			<div class="modal fade js_member-post-pinjaman" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="detailDepositModal" aria-hidden="true"
				 data-url="<?=base_url('member/MemberAjax/showLenteraPinjamanContentDetail/'); ?>">
				<div class="modal-dialog modal-dialog-centered modal-md" role="document">
					<div class="modal-content" id="block-modal">
						<form action="<?= base_url('member/pinjaman/pengajuan/save') ?>" method="POST" class="js_form-member-pinjaman">
							<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
							<input type="hidden" name="lentera_pinjaman_content_id" value="">
							<input type="hidden" name="pinjaman_content_detail_id" value="">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalCenterTitle">Form Pengajuan Pinjaman</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<i class="material-icons">close</i>
								</button>
							</div>
							<div class="modal-body">
								<div class="col-md-12">
									<div class="form-group">
										<label for="inputEmail4">Nominal Pinjaman</label>
										<div class="input-group">
											<div class="input-group-prepend">
										<span class="input-group-text btn btn-primary" id="inputGroupPrepend">
											<div class="col-sm-1">
												Rp.
											</div>
										</span>
											</div>
											<input type="number" class="form-control" id="pinjaman_member_ammount" name="pinjaman_member_ammount" value="<?= set_value('pinjaman_member_ammount')?>" placeholder="500.000">
										</div>
									</div>
									<div class="form-group js-pinjaman-member-select">
										<label>Jangka Waktu Pinjaman</label>
										<select name="pinjaman_member_tenor" class="modal-select form-control" id="pinjamanSelectTenor" style="display: none;width: 100%" data-id="new id">
											<optgroup label="--Pilih Tenor--">

											</optgroup>
										</select>
									</div>
									<div class="form-group">
										<label for="exampleFormControlTextarea1">Keterangan</label>
										<textarea name="pinjaman_member_description" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
									</div>

									<label>Kode Transaksi</label>
									<div class="form-group input-group">
										<div class="input-group-prepend">
											<span class="input-group-text btn btn-primary" id="inputGroupPrepend">
												<div class="col-sm-1 fas fa-lock">
												</div>
											</span>
										</div>
										<input type="password" class="form-control" id="member_transaction_code" name="member_transaction_code"
											   placeholder="*****" aria-describedby="member_transaction_code">
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="submit" class="btn btn-primary">Ajukan</button>
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!--./Modal-->
			<?php }?>
		</div>
	<?php } else {?>
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">
					<span class="text-center">
						<h5>Belum ada Program Pinjaman yang dapat kami tawarkan saat ini</h5>
					</span>
				</div>
			</div>
		</div>
	<?php }?>
</div>
