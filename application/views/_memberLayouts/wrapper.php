<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="<?= isset($metaData->ogDescription) ? $metaData->ogDescription : "Koperasi Simpan Pinjam di Makassar"?>">
	<meta name="keyword" content="<?= isset($metaData->ogKeyword) ? $metaData->ogKeyword : "koperasi, simpan pinjam, koperasi di makassar, lentera, lentera digital"?>">
	<meta name="author" content="Lentera Digital Indonesia">

	<meta property="og:url" content="<?= isset($metaData->ogUrl) ? $metaData->ogUrl : "https://www.lenteradigitalindonesia.com"?>" />
	<meta property="og:title" content="<?= isset($metaData->ogTitle) ? $metaData->ogTitle : "Lentera Digital Indonesia"?>" />
	<meta property="og:description" content="<?= isset($metaData->ogDescription) ? $metaData->ogDescription : "Koperasi Simpan Pinjam di Makassar"?>" />
	<meta property="og:image" content="<?= isset($metaData->ogImage) ? base_url('/assets/resources/articles/'.$metaData->ogImage) : base_url('/assets/images/resources/logo-1.png')?>" />

	<!--favicon-->
	<link rel="apple-touch-icon" sizes="180x180" href="<?=base_url('assets/')?>resources/images/favicons/apple-touch-icon.png" />
	<link rel="icon" type="image/png" sizes="32x32" href="<?=base_url('assets/')?>resources/images/favicons/favicon-32x32.png" />
	<link rel="icon" type="image/png" sizes="16x16" href="<?=base_url('assets/')?>resources/images/favicons/favicon-16x16.png" />
	<link rel="manifest" href="<?=base_url('assets/')?>resources/images/favicons/site.webmanifest" />

	<!-- Title -->
	<title><?= $session == 'Member' ? "Anggota":"Pengurus"; ?> | Lentera Digital Indonesia</title>

	<!-- Styles -->
	<link href="https://fonts.googleapis.com/css?family=Lato:400,700,900&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">
	<link href="<?= base_url('assets/') ?>admin/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?= base_url('assets/') ?>admin/plugins/font-awesome/css/all.min.css" rel="stylesheet">
	<link href="<?= base_url('assets/') ?>admin/plugins/DataTables/datatables.min.css" rel="stylesheet">
	<link href="<?= base_url('assets/') ?>admin/plugins/select2/css/select2.min.css" rel="stylesheet">
	<link href="<?= base_url('assets/') ?>admin/plugins/notifications/css/lobibox.min.css" rel="stylesheet"/>

	<!-- Theme Styles -->
	<link href="<?= base_url('assets/') ?>admin/css/connect.min.css" rel="stylesheet">
	<link href="<?= base_url('assets/') ?>admin/css/dark_theme.css" rel="stylesheet">
	<link href="<?= base_url('assets/') ?>admin/css/icons.css" rel="stylesheet">
	<link href="<?= base_url('assets/') ?>admin/css/custom.css" rel="stylesheet">
	<link href="<?= base_url('assets/') ?>admin/css/custom_scroll.css" rel="stylesheet">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<?php $controller = $this->uri->segment(1);
$modul = $this->uri->segment(2);
$params = $this->uri->segment(3);
$params1 = $this->uri->segment(4);
$type = $this->input->get('type');
$alwaysOpen = true;?>
<body class="<?= isset($appProfile) ? "app-profile":""; ?>">
<div class='loader'>
	<div class='spinner-grow text-primary' role='status'>
		<span class='sr-only'>Loading...</span>
	</div>
