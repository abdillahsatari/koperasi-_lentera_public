<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item"><a href="#">Pengurus</a></li>
			<li class="breadcrumb-item active" aria-current="page">
				Semua Pengurus
			</li>
		</ol>
	</nav>
</div>
<div class="main-wrapper">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card">
				<div class="card-header">
					<h5>Semua Pengurus</h5>
				</div>
				<div class="card-body">
					<?= $this->session->flashdata('message');?>
					<table class="default-table display" style="width: 100%;">
						<thead>
						<tr>
							<th>No</th>
							<th>Pengurus</th>
							<th>Didaftarkan Pada</th>
							<th>Role</th>
							<th>Status</th>
							<?php if ($this->session->userdata('admin_role_type')== AdminRoleType::ADMIN) {?>
							<th>Aksi</th>
							<?php }?>
						</tr>
						</thead>
						<tbody>
						<?php
						$no = 1;
						foreach($dataPengurus as $pengurus){
							$pengurusId = $pengurus->id;
							switch ($pengurus->role_type){
								case AdminRoleType::ADMIN:
									$role = AdminRoleType::ADMIN;
									break;
								default:
									$role = AdminRoleType::PENGURUS_KOPERASI;
									break;
							}
							?>
							<tr>
								<td><?= $no++; ?></td>
								<td>
									<?= $pengurus->admin_phone_number." / ".$pengurus->admin_full_name ?>
									<br/>
									<strong>
										<span class="text-info"><?= $pengurus->admin_email ?></span>
									</strong>
								</td>
								<td><?= date('d M Y ', strtotime($pengurus->created_at)) ?></td>
								<td><?= $role ?></td>
								<td><?= $pengurus->admin_status ? "Aktif" : "Non-Aktif"; ?></td>
								<?php if ($this->session->userdata('admin_role_type')== AdminRoleType::ADMIN) {?>
									<td>
										<button type="button" class="btn btn-info btn-sm" id="js_admin-pengurus-detail" data-id="<?= $pengurusId ?>" data-toggle="modal" data-target="#showAdminPengurusDetail">Detail</button>
										<a href="<?= base_url('admin/pengurus/edit/').$pengurusId ?>" type="button" class="btn btn-primary btn-sm">Edit</a>
									</td>
								<?php }?>
							</tr>
						<?php }?>
						</tbody>
					</table>

					<?php if ($dataPengurus) {?>
						<!-- Modal -->
						<div class="modal fade js_admin-pengurus-detail" id="showAdminPengurusDetail" tabindex="-1" role="dialog" aria-labelledby="detailDepositModal" aria-hidden="true"
							 data-url="<?=base_url('admin/AdminAjax/showAdminPengurusDetail/'); ?>">
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
												<td class="js_admin-full-name"></td>
											</tr>
											<tr>
												<td>Status Verifikasi Email</td>
												<td class="js_admin-verified"></td>
											</tr>
											<tr>
												<td>Status Verifikasi Data</td>
												<td class="js_admin-kyc"></td>
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
