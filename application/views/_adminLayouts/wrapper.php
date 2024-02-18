<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="<?= isset($metaData->ogDescription) ? $metaData->ogDescription : "Koperasi Simpan Pinjam di Makassar" ?>">
	<meta name="keyword" content="<?= isset($metaData->ogKeyword) ? $metaData->ogKeyword : "koperasi, simpan pinjam, koperasi di makassar, lentera, lentera digital" ?>">
	<meta name="author" content="Lentera Digital Indonesia">

	<meta property="og:url" content="<?= isset($metaData->ogUrl) ? $metaData->ogUrl : "https://www.lenteradigitalindonesia.com" ?>" />
	<meta property="og:title" content="<?= isset($metaData->ogTitle) ? $metaData->ogTitle : "Lentera Digital Indonesia" ?>" />
	<meta property="og:description" content="<?= isset($metaData->ogDescription) ? $metaData->ogDescription : "Koperasi Simpan Pinjam di Makassar" ?>" />
	<meta property="og:image" content="<?= isset($metaData->ogImage) ? base_url('/assets/resources/articles/' . $metaData->ogImage) : base_url('/assets/images/resources/logo-1.png') ?>" />

	<!--favicon-->
	<link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('assets/') ?>resources/images/favicons/apple-touch-icon.png" />
	<link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/') ?>resources/images/favicons/favicon-32x32.png" />
	<link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/') ?>resources/images/favicons/favicon-16x16.png" />
	<link rel="manifest" href="<?= base_url('assets/') ?>resources/images/favicons/site.webmanifest" />

	<!-- Title -->
	<title><?= $session == 'Member' ? "Anggota" : "Pengurus"; ?> | Lentera Digital Indonesia</title>

	<!-- Styles -->
	<link href="https://fonts.googleapis.com/css?family=Lato:400,700,900&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">
	<link href="<?= base_url('assets/') ?>admin/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?= base_url('assets/') ?>admin/plugins/font-awesome/css/all.min.css" rel="stylesheet">
	<link href="<?= base_url('assets/') ?>admin/plugins/DataTables/datatables.min.css" rel="stylesheet">
	<link href="<?= base_url('assets/') ?>admin/plugins/select2/css/select2.min.css" rel="stylesheet">
	<link href="<?= base_url('assets/') ?>admin/plugins/notifications/css/lobibox.min.css" rel="stylesheet" />

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
$type = $this->input->get('type') ?>

