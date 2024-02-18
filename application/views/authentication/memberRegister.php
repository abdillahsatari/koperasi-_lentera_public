<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="<?= isset($metaData->ogDescription) ? $metaData->ogDescription : "Feducation adalah Lembaga Pendidikan non formal yang berkonsentrasi pada pelatihan Forex Trading guna membantu investor dan trader pemula maupun masyarakat umum untuk belajar dan menekuni bisnis perdagangan berjangka sebagai salah satu Bisnis Perdagangan secara global. Didirikan pada 2 Februari 2022"?>">
	<meta name="keyword" content="<?= isset($metaData->ogKeyword) ? $metaData->ogKeyword : "Trading Forex, Sekolah Digital Forex, Sekolah Trader, Sekolah Trading, Feducation, Feducation Id, Forex, XAUSD, Cryptocurrency, Member Feducation"?>">
	<meta name="author" content="Feducation Id">

	<meta property="og:url" content="<?= isset($metaData->ogUrl) ? $metaData->ogUrl : "https://www.feducation.id"?>" />
	<meta property="og:title" content="<?= isset($metaData->ogTitle) ? $metaData->ogTitle : "Feducation Id | Sekolah Digital Forex | Sekolah Forex | Sekolah Trading | Sekolah Trader"?>" />
	<meta property="og:description" content="<?= isset($metaData->ogDescription) ? $metaData->ogDescription : "Feducation adalah Lembaga Pendidikan non formal yang berkonsentrasi pada pelatihan Forex Trading guna membantu investor dan trader pemula maupun masyarakat umum untuk belajar dan menekuni bisnis perdagangan berjangka sebagai salah satu Bisnis Perdagangan secara global. Didirikan pada 2 Februari 2022"?>" />
	<meta property="og:image" content="<?= isset($metaData->ogImage) ? base_url('/assets/resources/articles/'.$metaData->ogImage) : base_url('/assets/images/resources/logo-1.png')?>" />

	<!--favicon-->
	<link rel="apple-touch-icon" sizes="180x180" href="<?=base_url('assets/')?>resources/images/favicons/apple-touch-icon.png" />
	<link rel="icon" type="image/png" sizes="32x32" href="<?=base_url('assets/')?>resources/images/favicons/favicon-32x32.png" />
	<link rel="icon" type="image/png" sizes="16x16" href="<?=base_url('assets/')?>resources/images/favicons/favicon-16x16.png" />
	<link rel="manifest" href="<?=base_url('assets/')?>resources/images/favicons/site.webmanifest" />

	<!-- Title -->
	<title>Pendaftaran Anggota Lentera Digital Indonesia </title>

	<!-- Styles -->
	<link href="https://fonts.googleapis.com/css?family=Lato:400,700,900&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">
	<link href="<?= base_url('assets/') ?>admin/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?= base_url('assets/') ?>admin/plugins/font-awesome/css/all.min.css" rel="stylesheet">


	<!-- Theme Styles -->
	<link href="<?= base_url('assets/') ?>admin/css/connect.min.css" rel="stylesheet">
	<link href="<?= base_url('assets/') ?>admin/css/dark_theme.css" rel="stylesheet">
	<link href="<?= base_url('assets/') ?>admin/css/custom.css" rel="stylesheet">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body class="auth-page sign-in">

<div class='loader'>
	<div class='spinner-grow text-primary' role='status'>
		<span class='sr-only'>Loading...</span>
	</div>
</div>
<div class="connect-container align-content-stretch d-flex flex-wrap">
	<div class="container-fluid d-flex align-items-center justify-content-center my-10 my-lg-0">
		<div class="col-lg-6">
			<div class="auth-form">
				<div class="row">
					<div class="col">
						<!-- with card layout -->
						<div class="card">
							<div class="card-header">
								<div class="float-left">
									<div class="logo-box">
										<a class="logo-text">Pendaftaran Anggota</a>
									</div>
								</div>
								<div class="float-right">
									<img src="<?= base_url('assets/') ?>admin/images/bg-auth.png" width="50" alt="" />
								</div>
							</div>
							<div class="card-body">
								<?= $this->session->flashdata('message') ?>
								<form action="<?= base_url('member/register')?>"
									  method="POST" class="js-form_registration"
									  data-url="<?= base_url('member/MemberAjax/verifyMemberPhoneNumber')?>">
									<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
									<input type="hidden" name="session_type" value="<?= $session?>">
									<div class="form-group">
										<input type="text" class="form-control" id="member_full_name" name="member_full_name" value="<?=set_value('member_full_name')?>" placeholder="Nama Lengkap">
										<small class="text-danger"><?= form_error('member_full_name')?></small>
									</div>
									<div class="form-group">
										<input type="email" class="form-control" id="member_email" name="member_email" value="<?= set_value('member_email')?>" placeholder="Email">
										<small class="text-danger"><?= form_error('member_email')?></small>
									</div>
									<div class="form-group">
										<input type="number" class="form-control" id="member_phone_number" name="member_phone_number" value="<?= set_value('member_phone_number')?>" placeholder="No. Hp">
										<small class="text-danger"><?= form_error('member_phone_number')?></small>
									</div>
									<div class="form-group">
										<input type="number" class="form-control" id="member_ktp_number" name="member_ktp_number" value="<?= set_value('member_ktp_number')?>" placeholder="No. Ktp">
										<small class="text-danger"><?= form_error('member_ktp_number')?></small>
									</div>
									<?php
									if ($this->input->get('r') || set_value('referal_link')) {
										$kode = $this->input->get('r');?>

										<div class="form-group input-group">
											<div class="input-group-prepend">
												<span class="input-group-text btn btn-outline-primary js-copy_btn" id="inputGroupPrepend">
													<div class="col-sm-1">
														Kode Referal
													</div>
												</span>
											</div>
											<input type="text" class="form-control js-targeted_copy_value" id="referal_link" name="referal_link"
												   value="<?= (set_value('referal_link') == '' ? $kode : set_value('referal_link')) ?>"
												   placeholder="Username" aria-describedby="refferal_code">
											<small><?= form_error('referal_link')?></small>
										</div>
									<?php } ?>
									<button type="button" class="btn btn-primary btn-block js-form_action_btn">Daftar</button>
									<br/>
									<div class="text-center">
										<p>Sudah Punya Akun ? <a href="<?= base_url('member/login')?>">Masuk Disini</a></p>
									</div>

								</form>
							</div>
							<div class="card-footer text-center text-muted">
								<p>Lentera Digital Indonesia © 2022</p>
							</div>
						</div>
					</div>
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
<script src="<?= base_url('assets/') ?>admin/js/connect.min.js"></script>
<script src="<?= base_url('assets/') ?>admin/js/application_auth.js"></script>
</body>
</html>
