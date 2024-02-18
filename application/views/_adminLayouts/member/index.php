<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item"><a href="#">Keanggotaan</a></li>
			<li class="breadcrumb-item active" aria-current="page">
				<?php
				switch ($pageType){
					case strtolower(MemberStatus::REGISTERED):
						echo "Daftar Calon Anggota";
						break;
					case strtolower(MemberStatus::ACTIVE):
						echo "Daftar Anggota Aktif";
						break;
				}
				?>
			</li>
		</ol>
	</nav>
</div>

<div class="main-wrapper">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card">
				<div class="card-header">
					<h5>
						<?php
						switch ($pageType){
							case strtolower(MemberStatus::REGISTERED):
								echo "Daftar Calon Anggota";
								break;
							case strtolower(MemberStatus::ACTIVE):
								echo "Daftar Anggota";
								break;
						}
						?>
					</h5>
				</div>
				<div class="card-body">
					<?= $this->session->flashdata('message');?>
					<table class="default-table display" style="width: 100%;">
						<thead>
						<tr>
							<th>No.</th>
							<th>Anggota</th>
							<th>Didaftarkan Oleh</th>
							<th>Didaftarkan Pada</th>
							<th>Status</th>
							<th>Aksi</th>
						</tr>
						</thead>
						<tbody>
						<?php

						$no = 1;
						$memberId="";
						$memberStatus="";

						foreach($dataMember as $member){
							$memberId 		= $member->id;
							$memberStatus 	= $member->member_is_activated;
							?>
							<tr>
								<td>
									<?= $no++;?>
								</td>
								<td>
									<strong>
										<span class="text-info"><?= $member->member_referal_code ?></span>
									</strong>
									<br/>
									<?= $member->member_email ?>
									<br/>
									<strong><?= $member->member_phone_number ?></strong> / <?= $member->member_full_name ?>
								</td>
								<td>
									<strong>
										<span class="text-info"><?= $member->parent_member_referal_code ?></span>
									</strong>
									<br/>
									<?= $member->parent_member_full_name ?>
								</td>
								<td><?= date('d M Y ', strtotime($member->created_at)) ?></td>
								<td>
									<?= $member->member_is_activated ?>
								</td>
								<td>
									<button type="button" class="btn btn-info btn-sm" id="js_admin-member-detail" data-id="<?= $member->id ?>" data-toggle="modal" data-target="#adminShowMemberDetail">Detail</button>
									<a href="<?= base_url('admin/keanggotaan/edit/').$member->id ?>" type="button" class="btn btn-primary btn-sm">Edit</a>
								</td>
							</tr>
						<?php }?>
						</tbody>
					</table>

					<?php if ($dataMember){?>
					<!-- Modal -->
					<div class="modal fade js_admin-show-member-detail" id="adminShowMemberDetail" tabindex="-1" role="dialog" aria-labelledby="detailDepositModal" aria-hidden="true"
						 data-url="<?=base_url('admin/AdminAjax/showMemberTimDetail/'); ?>">
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