</div>
<div class="connect-container align-content-stretch d-flex flex-wrap">
	<div class="page-sidebar">
		<div class="logo-box"><a href="#" class="logo-text">LENTERA</a><a href="#" id="sidebar-close"><i class="material-icons">close</i></a> <a href="#" id="sidebar-state"><i class="material-icons">adjust</i><i class="material-icons compact-sidebar-icon">panorama_fish_eye</i></a></div>
		<div class="page-sidebar-inner slimscroll">
			<ul class="accordion-menu">
				<li class="<?= $alwaysOpen == true ? "open" : ""?>">
					<a href="#"><i class="material-icons">text_format</i>Akun<i class="material-icons has-sub-menu">add</i></a>
					<ul class="sub-menu">
						<li>
							<div class="text-center">
								<div class="card-body">
									<img src="<?= base_url('assets/') ?>admin/images/avatars/profile-image.png" width="50" style="padding-bottom: 20px">
									<p class="card-text"><?= $this->session->userdata('member_full_name'); ?></p>
									<hr/>
									<div class="row">
										<div class="col-md-6">
											<span>Dompet</span>
										</div>
										<div class="col-md-6">
											<span>Tabungan</span>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<small class="js-currencyFormatter" data-price="<?= current($this->FinanceModel->memberFinance($this->session->userdata("member_id")))->totalWalletBalance; ?>"></small>
										</div>
										<div class="col-md-6">
											<small class="js-currencyFormatter" data-price="<?= current($this->FinanceModel->memberFinance($this->session->userdata("member_id")))->totalTabunganBalance; ?>"></small>
										</div>
									</div>
									<hr/>
								</div>
								<div class="row">
									<div class="col-lg-6">
										<a href="<?= base_url('member-deposit')?>" class="btn btn-xs btn-success" style="opacity:100%; padding: 7% 15% !important;">Deposit</a>
									</div>
									<div class="col-lg-6">
										<a href="<?= base_url('member/withdrawal/index')?>" class="btn btn-xs btn-primary" style="opacity:100%; padding: 7% 15% !important;">Withdraw</a>
									</div>
								</div>
							</div>
						</li>
					</ul>
				</li>
				<li class="sidebar-title">
					Halaman Utama
				</li>
				<li class="<?= ($modul == 'dashboard' ? 'active-page' : '') ?>">
					<a href="<?= base_url("member/dashboard") ?>" class="active"><i class="material-icons-outlined">dashboard</i>Dashboard</a>
				</li>
				<li class="<?= ($modul == 'keanggotaan' ? 'open' : '') ?>">
					<a href="#"><i class="material-icons">text_format</i>Keanggotaan<i class="material-icons has-sub-menu">add</i></a>
					<ul class="sub-menu">
						<li class="<?= ($modul == 'keanggotaan' && $params == 'index' ? 'active-page' : '') ?>">
							<a href="<?= base_url('member/keanggotaan/index')?>">Daftar Anggota</a>
						</li>
						<li class="<?= ($modul == 'keanggotaan' && $params == 'aturan' ? 'active-page' : '') ?>">
							<a href="<?= base_url('member/keanggotaan/aturan')?>">Aturan Dan Sanksi Anggota</a>
						</li>
						<li class="<?= ($modul == 'keanggotaan' && $params == 'ad-art' ? 'active-page' : '') ?>">
							<a href="<?= base_url('member/keanggotaan/ad-art')?>">AD/ART</a>
						</li>
					</ul>
				</li>
				<li class="<?= ($modul == 'simpanan' ? 'open' : '') ?>" >
					<a href="#"><i class="material-icons">text_format</i>Simpanan<i class="material-icons has-sub-menu">add</i></a>
					<ul class="sub-menu">
						<li class="<?= ($modul == 'simpanan' && $params == 'index' ? 'active-page' : '') ?>">
							<a href="<?= base_url('member/simpanan/index')?>">Simpanan Saya</a>
						</li>
						<li class="<?= ($modul == 'simpanan' && $params == 'program-funding' ? 'active-page' : '') ?>">
							<a href="<?= base_url('member/simpanan/program-funding/index')?>">Program Funding</a>
						</li>
					</ul>
				</li>
				<li class="<?= ($modul == 'pinjaman' ? 'open' : '') ?>" >
					<a href="#"><i class="material-icons">card_giftcard</i>Pinjaman<i class="material-icons has-sub-menu">add</i></a>
					<ul class="sub-menu">
						<li class="<?= ($modul == 'pinjaman' && $params == 'summary' ? 'active-page' : '') ?>">
							<a href="<?= base_url('member/pinjaman/summary')?>">Pinjaman saya</a>
						</li>
						<li class="<?= ($modul == 'pinjaman' && $params == 'pengajuan' ? 'active-page' : '') ?>">
							<a href="<?= base_url('member/pinjaman/pengajuan')?>">Pengajuan Pinjaman</a>
						</li>
						<li class="<?= ($modul == 'pinjaman' && $params == 'pembayaran' ? 'active-page' : '') ?>">
							<a href="<?= base_url('member/pinjaman/pembayaran')?>">Bayar Pinjaman</a>
						</li>
						<li class="<?= ($modul == 'pinjaman' && $params == 'laporan' ? 'active-page' : '') ?>">
							<a href="<?= base_url('member/pinjaman/laporan/pembayaran')?>">Laporan Pembayaran</a>
						</li>
					</ul>
				</li>
				<li class="<?= ($modul == 'tabungan' ? 'open' : '') ?>">
					<a href="#"><i class="material-icons">card_giftcard</i>Tabungan<i class="material-icons has-sub-menu">add</i></a>
					<ul class="sub-menu">
						<li class="<?= ($modul == 'tabungan' && $params == 'summary' ? 'active-page' : '') ?>">
							<a href="<?= base_url('member/tabungan/summary') ?>">Tabungan Saya</a>
						</li>
						<li class="<?= ($modul == 'tabungan' && $params == 'buka-tabungan' ? 'active-page' : '') ?>">
							<a href="<?= base_url('member/tabungan/buka-tabungan') ?>">Buka Tabungan Baru</a>
						</li>
						<li class="<?= ($modul == 'tabungan' && $params == 'daftar-pemindahan' ? 'active-page' : '') ?>">
							<a href="<?= base_url('member/tabungan/daftar-pemindahan') ?>">Daftar Pemindahan Tabungan</a>
						</li>
						<li class="<?= ($modul == 'tabungan' && $params == 'pengajuan-pemindahan' ? 'active-page' : '') ?>">
							<a href="<?= base_url('member/tabungan/pengajuan-pemindahan') ?>">Pengajuan Pemindahan Tabungan</a>
						</li>
						<li class="<?= ($modul == 'tabungan' && $type == strtolower(MemberTabunganReportType::TABUNGAN_REPORT_MUTASI_TABUNGAN)  ? 'active-page' : '') ?>">
							<a href="<?= base_url('member/tabungan/laporan?type=') . strtolower(MemberTabunganReportType::TABUNGAN_REPORT_MUTASI_TABUNGAN) ?>">Mutasi Tabungan</a>
						</li>
						<li class="<?= ($modul == 'tabungan' && $type == strtolower(MemberTabunganReportType::TABUNGAN_REPORT_IMBAL_JASA) ? 'active-page' : '') ?>">
							<a href="<?= base_url('member/tabungan/laporan?type=') . strtolower(MemberTabunganReportType::TABUNGAN_REPORT_IMBAL_JASA) ?>">Mutasi Imbal Jasa</a>
						</li>
					</ul>
				</li>

				<li class="sidebar-title">
					Halaman Hadiah
				</li>
				<li class="<?= (urldecode($modul) == "onlineShop" ? 'active-page' : '') ?>">
					<a href="<?= base_url('member/onlineShop/index')?>"><i class="material-icons-outlined">dashboard</i>Online Shop</a>
				</li>
