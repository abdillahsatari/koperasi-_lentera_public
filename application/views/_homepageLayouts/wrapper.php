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
	<title><?= $title ?> | Lentera Digital Indonesia</title>

	<!-- Plugin css -->
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/') ?>css/font-awesome.min.css" media="all" />
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/') ?>fonts/flaticon.css" media="all" />
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/') ?>css/bootstrap.min.css" media="all" />
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/') ?>css/animate.css" media="all" />
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/') ?>css/swiper.min.css" media="all" />
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/') ?>css/lightcase.css" media="all" />
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/') ?>css/jquery.nstSlider.css" media="all" />
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/') ?>css/flexslider.css" media="all" />

	<!-- own style css -->
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/') ?>css/style.css" media="all" />
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/') ?>css/rtl.css" media="all" />
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/') ?>css/responsive.css" media="all" />
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/') ?>css/custom.css" media="all" />

</head>

<body id="page-top" data-spy="scroll">
<div class="box-layout">
	<div class="box-style">
		<div class="color-btn">
			<a href="#"><i class="fa fa-cog fa-spin" aria-hidden="true"></i></a>
		</div>
		<div class="box-style-inner">
			<h3>Box Layout</h3>
			<div class="box-element">
				<div class="box-heading">
					<h5>HTML Layout</h5>
				</div>
				<div class="box-content">
					<ul class="box-customize">
						<li><a class="boxed-btn" href="#">Boxed</a></li>
						<li><a class="wide-btn" href="#">Wide</a></li>
						<li><a class="rtl-btn" href="#">Rtl</a></li>
						<li><a class="ltl-btn" href="#">Ltl</a></li>
					</ul>
				</div>
			</div>
			<div class="box-element">
				<div class="box-heading">
					<h5>Backgroud Images</h5>
				</div>
				<div class="box-content">
					<ul class="box-bg-img">
						<li>
							<a class="bg-1" href="#"><img src="<?= base_url('assets/') ?>images/box-style/01.jpg" alt=""></a>
						</li>
						<li>
							<a class="bg-2" href="#"><img src="<?= base_url('assets/') ?>images/box-style/02.jpg" alt=""></a>
						</li>
						<li>
							<a class="bg-3" href="#"><img src="<?= base_url('assets/') ?>images/box-style/03.jpg" alt=""></a>
						</li>
						<li>
							<a class="bg-4" href="#"><img src="<?= base_url('assets/') ?>images/box-style/04.jpg" alt=""></a>
						</li>
						<li>
							<a class="bg-5" href="#"><img src="<?= base_url('assets/') ?>images/box-style/05.jpg" alt=""></a>
						</li>
						<li>
							<a class="bg-6" href="#"><img src="<?= base_url('assets/') ?>images/box-style/06.jpg" alt=""></a>
						</li>
						<li>
							<a class="bg-7" href="#"><img src="<?= base_url('assets/') ?>images/box-style/07.jpg" alt=""></a>
						</li>
						<li>
							<a class="bg-8" href="#"><img src="<?= base_url('assets/') ?>images/box-style/08.jpg" alt=""></a>
						</li>
					</ul>
				</div>
			</div>
			<div class="box-element">
				<div class="box-heading">
					<h5>Backgroud Pattern</h5>
				</div>
				<div class="box-content">
					<ul class="box-pattern-img">
						<li>
							<a class="pt-1" href="#"><img src="https://www.codexcoder.com/images/auror/pt-image/01.png"" alt=""></a>
						</li>
						<li>
							<a class="pt-2" href="#"><img src="https://www.codexcoder.com/images/auror/pt-image/02.png"" alt=""></a>
						</li>
						<li>
							<a class="pt-3" href="#"><img src="https://www.codexcoder.com/images/auror/pt-image/03.png"" alt=""></a>
						</li>
						<li>
							<a class="pt-4" href="#"><img src="https://www.codexcoder.com/images/auror/pt-image/04.png"" alt=""></a>
						</li>
						<li>
							<a class="pt-5" href="#"><img src="https://www.codexcoder.com/images/auror/pt-image/05.png"" alt=""></a>
						</li>
						<li>
							<a class="pt-6" href="#"><img src="https://www.codexcoder.com/images/auror/pt-image/06.png"" alt=""></a>
						</li>
						<li>
							<a class="pt-7" href="#"><img src="https://www.codexcoder.com/images/auror/pt-image/07.png"" alt=""></a>
						</li>
						<li>
							<a class="pt-8" href="#"><img src="https://www.codexcoder.com/images/auror/pt-image/08.png"" alt=""></a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<header class="header-style-2 one-page">
		<div class="bg-header-top">
			<div class="container">
				<div class="row">
					<div class="header-top">
						<ul class="h-contact">
							<li><i class="flaticon-time-left"></i> Email : koperasilenteradigital@gmail.com</li>
							<li><i class="flaticon-vibrating-phone"></i> Phone : +62 0852 555 08278</li>
							<li><i class="flaticon-placeholder"></i> Address : JL. Datuk Ditiro II No. 43 Makassar Sulawesi – Selatan</li>
						</ul>
						<!-- .donate-option -->
					</div>
					<!-- .header-top -->
				</div>
				<!-- .header-top -->
			</div>
			<!-- .container -->
		</div>
		<!-- .bg-header-top -->

		<!-- Start Menu -->
		<div class="bg-main-menu menu-scroll">
			<div class="container">
				<div class="row">
					<div class="main-menu">
						<div class="main-menu-bottom">
							<div class="navbar-header">
								<a class="navbar-brand" href="#"><img src="<?= base_url('assets/') ?>resources/images/homepage/logo_header_2.png" alt="logo" class="img-responsive" /></a>
								<button type="button" class="navbar-toggler collapsed d-lg-none" data-bs-toggle="collapse" data-bs-target="#bs-example-navbar-collapse-1" aria-controls="bs-example-navbar-collapse-1" aria-expanded="false">
                                        <span class="navbar-toggler-icon">
                                            <i class="fa fa-bars"></i>
                                        </span>
								</button>
							</div>
							<div class="main-menu-area">
								<div class="navbar-collapse collapse" id="bs-example-navbar-collapse-1">
									<ul>
										<li><a href="<?= base_url('homepage') ?>" class="page-scroll">Home</a></li>
										<li><a href="<?= base_url('tentang-kami') ?>" class="page-scroll">Tentang</a></li>
										<li><a href="<?= base_url('visi-misi') ?>" class="page-scroll">Visi Dan Misi</a></li>
										<li><a href="<?= base_url('ad-art') ?>" class="page-scroll">AD/ART</a></li>
										<li><a href="<?= base_url('struktur-organisasi') ?>" class="page-scroll">Struktur Organisasi</a></li>
										<li><a href="<?= base_url('program-koperasi') ?>" class="page-scroll">Program Kami</a></li>
										<li><a href="<?= base_url('member/login') ?>" class="page-scroll">Login</a></li>
									</ul>
									<div class="menu-right-option pull-right">

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- .bg-main-menu -->
	</header>

	<!-- /Start Content -->

	<?php $this->load->view($content); ?>

	<!-- ./ End Content -->

	<!-- Start Footer Section -->
	<footer>
		<div class="bg-footer-top">
			<div class="container">
				<div class="row">
					<div class="footer-top">
						<div class="row">
							<div class="col-lg-4 col-sm-6">
								<div class="footer-widgets">
									<div class="widgets-title">
										<a class="navbar-brand" href="#"><img src="<?= base_url('assets/') ?>resources/images/homepage/logo_header.png" alt="logo" class="img-responsive" /></a>
									</div>
									<!-- .widgets-title -->
									<div class="widgets-content">
										<p>Koperasi Digital yang Tansparan dan memudahkan anggota untuk mengakses segala aktivitas usaha koperasi.</p>
									</div>
								</div>
								<!-- .footer-widgets -->
							</div>
							<!-- .col-lg-3 -->
							<div class="col-lg-4 col-sm-6">
								<div class="footer-widgets">
									<div class="widgets-title">
										<h3>Hubugngi Kami</h3>
									</div>
									<!-- .widgets-content -->
									<div class="address-box">
										<ul class="address">
											<li>
												<i class="fa fa-home" aria-hidden="true"></i>
												<span>JL. Datuk Ditiro II No. 43 Makassar Sulawesi – Selatan.</span>
											</li>
											<li>
												<i class="fa fa-phone" aria-hidden="true"></i>
												<span>+62 0852 555 08278.</span>
											</li>
											<li>
												<i class="fa fa-envelope-o" aria-hidden="true"></i>
												<span>koperasilenteradigital@gmail.com</span>
											</li>
										</ul>
									</div>
									<!-- .address -->
								</div>
								<!-- .footer-widgets -->
							</div>
							<!-- .col-lg-3 -->
							<div class="col-lg-4 col-sm-6">
								<div class="footer-widgets">
									<div class="widgets-title">
										<h3>Tentang Kami</h3>
									</div>
									<!-- .widgets-title -->
									<ul class="twitter-widget">
										<li>
											<div class="twitter-icon"><i class="fa fa-external-link-square" aria-hidden="true"></i></div>
											<div class="twitter-content">
												<a href="<?= base_url('tentang-kami')?>">
													<h5>Tentang</h5>
												</a>
											</div>
											<!-- .twitter-content -->
										</li>
										<li>
											<div class="twitter-icon"><i class="fa fa-external-link-square" aria-hidden="true"></i></div>
											<div class="twitter-content">
												<a href="<?= base_url('visi-misi')?>">
													<h5>Visi Misi</h5>
												</a>
											</div>
											<!-- .twitter-content -->
										</li>
										<li>
											<div class="twitter-icon"><i class="fa fa-external-link-square" aria-hidden="true"></i></div>
											<div class="twitter-content">
												<a href="<?= base_url('program-koperasi')?>">
													<h5>Program Kami</h5>
												</a>
											</div>
											<!-- .twitter-content -->
										</li>
									</ul>
								</div>
								<!-- .footer-widgets -->
							</div>
						</div>
						<!-- .row -->
					</div>
					<!-- .footer-top -->
				</div>
				<!-- .row -->
			</div>
			<!-- .container -->
		</div>
		<!-- .bg-footer-top -->

		<div class="bg-footer-bottom">
			<div class="container">
				<div class="row">
					<div class="footer-bottom">
						<div class="copyright-txt">
							<p>&copy; 2022. Copyrigth <a href="#" title="Al-Amin(Web Designer)">Lentera Digital Indonesia</a></p>
						</div>
					</div>
					<!-- .footer-bottom -->
				</div>
				<!-- .row -->
			</div>
			<!-- .container -->
		</div>
		<!-- .bg-footer-bottom -->

	</footer>
	<!-- End Footer Section -->


	<!-- Start Scrolling -->
	<div class="scroll-img"><i class="fa fa-angle-up" aria-hidden="true"></i></div>
	<!-- End Scrolling -->


