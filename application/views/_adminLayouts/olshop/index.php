<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item active" aria-current="page"></li>
			Online Shop
		</ol>
	</nav>
	<div class="page-options">
		<a href="<?= base_url('admin/olshop/create')?>" class="btn btn-primary">Tambah Produk</a>
	</div>
</div>

<div class="main-wrapper">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card">
				<div class="card-header">
					<h5>Daftar Produk</h5>
				</div>
				<div class="card-body">
					<?= $this->session->flashdata('message')?>
					<table class="default-table display" style="width: 100%;">
						<thead>
						<tr>
							<th>No.</th>
							<th>Nama Barang</th>
							<th>Harga</th>
							<th>Status</th>
							<th>Aksi</th>
						</tr>
						</thead>
						<tbody>
						<?php $no=1; foreach($dataOlshopProduct as $product){?>
							<tr>
								<td><?= $no++; ?></td>
								<td><?= $product->olshop_product_name ?></td>
								<td><?= $product->olshop_product_price ?> L-poin</td>
								<td><?= $product->olshop_product_status ? "Aktif" : "Non-aktif" ?></td>
								<td>
									<a href="<?= base_url('admin/olshop/edit/')?><?= $product->id?>" type="button" class="btn btn-primary btn-sm">Edit</a>
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