<!--				<li>-->
<!--					<a href="#"><i class="material-icons-outlined">dashboard</i>Reward</a>-->
<!--				</li>-->
				<li class="<?= ($modul == 'checkout' ? 'open' : '') ?>">
					<a href="#"><i class="material-icons">card_giftcard</i>Checkout Product<i class="material-icons has-sub-menu">add</i></a>
					<ul class="sub-menu">
						<li class="<?= ($type == strtolower(MemberCheckoutProductApproval::CHECKOUT_NEW) ? 'active-page' : '') ?>">
							<a href="<?= base_url('member/checkout/product?type=') . strtolower(MemberCheckoutProductApproval::CHECKOUT_NEW) ?>">Permintaan Checkout</a>
						</li>
						<li class="<?= ($type == strtolower(MemberCheckoutProductApproval::CHECKOUT_PROCESSED) ? 'active-page' : '') ?>">
							<a href="<?= base_url('member/checkout/product?type=') . strtolower(MemberCheckoutProductApproval::CHECKOUT_PROCESSED) ?>">Checkout Telah Diproses</a>
						</li>
						<li class="<?= ($type == strtolower(MemberCheckoutProductApproval::CHECKOUT_CANCEL) ? 'active-page' : '') ?>">
							<a href="<?= base_url('member/checkout/product?type=') . strtolower(MemberCheckoutProductApproval::CHECKOUT_CANCEL) ?>">Checkout Dibatalkan</a>
						</li>
					</ul>
				</li>
				<li class="sidebar-title">
					Halaman Anggota
				</li>
				<li class="<?= ($controller == 'member-profile' ? 'open' : '') ?>">
					<a href="#"><i class="material-icons">card_giftcard</i>Akun<i class="material-icons has-sub-menu">add</i></a>
					<ul class="sub-menu">
						<li class="<?= ($controller == 'member-profile' ? 'active-page' : '') ?>">
							<a href="<?= base_url('member-profile')?>">Profile</a>
						</li>
						<li>
							<a href="<?= base_url('member/logout'); ?>">Logout</a>
						</li>
					</ul>
				</li>
				<?php if ($this->session->userdata("member_email") == "abdillahsatari@gmail.com"){?>
				<li class="sidebar-title">
					Testing Member Page
				</li>
				<li class="<?= ($controller == ' cronJobs' ? 'open' : '') ?>">
					<a href="#"><i class="material-icons">card_giftcard</i>Testing<i class="material-icons has-sub-menu">add</i></a>
					<ul class="sub-menu">
						<li class="<?= ($controller == 'cronJobs' ? 'active-page' : '') ?>">
							<a href="<?= base_url('cronJobs/index')?>">Testing Cron Jobs</a>
						</li>
					</ul>
				</li>
				<?php }?>

				<br/><br/><br/>
			</ul>
		</div>
	</div>
	<div class="page-container">
		<div class="page-header">
			<nav class="navbar navbar-expand">
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<ul class="navbar-nav">
					<li class="nav-item small-screens-sidebar-link">
						<a href="#" class="nav-link"><i class="material-icons-outlined">menu</i></a>
					</li>
					<li class="nav-item nav-profile dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<img src="<?= base_url('assets/') ?>admin/images/avatars/profile-image-1.png" alt="profile image">
							<span><?= $this->session->userdata('member_full_name'); ?></span><i class="material-icons dropdown-icon">keyboard_arrow_down</i>
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
							<a class="dropdown-item" href="<?= base_url('member-profile')?>">Profiles</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?= base_url('member/logout'); ?>">Log out</a>
						</div>
					</li>
				</ul>
				<div class="collapse navbar-collapse" id="navbarNav">
					<ul class="navbar-nav">
						<li class="nav-item dropdown">
							<a href="#" class="nav-link dropdown-toggle" id="navbarNotifDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="material-icons-outlined">notifications
								</i><span class="badge badge-pill badge-info float-right"><?= current($this->ActivityLog->getMemberNotification())->dataCountNotif ?></span>
							</a>
							<?php
							$notification = current($this->ActivityLog->getMemberNotification());
							if ( $notification->dataCountNotif ){?>
									<div class="dropdown-menu dropdown-menu-lg-right dropdown-secondary"aria-labelledby="navbarNotifDropdown">
									<?php foreach ($notification->dataNotif as $notif ){?>
										<a class="dropdown-item waves-effect waves-light" href="<?= base_url('member/notification/setStatus/'.$notif->id) ?>"><?= $notif->notif_description ?></a>
									<?php }?>
								</div>
							<?php } ?>
						</li>

						<li class="nav-item">
							<a href="#" class="nav-link" id="dark-theme-toggle"><i class="material-icons-outlined">brightness_2</i><i class="material-icons">brightness_2</i></a>
						</li>
						<li class="nav-item small-screens-sidebar-link">
							<a href="#" class="nav-link"><i class="material-icons-outlined">menu</i></a>
						</li>
					</ul>
				</div>
