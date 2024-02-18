<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>PT. Teknologi Finansial Berjangka | Official Mail</title>
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
	<style type="text/css">
		html {
			-webkit-text-size-adjust: none;
			-ms-text-size-adjust: none;
		}

		@media only screen and (min-device-width: 750px) {
			.table750 {
				width: 750px !important;
			}
		}

		@media only screen and (max-device-width: 750px),
		only screen and (max-width: 750px) {
			table[class="table750"] {
				width: 100% !important;
			}

			.mob_b {
				width: 93% !important;
				max-width: 93% !important;
				min-width: 93% !important;
			}

			.mob_b1 {
				width: 100% !important;
				max-width: 100% !important;
				min-width: 100% !important;
			}

			.mob_left {
				text-align: left !important;
			}

			.mob_soc {
				width: 50% !important;
				max-width: 50% !important;
				min-width: 50% !important;
			}

			.mob_menu {
				width: 50% !important;
				max-width: 50% !important;
				min-width: 50% !important;
				box-shadow: inset -1px -1px 0 0 rgba(255, 255, 255, 0.2);
			}

			.mob_center {
				text-align: center !important;
			}

			.top_pad {
				height: 15px !important;
				max-height: 15px !important;
				min-height: 15px !important;
			}

			.mob_pad {
				width: 15px !important;
				max-width: 15px !important;
				min-width: 15px !important;
			}

			.mob_div {
				display: block !important;
			}
		}

		@media only screen and (max-device-width: 550px),
		only screen and (max-width: 550px) {
			.mod_div {
				display: block !important;
			}
		}

		.table750 {
			width: 750px;
		}

		#credentials {
			border-collapse: collapse;
			width: 100%;
		}

		#credentials td {
			border-top: 1px solid #ddd;
			border-bottom: 1px solid #ddd;
			padding: 5px;
		}

		#credentials tbody {
			border-left: 1px solid #ddd;
			border-right: 1px solid #ddd;
			padding: 8px;
		}

		#credentials tr:nth-child(odd) {
			background-color: #f2f2f2;
		}
	</style>
</head>

<body style="margin: 0; padding: 0;">

