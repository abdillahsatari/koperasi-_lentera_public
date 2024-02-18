<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item"><a href="#">Simpanan</a></li>
			<li class="breadcrumb-item active" aria-current="page">Program Funding</li>
		</ol>
	</nav>
</div>
<div class="main-wrapper">
	<?= $this->session->flashdata("message")?>
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


	<?php if ($dataContentSimpananFunding){?>
	<div class="col-lg-12">
		<div class="card">
			<div class="alert alert-info no-m">
				Simpanan Program Funding.
			</div>
		</div>
	</div>
	<br/>
	<div class="row">
		<?php foreach ($dataContentSimpananFunding as $simpananFunding) {?>
			<div class="col-lg-4 col-md-12">
				<div class="card savings-card">
					<div class="card-body">
<!--						<h5 class="card-title">--><?//= $simpananFunding->simpanan_name?><!--</h5>-->
						<div class="savings-stats">
							<h5><?= $simpananFunding->simpanan_name?></h5>
						</div>
					</div>
					<div class="card-footer">
						<div class="float-right">
							<button class="btn <?= !$memberIsActivated ? "btn-dark": "btn-primary"?>"
									data-id="<?=$simpananFunding->id?>" data-toggle="modal" data-target="#simpananFunding"
									<?= !$memberIsActivated ? "disabled readonly": ""?>>
								Bayar</button>
						</div>
					</div>
				</div>
			</div>
		<?php }?>
		<?php if ($memberIsActivated) {?>
		<!-- Modal Bayar  -->
		<div class="modal fade js_member-post-simpanan-funding" id="simpananFunding" tabindex="-1" role="dialog" aria-labelledby="detailDepositModal" aria-hidden="true"
			 data-url="<?=base_url('member/MemberAjax/showLenteraSimpananContentDetail/'); ?>">
			<div class="modal-dialog modal-dialog-centered modal-md" role="document">
				<div class="modal-content" id="block-modal">
					<form action="<?= base_url('member/simpanan/program-funding/save') ?>" method="POST" class="js_form-member-deposit">
						<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
						<input type="hidden" name="lentera_simpanan_content_id" value="">
						<input type="hidden" name="simpanan_content_type" value="<?=SimpananContentType::SIMPANAN_FUNDING?>">
						<input type="hidden" name="simpanan_input_type" value="ammount">
						<input type="hidden" name="lentera_simpanan_content_name" value="">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalCenterTitle">Detail Program</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<i class="material-icons">close</i>
							</button>
						</div>
						<div class="modal-body">
							<div class="col-lg-12">
								<div class="alert alert-warning" role="alert">
									<ul>
										<li>
											<span class="js_simpanan-name"></span> Minimal <span class="js_simpanan-price"></span>
										</li>
										<li>
											Anda akan mendapatkan bagi untung sebesar <span class="js_simpanan-bagi-untung"></span> perbulan selama <span class="js_simpanan-duration"></span> bulan
										</li>
										<li>
											Saldo anda saat ini <span class="js-currencyFormatter" data-price="<?= current($this->FinanceModel->memberFinance($this->session->userdata("member_id")))->totalWalletBalance; ?>"></span>
										</li>
									</ul>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group js-simpanan_member_input_ammount">

								</div>

								<input type="hidden" class="form-control" name="simpanan_member_ammount" value="" placeholder="Nominal Simpanan">

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
		<?php }?>
	</div>
<?php } ?>

</div>
