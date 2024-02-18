<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item"><a href="#">Pinjaman</a></li>
			<li class="breadcrumb-item active" aria-current="page"></li>
			Program Pinjaman
		</ol>
	</nav>
	<div class="page-options">
		<a href="<?= base_url('admin/pinjaman/content/create')?>" class="btn btn-primary">Tambah Program Pinjaman</a>
	</div>
</div>

<div class="main-wrapper">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card">
				<div class="card-header">
					<h5>Daftar Program Pinjaman</h5>
				</div>
				<div class="card-body">
					<?= $this->session->flashdata('message')?>
					<table class="default-table display" style="width: 100%;">
						<thead>
						<tr>
							<th>No.</th>
							<th>Nama Pinjaman</th>
							<th>DiBuat Pada</th>
							<th>Dibuat Oleh</th>
							<th>Status</th>
							<th>Aksi</th>
						</tr>
						</thead>
						<tbody>
						<?php $no=1; foreach($dataContentPinjaman as $contentPinjaman){?>
							<tr>
								<td><?= $no++; ?></td>
								<td><?= $contentPinjaman->pinjaman_name ?></td>
								<td><?= date('d M Y ', strtotime($contentPinjaman->created_at)) ?></td>
								<td><?= $contentPinjaman->admin_full_name ?></td>
								<td><?= $contentPinjaman->pinjaman_content_status ? "Aktif" : "Non-aktif" ?></td>
								<td>
									<a href="<?= base_url('admin/pinjaman/content/edit/').$contentPinjaman->id?>" type="button" class="btn btn-primary btn-sm" disabled readonly>Edit</a>
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
