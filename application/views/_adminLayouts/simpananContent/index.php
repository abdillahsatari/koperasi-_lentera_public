<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item"><a href="#">Simpanan</a></li>
			<li class="breadcrumb-item active" aria-current="page"></li>
			Program Simpanan
		</ol>
	</nav>
	<div class="page-options">
		<a href="<?= base_url('admin/simpanan/content/create')?>" class="btn btn-primary">Tambah Program Simpanan</a>
	</div>
</div>

<div class="main-wrapper">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card">
				<div class="card-header">
					<h5>Daftar Program Simpanan</h5>
				</div>
				<div class="card-body">
					<?= $this->session->flashdata('message')?>
					<table class="default-table display" style="width: 100%;">
						<thead>
						<tr>
							<th>No.</th>
							<th>Nama Simpanan</th>
							<th>Tipe Simpanan</th>
							<th>DiBuat Pada</th>
							<th>Dibuat Oleh</th>
							<th>Status</th>
							<th>Aksi</th>
						</tr>
						</thead>
						<tbody>
						<?php $no=1; foreach($dataContentSimpanan as $contentSimpanan){?>
							<tr>
								<td><?= $no++; ?></td>
								<td><?= $contentSimpanan->simpanan_name ?></td>
								<td><?= $contentSimpanan->simpanan_content_type ?></td>
								<td><?= date('d M Y ', strtotime($contentSimpanan->created_at)) ?></td>
								<td><?= $contentSimpanan->admin_full_name ?></td>
								<td><?= $contentSimpanan->simpanan_content_status ? "Aktif" : "Non-aktif" ?></td>
								<td>
									<a href="<?= base_url('admin/simpanan/content/edit/')?><?= $contentSimpanan->id?>" type="button" class="btn btn-primary btn-sm">Edit</a>
								</td>
							</tr>
						<?php }?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
