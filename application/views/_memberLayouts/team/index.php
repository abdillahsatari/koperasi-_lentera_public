<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item"><a href="#">Keanggotaan</a></li>
			<li class="breadcrumb-item active" aria-current="page">Anggota Saya</li>
		</ol>
	</nav>
	<div class="page-options">
		<button type="button" class="btn <?= !$memberIsActivated ? "btn-dark": "btn-primary"?>" data-toggle="modal" data-target="#exampleModal" <?= !$memberIsActivated ? "disabled readonly": ""?>>Tambah Anggota</button>

		<!-- Modal -->
		<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<form action="<?=base_url('member/keanggotaan/save'); ?>" method="POST" class="js_form-member-register-team"
						  data-url="<?= base_url('member/MemberAjax/verifyMemberPhoneNumber')?>">
						<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
						<input type="hidden" name="referal_link" value="<?= current($dataReferal)->member_referal_code ?>">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalCenterTitle">Form Pendaftaran Anggota Baru</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<i class="material-icons">close</i>
							</button>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<div class="form-group">
									<input type="text" class="form-control" id="member_full_name" name="member_full_name" value="<?=set_value('member_full_name')?>" placeholder="Nama Lengkap">
									<small class="text-danger"><?= form_error('member_full_name')?></small>
								</div>
								<div class="form-group">
									<input type="email" class="form-control" id="member_email" name="member_email" value="<?= set_value('member_email')?>" placeholder="Email">
									<small class="text-danger"><?= form_error('member_email')?></small>
								</div>
								<div class="form-group">
									<input type="number" class="form-control" id="member_phone_number" name="member_phone_number" value="<?= set_value('member_phone_number')?>" placeholder="No. Hp">
									<small class="text-danger"><?= form_error('member_phone_number')?></small>
								</div>
								<div class="form-group">
									<input type="number" class="form-control" id="member_ktp_number" name="member_ktp_number" value="<?= set_value('member_ktp_number')?>" placeholder="No. Ktp">
									<small class="text-danger"><?= form_error('member_ktp_number')?></small>
								</div>
								<div class="form-group input-group">
									<div class="input-group-prepend">
											<span class="input-group-text btn btn-outline-primary js-copy_btn" id="inputGroupPrepend">
												<div class="col-sm-1">
													Kode Referal
												</div>
											</span>
									</div>
									<input type="text" class="form-control js-targeted_copy_value" id="refferal_code"
										   value="<?= (set_value('referal_link') == '' ? current($dataReferal)->member_referal_code : set_value('referal_link')) ?>"
										   placeholder="Username" aria-describedby="refferal_code" readonly>
									<small><?= form_error('referal_link')?></small>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary js-form_action_btn">Daftarkan</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- ./Modal -->

	</div>
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
					<h5>Daftar Anggota Saya</h5>
				</div>
				<div class="card-body">
					<?= $this->session->flashdata('message')?>
					<table class="default-table display" style="width: 100%;">
						<thead>
						<tr>
							<th>Nama</th>
							<th>No. Hp</th>
							<th>Email</th>
							<th>Tgl Pendaftaran</th>
							<th>Status</th>
							<th>Aksi</th>
						</tr>
						</thead>
						<tbody>
						<?php foreach($dataMember as $member){
							$memberId = $member->id;
							?>
							<tr>
								<td><?= $member->member_full_name ?></td>
								<td><?= $member->member_phone_number ?></td>
								<td><?= $member->member_email ?></td>
								<td><?= date('d M Y ', strtotime($member->created_at)) ?></td>
								<td><?= $member->member_is_activated  ? "Aktif" : "Belum Aktif" ?></td>
								<td>
									<button type="button" class="btn btn-primary" id="js_member-tim-detail" data-toggle="modal" data-target="#showMemberTimDetail">Detail</button>
								</td>
							</tr>
						<?php }?>
						</tbody>
					</table>

					<?php if ($dataMember) {?>
					<!-- Modal -->
					<div class="modal fade js_member-tim-detail" id="showMemberTimDetail" tabindex="-1" role="dialog" aria-labelledby="detailDepositModal" aria-hidden="true"
						 data-url="<?=base_url('member/MemberAjax/showMemberTimDetail/'); ?>"
						 data-member-id="<?= $memberId?>">
						<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
						<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
							<div class="modal-content" id="block-modal">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalCenterTitle">Detail Status Member</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<i class="material-icons">close</i>
									</button>
								</div>
								<div class="modal-body">
									<table class="table table-striped table-bordered table-condensed">
										<tbody>
										<tr>
											<td>Nama Lengkap</td>
											<td class="js_member-full-name"></td>
										</tr>
										<tr>
											<td>Kode Referal</td>
											<td class="js_member-referal-code"></td>
										</tr>
										<tr>
											<td>Status Verifikasi Email</td>
											<td class="js_member-verified"></td>
										</tr>
										<tr>
											<td>Status Verifikasi Data</td>
											<td class="js_member-kyc"></td>
										</tr>
										<tr>
											<td>Status Pembayaran Simpanan Pokok</td>
											<td class="js_member-simpanan-pokok"></td>
										</tr>
										<tr>
											<td>Status Pembayaran Simpanan Wajib</td>
											<td class="js_member-simpanan-wajib"></td>
										</tr>
										</tbody>
									</table>
									<br/>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								</div>
							</div>
						</div>
					</div>
					<!-- ./Modal -->
					<?php }?>

				</div>
			</div>
		</div>
	</div>
</div>