<!--				<div class="navbar-search">-->
<!--					<form>-->
<!--						<div class="form-group">-->
<!--							<input type="text" name="search" id="nav-search" placeholder="Search...">-->
<!--						</div>-->
<!--					</form>-->
<!--				</div>-->
			</nav>
		</div>
		<div class="page-content">

			<?php if (isset($appProfile)) {?>
				<div class="main-wrapper">
					<div class="profile-header">
						<div class="row">
							<div class="col">
								<div class="profile-img">
									<img src="<?= base_url('assets/') ?>admin/images/avatars/profile-image-1.png">
								</div>
								<div class="profile-name">
									<h3><?= $this->session->userdata('member_full_name')?></h3>
									<span>Anggota Koperasi</span>
								</div>
								<div class="profile-menu">
									<ul>
										<li>
											<a href="<?= base_url('member-profile'); ?>" class="<?= ($modul == '' ? 'text-primary' : '') ?>">Profile</a>
										</li>
										<li>
										<li>
											<a href="<?= base_url('member-profile/edit/'); ?><?= $this->session->userdata('member_id');?>" class="<?= ($modul == 'edit' ? 'text-primary' : '') ?>"><?= $this->session->userdata("member_is_kyc") == false ? "Verifikasi Data" : "Edit Profile"?></a>
										</li>
										<li>
											<a href="<?= base_url('member/profile/password'); ?>" class="<?= ($params == 'password' ? 'text-primary' : '') ?>" >Ganti Password & Rekening </a>
										</li>
										<li>
											<a href="<?= base_url('member/logout'); ?>">Logout</a>
										</li>
									</ul>
									<div class="profile-status">
										<i class="active-now"></i> Active now
									</div>
								</div>
							</div>
						</div>
					</div>

					<?php $this->load->view($content);?>

				</div>

			<?php } else {
				$this->load->view($content);
			}?>

		</div>
		<div class="page-footer">
			<div class="row">
				<div class="col-md-12">
					<span class="footer-text">Lentera Digital Indonesia © 2022</span>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Javascripts -->
