<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item"><a href="#">Online Shop</a></li>
			<li class="breadcrumb-item active" aria-current="page">Tambah Produk</li>
		</ol>
	</nav>
</div>
<div class="main-wrapper">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card">
				<form action="<?= base_url('admin/olshop/save')?>" method="POST" class="js_admin-post-olshop">
					<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
					<div class="card-header">
						<h5>Tambah Produk Online Shop</h5>
					</div>
					<div class="card-body">
						<?= $this->session->flashdata('message') ?>
						<div class="form-row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="olshop_product_name">Nama Produk</label>
									<input type="text" class="form-control" id="olshop_product_name" name="olshop_product_name" placeholder="Nama Produk" value="<?= set_value('olshop_product_name') ?>">
									<small><?= form_error('olshop_product_name')?></small>
								</div>
								<div class="form-group">
									<label for="olshop_product_price">Harga L-poin</label>
									<input type="number" class="form-control" id="olshop_product_price" name="olshop_product_price" placeholder="Harga L-poin" value="<?= set_value('olshop_product_price') ?>">
									<small><?= form_error('olshop_product_price')?></small>
								</div>
								<div class="form-group">
									<label for="olshop_product_status">Pilih Status Admin</label>
									<select name="olshop_product_status" id="olshop_product_status" class="single-select form-control js-input-with-plugin" style="display: none;width: 100%">
										<optgroup label="--Pilih Satatus Produk--">
											<option value="1">Aktif</option>
											<option value="0">Non-Aktif</option>
										</optgroup>
									</select>
									<small class="text-danger js_input-error-placement"><?= form_error('olshop_product_status')?></small>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="olshop_product_img" class="form-label">Thumbnail Produk</label><br>
									<img src="https://via.placeholder.com/200x155.png?text=Preview" alt="ktp"
										 class="image-preview" width="100%" height="225">
									<br><br>
									<input type="file" class="form-control js-image_upload" accept=".png, .jpg, .jpeg" data-url-upload="<?= base_url('admin/adminAjax/postOlshopImg');?>">
									<input type="hidden" name="olshop_product_img" id="olshop_product_img" value="">
									<small><?= form_error('olshop_product_img')?></small>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<a href="javascript:window.history.go(-1);" class="btn btn-light">Kembali</a>
						<button type="button" class="btn btn-primary js-form_action_btn">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