</div>

<!-- Start Pre-Loader-->

<div id="loading">
	<div id="loading-center">
		<div id="loading-center-absolute">
			<div class="object" id="object_one"></div>
			<div class="object" id="object_two"></div>
			<div class="object" id="object_three"></div>
			<div class="object" id="object_four"></div>
		</div>
	</div>
</div>


<!-- End Pre-Loader -->




<script type="text/javascript" src="<?= base_url('assets/') ?>js/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="<?= base_url('assets/') ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= base_url('assets/') ?>js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="<?= base_url('assets/') ?>js/jquery.waypoints.min.js"></script>
<script type="text/javascript" src="<?= base_url('assets/') ?>js/jquery.counterup.min.js"></script>
<script type="text/javascript" src="<?= base_url('assets/') ?>js/swiper.min.js"></script>
<script type="text/javascript" src="<?= base_url('assets/') ?>js/plugins.isotope.js"></script>
<script type="text/javascript" src="<?= base_url('assets/') ?>js/isotope.pkgd.min.js"></script>
<script type="text/javascript" src="<?= base_url('assets/') ?>js/lightcase.js"></script>
<script type="text/javascript" src="<?= base_url('assets/') ?>js/jquery.nstSlider.js"></script>
<script type="text/javascript" src="<?= base_url('assets/') ?>js/jquery.flexslider.js"></script>
<script type="text/javascript" src="<?= base_url('assets/') ?>js/custom.isotope.js"></script>
<script type="text/javascript" src="<?= base_url('assets/') ?>js/custom.map.js"></script>

<script type="text/javascript" src="<?= base_url('assets/') ?>js/custom.js"></script>

<!-- Map Api -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBqVIkdttPNjl5c5hKlc_Hk3bfXQQlf2Rc&callback=initMap">


</script>


</body>

</html>