<body class="<?= isset($appProfile) ? "app-profile" : ""; ?>">
	<!--<div class='loader'>-->
	<!--	<div class='spinner-grow text-primary' role='status'>-->
	<!--		<span class='sr-only'>Loading...</span>-->
	<!--	</div>-->
	<!--</div>-->
	<div class="connect-container align-content-stretch d-flex flex-wrap">
		<div class="page-sidebar">
			<div class="logo-box"><a href="#" class="logo-text">LENTERA</a><a href="#" id="sidebar-close"><i class="material-icons">close</i></a> <a href="#" id="sidebar-state"><i class="material-icons">adjust</i><i class="material-icons compact-sidebar-icon">panorama_fish_eye</i></a></div>
			<div class="page-sidebar-inner slimscroll">
				<ul class="accordion-menu">
					<li class="sidebar-title">
						Utama
					</li>
					<li class="<?= ($modul == 'dashboard' ? 'active-page' : '') ?>">
						<a href="<?= base_url("admin/dashboard") ?>" class="active"><i class="material-icons-outlined">dashboard</i>Dashboard</a>
					</li>
					<li class="<?= ($modul == 'keanggotaan' ? 'open' : '') ?>">
						<a href="#"><i class="material-icons">text_format</i>Keanggotaan<i class="material-icons has-sub-menu">add</i></a>
						<ul class="sub-menu">
							<li class="<?= ($modul == 'keanggotaan' && $params == 'create' ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/keanggotaan/create') ?>">Form Pendaftaran Anggota Baru</a>
							</li>
							<li class="<?= ($type == strtolower(MemberStatus::REGISTERED) ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/keanggotaan/index?type=') . strtolower(MemberStatus::REGISTERED) ?>">Daftar Calon Anggota</a>
							</li>
							<li class="<?= ($type == strtolower(MemberStatus::ACTIVE) ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/keanggotaan/index?type=') . strtolower(MemberStatus::ACTIVE) ?>">Daftar Anggota Aktif</a>
							</li>
							<li class="<?= ($modul == 'keanggotaan' && $params == 'report' ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/keanggotaan/report') ?>">Semua Transaksi Anggota</a>
							</li>
						</ul>
					</li>
					<li class="<?= ($modul == 'simpanan' ? 'open' : '') ?>">
						<a href="#"><i class="material-icons">text_format</i>Simpanan<i class="material-icons has-sub-menu">add</i></a>
						<ul class="sub-menu">
							<li class="<?= ($modul == 'simpanan' && $params == 'content' ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/simpanan/content/index') ?>">Program Simpanan</a>
							</li>
							<li class="<?= ($modul == 'simpanan' && $params == 'laporan' ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/simpanan/laporan/index') ?>">Laporan Simpanan</a>
							</li>
						</ul>
					</li>
					<li class="<?= ($modul == 'pinjaman' ? 'open' : '') ?>">
						<a href="#"><i class="material-icons">card_giftcard</i>Pinjaman<i class="material-icons has-sub-menu">add</i></a>
						<ul class="sub-menu">
							<li class="<?= ($modul == 'pinjaman' && $params == 'content' ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/pinjaman/content/index') ?>">Program Pinjaman</a>
							</li>
							<li class="<?= ($modul == 'pinjaman' && $params == 'permohonan-pinjaman' ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/pinjaman/permohonan-pinjaman') ?>">Permohonan Pinjaman</a>
							</li>
							<li class="<?= ($modul == 'pinjaman' && $params == 'semua-pinjaman' || $params == 'detail'  ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/pinjaman/semua-pinjaman') ?>">Semua Pinjaman</a>
							</li>
							<li class="<?= ($modul == 'pinjaman' && $params == 'pembayaran-pinjaman' ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/pinjaman/pembayaran-pinjaman') ?>">Semua Pembayaran Pinjaman</a>
							</li>
						</ul>
					</li>
					<li class="<?= ($modul == 'tabungan' ? 'open' : '') ?>">
						<a href="#"><i class="material-icons">card_giftcard</i>Tabungan<i class="material-icons has-sub-menu">add</i></a>
						<ul class="sub-menu">
							<li class="<?= ($modul == 'tabungan' && $params == 'pengaturan-program' ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/tabungan/pengaturan-program') ?>">Pengaturan Program Tabungan</a>
							</li>
							<li class="<?= ($modul == 'tabungan' && $type == strtolower(MemberTabunganApproval::TABUNGAN_NEW) ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/tabungan/anggota?type=') . strtolower(MemberTabunganApproval::TABUNGAN_NEW) ?>">Permintaan Tabungan</a>
							</li>
							<li class="<?= ($modul == 'tabungan' && $type == strtolower(MemberTabunganApproval::TABUNGAN_PROCESSED) ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/tabungan/anggota?type=') . strtolower(MemberTabunganApproval::TABUNGAN_PROCESSED) ?>">Tabungan Diterima</a>
							</li>
							<li class="<?= ($modul == 'tabungan' && $type == strtolower(MemberTabunganApproval::TABUNGAN_REJECTED) ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/tabungan/anggota?type=') . strtolower(MemberTabunganApproval::TABUNGAN_REJECTED) ?>">Tabungan Ditolak</a>
							</li>
							<li class="<?= ($modul == 'tabungan' && $type == strtolower(MemberTabunganTfApproval::TABUNGAN_TF_NEW) ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/tabungan/transfer-tabungan?type=') . strtolower(MemberTabunganTfApproval::TABUNGAN_TF_NEW) ?>">Permintaan Transfer Tabungan</a>
							</li>
							<li class="<?= ($modul == 'tabungan' && $type == strtolower(MemberTabunganTfApproval::TABUNGAN_TF_PROCESSED) ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/tabungan/transfer-tabungan?type=') . strtolower(MemberTabunganTfApproval::TABUNGAN_TF_PROCESSED) ?>">Transfer Tabungan Diterima</a>
							</li>
							<li class="<?= ($modul == 'tabungan' && $type == strtolower(MemberTabunganTfApproval::TABUNGAN_TF_REJECTED) ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/tabungan/transfer-tabungan?type=') . strtolower(MemberTabunganTfApproval::TABUNGAN_TF_REJECTED) ?>">Transfer Tabungan Ditolak</a>
							</li>
							<li class="<?= ($modul == 'tabungan' && $type == strtolower(MemberTabunganReportType::TABUNGAN_REPORT_MUTASI_TABUNGAN)  ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/tabungan/laporan?type=') . strtolower(MemberTabunganReportType::TABUNGAN_REPORT_MUTASI_TABUNGAN)?>">Mutasi Tabungan</a>
							</li>
							<li class="<?= ($modul == 'tabungan' && $type == strtolower(MemberTabunganReportType::TABUNGAN_REPORT_IMBAL_JASA) ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/tabungan/laporan?type=') . strtolower(MemberTabunganReportType::TABUNGAN_REPORT_IMBAL_JASA) ?>">Mutasi Imbal Jasa</a>
							</li>
						</ul>
					</li>
					<li class="<?= ($modul == 'deposit' ? 'open' : '') ?>">
						<a href="#"><i class="material-icons">card_giftcard</i>Deposit<i class="material-icons has-sub-menu">add</i></a>
						<ul class="sub-menu">
							<li class="<?= (urldecode($params) == strtolower(MemberDepositStatus::DEPOSIT_NEW) ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/deposit/') . strtolower(MemberDepositStatus::DEPOSIT_NEW) ?>">Deposit Baru</a>
							</li>
							<li class="<?= (urldecode($params) == strtolower(MemberDepositStatus::DEPOSIT_PROCESSED) ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/deposit/') . strtolower(MemberDepositStatus::DEPOSIT_PROCESSED) ?>">Deposit Telah Diproses</a>
							</li>
							<li class="<?= (urldecode($params) == strtolower(MemberDepositStatus::DEPOSIT_PENDING) ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/deposit/') . strtolower(MemberDepositStatus::DEPOSIT_PENDING) ?>">Deposit Pending</a>
							</li>
						</ul>
					</li>
					<li class="<?= ($modul == 'withdrawal' ? 'open' : '') ?>">
						<a href="#"><i class="material-icons">card_giftcard</i>Withdrawal<i class="material-icons has-sub-menu">add</i></a>
						<ul class="sub-menu">
							<li class="<?= (urldecode($params) == strtolower(MemberWdStatus::WD_NEW) ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/withdrawal/') . strtolower(MemberWdStatus::WD_NEW) ?>">Permintaan Withdrawal</a>
							</li>
							<li class="<?= (urldecode($params) == strtolower(MemberWdStatus::WD_PROCESSED) ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/withdrawal/') . strtolower(MemberWdStatus::WD_PROCESSED) ?>">Withdrawal Telah Diproses</a>
							</li>
							<li class="<?= (urldecode($params) == strtolower(MemberWdStatus::WD_CANCEL) ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/withdrawal/') . strtolower(MemberWdStatus::WD_CANCEL) ?>">Withdrawal Dibatalkan</a>
							</li>
						</ul>
					</li>
					<li class="sidebar-title">
						Hadiah
					</li>
					<li class="<?= (urldecode($modul) == "olshop" ? 'active-page' : '') ?>">
						<a href="<?= base_url('admin/olshop/index') ?>"><i class="material-icons-outlined">dashboard</i>Online Shop</a>
					</li>