<script src="<?= base_url('assets/') ?>admin/plugins/jquery/jquery.min.js"></script>
<script src="<?= base_url('assets/') ?>admin/plugins/jquery-validation/jquery-validate.min.js"></script>
<script src="<?= base_url('assets/') ?>admin/plugins/jquery-validation/additional-methods.min.js"></script>
<script src="<?= base_url('assets/') ?>admin/plugins/bootstrap/popper.min.js"></script>
<script src="<?= base_url('assets/') ?>admin/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="<?= base_url('assets/') ?>admin/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="<?= base_url('assets/') ?>admin/plugins/DataTables/datatables.min.js"></script>
<script src="<?= base_url('assets/') ?>admin/plugins/select2/js/select2.full.min.js"></script>
<script src="<?= base_url('assets/') ?>admin/js/connect.min.js"></script>
<script src="<?= base_url('assets/') ?>admin/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
<script src="<?= base_url('assets/') ?>admin/plugins/apexcharts/dist/apexcharts.min.js"></script>
<script src="<?= base_url('assets/') ?>admin/plugins/blockui/jquery.blockUI.js"></script>
<script src="<?= base_url('assets/') ?>admin/plugins/flot/jquery.flot.min.js"></script>
<script src="<?= base_url('assets/') ?>admin/plugins/flot/jquery.flot.time.min.js"></script>
<script src="<?= base_url('assets/') ?>admin/plugins/flot/jquery.flot.symbol.min.js"></script>
<script src="<?= base_url('assets/') ?>admin/plugins/flot/jquery.flot.resize.min.js"></script>
<script src="<?= base_url('assets/') ?>admin/plugins/flot/jquery.flot.tooltip.min.js"></script>
<script src="<?= base_url('assets/') ?>admin/plugins/notifications/js/lobibox.min.js"></script>
<script src="<?= base_url('assets/') ?>admin/plugins/notifications/js/notifications.min.js"></script>
<script src="<?= base_url('assets/') ?>admin/plugins/notifications/js/notification-custom-script.js"></script>
<script src="<?= base_url('assets/') ?>admin/js/application_member.js?ver=0.2"></script>

</body>
</html>
