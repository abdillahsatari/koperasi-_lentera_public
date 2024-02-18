<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item active" aria-current="page"></li>
			Online Shop
		</ol>
	</nav>
</div>

<div class="main-wrapper">
	<?= $this->session->flashdata("message")?>
	<div class="row">
		<?php if ($dataOnlineShop){?>
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
							<span class="text-center">
								<h5>Produk Koperasi Lentera Digital Indonesia</h5>
							</span>
					</div>
				</div>
			</div>
			<?php foreach ($dataOnlineShop as $product){?>
				<div class="col-md-4">
					<div class="card">
						<img src="<?= $product->olshop_product_img ? base_url('assets/resources/images/uploads/').$product->olshop_product_img : "https://via.placeholder.com/200x155.png?text=Preview" ?>" class="card-img-top" alt="Placeholder">
						<div class="card-body">
							<h5 class="card-title"><?= $product->olshop_product_name?></h5>
							<p class="card-text"><?= $product->olshop_product_price?> L-poin</p>
							<a href="#" class="btn btn-primary" data-id="<?= $product->id ?>" data-toggle="modal" data-target="#exampleModal">Beli</a>
						</div>
					</div>
				</div>
			<?php }?>

			<!-- Modal Bayar  -->
			<div class="modal fade js_member-show-checkout-product" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="detailCheckoutProductModal" aria-hidden="true"
				 data-url="<?=base_url('member/MemberAjax/showLenteraOlshopProductDetail/'); ?>">
				<div class="modal-dialog modal-dialog-centered modal-md" role="document">
					<div class="modal-content" id="block-modal">
						<form action="<?= base_url('member/onlineShop/save')?>" method="POST" class="js_member-show-checkout-product">
							<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
							<input type="hidden" class="form-control" name="lentera_olshop_product_id" value="">
							<input type="hidden" class="form-control" name="lentera_olshop_product_price" value="">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalCenterTitle">Checkout Product</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<i class="material-icons">close</i>
								</button>
							</div>
							<div class="modal-body">
								<div class="col-lg-12">
									<div class="alert alert-warning" role="alert">
										<ul>
											<li>Pastikan Saldo L-point anda cukup untuk melakukan pembelian produk</li>
											<li>
												Saldo L-Point anda saat ini <span class="js-currencyFormatter" data-price="<?= current($this->FinanceModel->memberFinance($this->session->userdata("member_id")))->totalLpointBalance; ?>"></span>
											</li>
										</ul>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label for="checkoutQuantity">Kuantitas</label>
										<input type="number" class="form-control" id="checkout_quantity" name="checkout_quantity" placeholder="1" value="<?= set_value('checkout_quantity') ?>">
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
								<button type="submit" class="btn btn-primary">Bayar</button>
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- ./Modal -->

		<?php } else {?>
				<div class="col-md-12">
					<div class="card">
						<div class="card-body">
							<span class="text-center">
								<h5>Belum ada produk yang dapat kami tawarkan saat ini</h5>
							</span>
						</div>
					</div>
				</div>
		<?php }?>
	</div>
</div>
