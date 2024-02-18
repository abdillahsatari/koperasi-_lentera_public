<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="apple-touch-icon" sizes="180x180" href="<?=base_url('assets/')?>admin/images/favicons/apple-touch-icon.png" />
	<link rel="icon" type="image/png" sizes="32x32" href="<?=base_url('assets/')?>admin/images/favicons/favicon-32x32.png" />
	<link rel="icon" type="image/png" sizes="16x16" href="<?=base_url('assets/')?>admin/images/favicons/favicon-16x16.png" />
	<link rel="manifest" href="<?=base_url('assets/')?>admin/images/favicons/site.webmanifest" />
	<!--plugins-->
	<link href="<?= base_url('assets/') ?>admin/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="<?= base_url('assets/') ?>admin/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="<?= base_url('assets/') ?>admin/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<!-- loader-->
	<!-- loader-->
	<link href="<?= base_url('assets/') ?>admin/css/pace.min.css" rel="stylesheet" />
	<script src="<?= base_url('assets/') ?>admin/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="<?= base_url('assets/') ?>admin/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?= base_url('assets/') ?>admin/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="<?= base_url('assets/') ?>admin/css/app.css" rel="stylesheet">
	<link href="<?= base_url('assets/') ?>admin/css/icons.css" rel="stylesheet">
	<title>Feducation Id - Sekolah Trading Terbaik di Indonesia</title>
</head>

<body class="bg-theme bg-theme2">
<!-- wrapper -->
<div class="wrapper">
	<div class="authentication-forgot d-flex align-items-center justify-content-center">
		<div class="card forgot-box">
			<div class="card-body">
				<div class="p-4 rounded  border">
					<form method="POST" action="<?= base_url('member-forgot-password')?>">
						<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
						<div class="text-center">
							<img src="<?= base_url('assets/')?>admin/images/icons/forgot-2.png" width="120" alt="" />
						</div>
						<h4 class="mt-5 font-weight-bold">Lupa PAssword?</h4>
						<p class="">Masukkan Email Anda</p>
						<?= $this->session->flashdata('message') ?>
						<div class="my-4">
							<label class="form-label">Email</label>
							<input type="text" class="form-control form-control-lg" placeholder="Masukkan Email" name="email" />
							<small><?= form_error('email')?></small>
						</div>
						<div class="d-grid gap-2">
							<button type="button" class="btn btn-light btn-lg">Kirim</button> <a href="<?= base_url('member/login')?>" class="btn btn-light btn-lg"><i class='bx bx-arrow-back me-1'></i>Kembali ke Halaman Login</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end wrapper -->
<!--start switcher-->
<!--end switcher-->

<!-- Bootstrap JS -->
<script src="<?= base_url('assets/') ?>admin/js/bootstrap.bundle.min.js"></script>
<!--plugins-->
<script src="<?= base_url('assets/') ?>admin/js/jquery.min.js"></script>
<script src="<?= base_url('assets/') ?>admin/plugins/simplebar/js/simplebar.min.js"></script>
<script src="<?= base_url('assets/') ?>admin/plugins/metismenu/js/metisMenu.min.js"></script>
<script src="<?= base_url('assets/') ?>admin/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
<!--app JS-->
<script src="<?= base_url('assets/') ?>admin/js/app.js"></script>

</body>

</html>
