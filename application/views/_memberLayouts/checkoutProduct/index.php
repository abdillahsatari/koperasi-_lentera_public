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
									<span class="badge <?= $checkoutStatusBadge ?>">
										<?= $checkoutStatus ?>
									</span>
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
