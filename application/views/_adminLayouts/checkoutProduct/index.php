<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item"><a href="#">Checkout Product</a></li>
			<li class="breadcrumb-item active" aria-current="page"></li>
			<?php
			switch ($pageType){
				case MemberCheckoutProductApproval::CHECKOUT_NEW:
					$headline = "Permintaan Checkout";
					break;
				case MemberCheckoutProductApproval::CHECKOUT_PROCESSED:
					$headline = "Checkout Telah Diproses";
					break;
				case MemberCheckoutProductApproval::CHECKOUT_CANCEL:
					$headline = "Checkout Dibatalkan";
					break;
			}
			echo $headline;
			?>
		</ol>
	</nav>
</div>

<div class="main-wrapper">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card">
				<div class="card-header">
					<h5>
						<?= $headline ?>
					</h5>
				</div>
				<div class="card-body">
					<?= $this->session->flashdata('message')?>
					<table class="default-table display" style="width: 100%;">
						<thead>
						<tr>
							<th>No.</th>
							<th>Anggota</th>
							<th>Produk</th>
							<th>Harga</th>
							<th>No. Ref</th>
							<th>Kuantitas</th>
							<th>Total Harga</th>
							<th>Tgl</th>
							<th>Status</th>
							<?php if ($pageType == MemberCheckoutProductApproval::CHECKOUT_NEW) {?>
								<th>Aksi</th>
							<?php }?>
						</tr>
						</thead>
						<tbody>
						<?php

						$no = 1;
						$checkoutStatus="";
						$checkoutStatusBadge="";

						foreach($dataCheckoutProduct as $checkout){
							$checkoutStatus = $checkout->checkout_approved_status;

							switch ($checkoutStatus){
								case MemberCheckoutProductApproval::CHECKOUT_NEW:
									$checkoutStatusBadge = 'badge-info';
									break;
								case MemberCheckoutProductApproval::CHECKOUT_PROCESSED:
									$checkoutStatusBadge = 'badge-primary';
									break;
								case MemberCheckoutProductApproval::CHECKOUT_CANCEL:
									$checkoutStatusBadge = 'badge-danger';
									break;
							}
							?>
							<tr>
								<td><?= $no++; ?></td>
								<td>
									<strong>
										<span class="text-info"><?= $checkout->member_referal_code ?></span>
									</strong>
									<br/>
									<?= $checkout->member_full_name ?>
								</td>
								<td><?= $checkout->olshop_product_name ?></td>
								<td class="js-currencyFormatter" data-price="<?= $checkout->olshop_product_price ?>"></td>
								<td><?= $checkout->checkout_code ?></td>
								<td><?= $checkout->checkout_quantity ?></td>
								<td class="js-currencyFormatter" data-price="<?= $checkout->olshop_product_price * $checkout->checkout_quantity ?>"></td>
								<td>
									<?= $checkout->checkout_approved_date ?
											date('d M Y ', strtotime($checkout->checkout_approved_date)) : date('d M Y ', strtotime($checkout->created_at)); ?>
								</td>
								<td>
									<span class="badge <?= $checkoutStatusBadge ?> js_tabungan-status">
										<?= $checkoutStatus ?>
									</span>
								</td>
								<?php if ($pageType == MemberCheckoutProductApproval::CHECKOUT_NEW) {?>
									<td>
										<button type="button" class="btn btn-primary btn-sm" id="js_member-tabungan-detail" data-id="<?= $checkout->id ?>" data-toggle="modal" data-target="#exampleModal">Edit</button>
									</td>
								<?php }?>
							</tr>
						<?php }?>
						</tbody>
					</table>

					<?php if ($pageType == MemberCheckoutProductApproval::CHECKOUT_NEW) {?>
						<!-- Modal -->
						<div class="modal fade js_admin-show-checkout-product" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalDetail" aria-hidden="true"
							 data-url="<?=base_url('admin/AdminAjax/showOlshopCheckoutDetail/'); ?>">
							<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
								<div class="modal-content" id="block-modal">
									<form action="<?= base_url('admin/checkout/product/update')?>" method="POST" class="js_admin-show-checkout-product">
										<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
										<input type="hidden" name="lentera_olshop_product_id" class="form-control" value="">
										<input type="hidden" name="member_id" class="form-control" value="">
										<input type="hidden" name="checkout_code" class="form-control" value="">
										<div class="modal-header">
											<h5 class="modal-title" id="exampleModalCenterTitle">Detail Checkout Product</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<i class="material-icons">close</i>
											</button>
										</div>
										<div class="modal-body">
											<h3 class="card-title">Personal Info</h3>
											<table class="table table-striped table-bordered table-condensed">
												<tbody>
												<tr>
													<td>Kode Anggota</td>
													<td class="js_member-uid"></td>
												</tr>
												<tr>
													<td>Nama Anggota</td>
													<td class="js_member-full-name"></td>
												</tr>
												<tr>
													<td>Nomor Hp</td>
													<td class="js_member-phone-number"></td>
												</tr>
												<tr>
													<td>Email</td>
													<td class="js_member-email"></td>
												</tr>
												</tbody>
											</table>
											<br/>
											<h3 class="card-title">Checkout Info</h3>
											<table class="table table-striped table-bordered table-condensed">
												<tbody>
												<tr>
													<td>Status</td>
													<td>
														<select name="checkout_approved_status" class="modal-select form-control" id="selectTabunganApprovalModal" style="display: none;width: 100%">
															<optgroup label="--Pilih Status Checkout--">
																<option value="<?= MemberCheckoutProductApproval::CHECKOUT_NEW ?>">Checkout Baru</option>
																<option value="<?= MemberCheckoutProductApproval::CHECKOUT_PROCESSED ?>" >Checkout Diproses</option>
																<option value="<?= MemberCheckoutProductApproval::CHECKOUT_CANCEL ?>" >Checkout Dibatalkan</option>
															</optgroup>
														</select>
														<small class="text-danger"><?= form_error('checkout_approved_status	')?></small>
													</td>
												</tr>
												<tr>
													<td>Kode Checkout</td>
													<td class="js_checkout-code"></td>
												</tr>
												<tr>
													<td>Nama Produk</td>
													<td class="js_product-name"></td>
												</tr>
												<tr>
													<td>Harga Produk</td>
													<td class="js_product-price"></td>
												</tr>
												<tr>
													<td>Kuantitas</td>
													<td class="js_checkout-quantity"></td>
												</tr>
												<tr>
													<td>Total Pembayarn</td>
													<td class="js_checkout-total-ammount"></td>
												</tr>
												</tbody>
											</table>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
											<button type="submit" class="btn btn-primary">Update</button>
										</div>
									</form>
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