<!--					<li>-->
<!--						<a href="#"><i class="material-icons-outlined">dashboard</i>Reward</a>-->
<!--					</li>-->
					<li class="<?= ($modul == 'checkout' ? 'open' : '') ?>">
						<a href="#"><i class="material-icons">card_giftcard</i>Checkout Product<i class="material-icons has-sub-menu">add</i></a>
						<ul class="sub-menu">
							<li class="<?= ($type == strtolower(MemberCheckoutProductApproval::CHECKOUT_NEW) ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/checkout/product?type=') . strtolower(MemberCheckoutProductApproval::CHECKOUT_NEW) ?>">Permintaan Checkout</a>
							</li>
							<li class="<?= ($type == strtolower(MemberCheckoutProductApproval::CHECKOUT_PROCESSED) ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/checkout/product?type=') . strtolower(MemberCheckoutProductApproval::CHECKOUT_PROCESSED) ?>">Checkout Telah Diproses</a>
							</li>
							<li class="<?= ($type == strtolower(MemberCheckoutProductApproval::CHECKOUT_CANCEL) ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/checkout/product?type=') . strtolower(MemberCheckoutProductApproval::CHECKOUT_CANCEL) ?>">Checkout Dibatalkan</a>
							</li>
						</ul>
					</li>
					<li class="sidebar-title">
						Pengaturan
					</li>
					<li class="<?= (urldecode($modul) == "pengurus" ? 'open' : '') ?>">
						<a href="#"><i class="material-icons">card_giftcard</i>Pengurus<i class="material-icons has-sub-menu">add</i></a>
						<ul class="sub-menu">
							<li class="<?= (urldecode($modul) == "pengurus" && urldecode($params) == "create" ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/pengurus/create') ?>">Tambah Pengurus</a>
							</li>
							<li class="<?= (urldecode($modul) == "pengurus" && urldecode($params) == "index" ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/pengurus/index') ?>">Semua Pengurus</a>
							</li>
						</ul>
					</li>
					<li class="<?= ($modul == 'setting' ? 'open' : '') ?>">
						<a href="#"><i class="material-icons">card_giftcard</i>Pengaturan<i class="material-icons has-sub-menu">add</i></a>
						<ul class="sub-menu">
							<li class="<?= ($params == "basic" ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/setting/basic') ?>">Pengaturan Umum</a>
							</li>
							<li class="<?= ($params == "company" ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin/setting/company') ?>">Pengaturan Koperasi</a>
							</li>
						</ul>
					</li>
					<li class="<?= ($controller == 'admin-profile' ? 'open' : '') ?>">
						<a href="#"><i class="material-icons">card_giftcard</i>Akun<i class="material-icons has-sub-menu">add</i></a>
						<ul class="sub-menu">
							<li class="<?= ($controller == 'admin-profile' ? 'active-page' : '') ?>">
								<a href="<?= base_url('admin-profile') ?>">Profile</a>
							</li>
							<li>
								<a href="<?= base_url('pengurus/logout') ?>">Logout</a>
							</li>
						</ul>
					</li>
					<br /><br /><br />
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
								<span><?= $this->session->userdata('admin_full_name'); ?></span><i class="material-icons dropdown-icon">keyboard_arrow_down</i>
							</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="<?= base_url('admin-profile') ?>">Profiles</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?= base_url('pengurus/logout'); ?>">Log out</a>
							</div>
						</li>
					</ul>
					<div class="collapse navbar-collapse" id="navbarNav">
						<ul class="navbar-nav">
							<li class="nav-item dropdown">
								<a href="#" class="nav-link dropdown-toggle" id="navbarNotifDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="material-icons-outlined">notifications
									</i><span class="badge badge-pill badge-info float-right"><?= current($this->ActivityLog->getAdminNotification())->dataCountNotif ?></span>
								</a>
								<?php
								$notification = current($this->ActivityLog->getAdminNotification());
								if ($notification->dataCountNotif) { ?>
									<div class="dropdown-menu dropdown-menu-lg-right dropdown-secondary" aria-labelledby="navbarNotifDropdown">
										<hr>
										<?php foreach ($notification->dataNotif as $notif) {?>
											<a class="dropdown-item waves-effect waves-light" href="<?= base_url('pengurus/notification/setStatus/'.$notif->id) ?>"><?= $notif->notif_description ?></a>
											<hr>
										<?php } ?>
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

				<?php if (isset($appProfile)) { ?>
					<div class="main-wrapper">
						<div class="profile-header">
							<div class="row">
								<div class="col">
									<div class="profile-img">
										<img src="<?= base_url('assets/') ?>admin/images/avatars/profile-image-1.png">
									</div>
									<div class="profile-name">
										<h3><?= $this->session->userdata('admin_full_name') ?></h3>
										<span>Admin Koperasi</span>
									</div>
									<div class="profile-menu">
										<ul>
											<li>
												<a href="<?= base_url('admin-profile'); ?>" class="<?= ($modul == '' ? 'text-primary' : '') ?>">Profile</a>
											</li>
											<li>
											<li>
												<a href="<?= base_url('admin-profile/edit/'); ?><?= $this->session->userdata('admin_id'); ?>" class="<?= ($modul == 'edit' ? 'text-primary' : '') ?>"><?= $this->session->userdata("admin_is_kyc") == false ? "Verifikasi Data" : "Edit Profile" ?></a>
											</li>
											<li>
												<a href="<?= base_url('pengurus/profile/password'); ?>" class="<?= ($params == 'password' ? 'text-primary' : '') ?>">Ganti Password</a>
											</li>
											<li>
												<a href="<?= base_url('pengurus/logout'); ?>">Logout</a>
											</li>
										</ul>
										<div class="profile-status">
											<i class="active-now"></i> Active now
										</div>
									</div>
								</div>
							</div>
						</div>

						<?php $this->load->view($content); ?>

					</div>

				<?php } else {
					$this->load->view($content);
				} ?>

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
	<script src="<?= base_url('assets/'); ?>admin/plugins/ckeditor/ckeditor.js"></script>
	<script src="<?= base_url('assets/') ?>admin/js/application_admin.js?ver=0.2"></script>

</body>

</html>