<table cellpadding="0" cellspacing="0" border="0" width="100%" style="background: #f3f3f3; min-width: 350px; font-size: 1px; line-height: normal;">
	<tr>
		<td align="center" valign="top">
			<!--[if (gte mso 9)|(IE)]>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr><td align="center" valign="top" width="750"><![endif]-->
			<table cellpadding="0" cellspacing="0" border="0" width="750" class="table750" style="width: 100%; max-width: 750px; min-width: 350px; background: #f3f3f3;">
				<tr>
					<td class="mob_pad" width="25" style="width: 25px; max-width: 25px; min-width: 25px;">&nbsp;</td>
					<td align="center" valign="top" style="background: #ffffff;">

						<table cellpadding="0" cellspacing="0" border="0" width="100%" style="width: 100% !important; min-width: 100%; max-width: 100%; background: #f3f3f3;">
							<tr>
								<td align="right" valign="top">
									<div class="top_pad" style="height: 25px; line-height: 25px; font-size: 23px;">&nbsp;</div>
								</td>
							</tr>
						</table>

						<table cellpadding="0" cellspacing="0" border="0" width="88%" style="width: 88% !important; min-width: 88%; max-width: 88%;">
							<tr>
								<td align="left" valign="top">
									<div style="height: 39px; line-height: 39px; font-size: 37px;">&nbsp;</div>
									<div style="height: 73px; line-height: 73px; font-size: 71px;">
										<img style="height: 60px; display: block; margin-left: auto; margin-right: auto;" alt="" src="cid:logo">
									</div>
								</td>
							</tr>
						</table>

						<table cellpadding="0" cellspacing="0" border="0" width="88%" style="width: 88% !important; min-width: 88%; max-width: 88%;">
							<tr>
								<td align="left" valign="top">
									<font face="'Source Sans Pro', sans-serif" color="#1a1a1a" style="font-size: 52px; line-height: 60px; font-weight: 300; letter-spacing: -1.5px;">
										<span style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif;color: #1a1a1a;font-size: 36px;line-height: 60px;font-weight: 300;letter-spacing: -1.5px;"><?= $user['emailHeadline'] ?></span>
									</font>
									<div style="height: 33px; line-height: 33px; font-size: 18px;">&nbsp;</div>
									<font face="'Source Sans Pro', sans-serif" color="#585858" style="font-size: 18px; line-height: 32px;">
                                 <span style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #585858; font-size: 18px; line-height: 32px;">

                                    Dear <?= $user['memberFullName']; ?>, Permintaan Reset Password Anda Telah Kami Terima. Berikut Adalah Password Baru Akun Anda.

                                    <div style="height: 33px; line-height: 33px; font-size: 31px;">&nbsp;</div>

                                    <table id="credentials">
                                       <tr>
                                          <td>Email</td>
                                          <td>:</td>
                                          <td><?= $user['emaiRecipient'] ?></td>
                                       </tr>
                                       <tr>
                                          <td>Password</td>
                                          <td>:</td>
                                          <td><?= $user['memberPassword'] ?></td>
                                       </tr>
                                    </table>

									<br>
                                    	Setelah berhasil login, anda dapat melakukan reset password dihalaman kabinet anda untuk mengganti password baru dengan sesuatu yang lebih mudah untuk anda ingat.
									<br>
                                 </span>
									</font>
									<div style="height: 20px; line-height: 20px; font-size: 18px;">&nbsp;</div>

									<div style="height: 75px; line-height: 75px; font-size: 73px;">&nbsp;</div>

								</td>
							</tr>
						</table>

						<table cellpadding="0" cellspacing="0" border="0" width="90%" style="width: 90% !important; min-width: 90%; max-width: 90%; border-width: 1px; border-style: solid; border-color: #e8e8e8; border-bottom: none; border-left: none; border-right: none;">
							<tr>
								<td align="left" valign="top">
									<div style="height: 15px; line-height: 15px; font-size: 13px;">&nbsp;</div>
								</td>
							</tr>
						</table>

						<table cellpadding="0" cellspacing="0" border="0" width="100%" style="width: 100% !important; min-width: 100%; max-width: 100%; background: #f3f3f3;">
							<tr>
								<td align="center" valign="top">
									<div style="height: 34px; line-height: 34px; font-size: 32px;">&nbsp;</div>
									<table cellpadding="0" cellspacing="0" border="0" width="88%" style="width: 88% !important; min-width: 88%; max-width: 88%;">
										<tr>
											<td align="center" valign="top">
												<table cellpadding="0" cellspacing="0" border="0" width="78%" style="min-width: 300px;">
													<tr>
														<td align="center" valign="top" width="23%">
															<a href="#" target="_blank" style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #1a1a1a; font-size: 14px; line-height: 20px; text-decoration: none; white-space: nowrap; font-weight: bold;">
																<font face="'Source Sans Pro', sans-serif" color="#1a1a1a" style="font-size: 14px; line-height: 20px; text-decoration: none; white-space: nowrap; font-weight: bold;">
																	<span style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #1a1a1a; font-size: 14px; line-height: 20px; text-decoration: none; white-space: nowrap; font-weight: bold;">HELP&nbsp;CENTER</span>
																</font>
															</a>
														</td>
														<td align="center" valign="top" width="10%">
															<font face="'Source Sans Pro', sans-serif" color="#1a1a1a" style="font-size: 17px; line-height: 17px; font-weight: bold;">
																<span style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #1a1a1a; font-size: 17px; line-height: 17px; font-weight: bold;">&bull;</span>
															</font>
														</td>
														<td align="center" valign="top" width="23%">
															<a href="#" target="_blank" style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #1a1a1a; font-size: 14px; line-height: 20px; text-decoration: none; white-space: nowrap; font-weight: bold;">
																<font face="'Source Sans Pro', sans-serif" color="#1a1a1a" style="font-size: 14px; line-height: 20px; text-decoration: none; white-space: nowrap; font-weight: bold;">
																	<span style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #1a1a1a; font-size: 14px; line-height: 20px; text-decoration: none; white-space: nowrap; font-weight: bold;">SUPPORT&nbsp;24/7</span>
																</font>
															</a>
														</td>
														<td align="center" valign="top" width="10%">
															<font face="'Source Sans Pro', sans-serif" color="#1a1a1a" style="font-size: 17px; line-height: 17px; font-weight: bold;">
																<span style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #1a1a1a; font-size: 17px; line-height: 17px; font-weight: bold;">&bull;</span>
															</font>
														</td>
														<td align="center" valign="top" width="23%">
															<a href="https://my.tfx.co.id/kabinet" target="_blank" style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #1a1a1a; font-size: 14px; line-height: 20px; text-decoration: none; white-space: nowrap; font-weight: bold;">
																<font face="'Source Sans Pro', sans-serif" color="#1a1a1a" style="font-size: 14px; line-height: 20px; text-decoration: none; white-space: nowrap; font-weight: bold;">
																	<span style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #1a1a1a; font-size: 14px; line-height: 20px; text-decoration: none; white-space: nowrap; font-weight: bold;">ACCOUNT</span>
																</font>
															</a>
														</td>
													</tr>
												</table>
												<div style="height: 34px; line-height: 34px; font-size: 32px;">&nbsp;</div>
												<font face="'Source Sans Pro', sans-serif" color="#868686" style="font-size: 17px; line-height: 20px;">
													<span style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #868686; font-size: 17px; line-height: 20px;">Copyright &copy; <?= date('Y'); ?> Feducation ID. All&nbsp;Rights&nbsp;Reserved. We&nbsp;appreciate&nbsp;you!</span>
												</font>
												<div style="height: 3px; line-height: 3px; font-size: 1px;">&nbsp;</div>
												<!--												<font face="'Source Sans Pro', sans-serif" color="#1a1a1a" style="font-size: 17px; line-height: 20px;">-->
												<!--													<span style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #1a1a1a; font-size: 17px; line-height: 20px;"><a target="_blank" style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #1a1a1a; font-size: 17px; line-height: 20px; text-decoration: none;">support@tfx.co.id</a> &nbsp;&nbsp;|&nbsp;&nbsp; <a href="#" target="_blank" style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #1a1a1a; font-size: 17px; line-height: 20px; text-decoration: none;">021 - 4066 -0888</a> &nbsp;&nbsp;|&nbsp;&nbsp; <a href="#" target="_blank" style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #1a1a1a; font-size: 17px; line-height: 20px; text-decoration: none;">021 - 5050 -9100 </a></span>-->
												<!--												</font>-->
												<div style="height: 35px; line-height: 35px; font-size: 33px;">&nbsp;</div>
												<div style="height: 35px; line-height: 35px; font-size: 33px;">&nbsp;</div>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>

					</td>
					<td class="mob_pad" width="25" style="width: 25px; max-width: 25px; min-width: 25px;">&nbsp;</td>
				</tr>
			</table>
			<!--[if (gte mso 9)|(IE)]>
			</td></tr>
			</table><![endif]-->
		</td>
	</tr>
</table>
</body>

</html>
