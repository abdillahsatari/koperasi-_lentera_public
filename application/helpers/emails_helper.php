<?php
defined('BASEPATH') or exit('No direct script access allowed');


function sendEmail($params){

	$ci = &get_instance();

	switch ($params['emailType']) {
		case EmailType::NEW_MEMBER_REGISTRATION:
			$dataMsg['user']	= array('memberFullName'		=> $params['memberFullName'],
										'emailRecipient'		=> $params['emailRecipient'],
										'memberPhoneNumber'		=> $params['memberPhoneNumber'],
										'memberPassword'		=> $params['memberPassword'],
										'memberTransactionCode'	=> $params['memberTransactionCode'],
										'memberToken'			=> $params['memberToken']);

			$emailBody 			= $ci->load->view('_emailTemplate/email_member_verification', $dataMsg, true);
			break;
		case EmailType::FORGOT_PASSWORD:
			$dataMsg['user']	= array('emailHeadline'		=> $params['emailSubject'],
										'memberFullName'	=> $params['memberFullName'],
										'emailRecipient'	=> $params['emailRecipient'],
										'memberPassword'	=> $params['memberPassword'],
										'memberToken'		=> $params['memberToken']);

			$emailBody 			= $ci->load->view('_emailTemplate/email_member_forgot_password', $dataMsg, true);
			break;
		case EmailType::NEW_ADMIN_REGISTRATION:
			$dataMsg['user']	= array('adminFullName'			=> $params['adminFullName'],
										'emailRecipient'		=> $params['emailRecipient'],
										'adminPhoneNumber'		=> $params['adminPhoneNumber'],
										'adminPassword'			=> $params['adminPassword'],
										'adminTransactionCode'	=> $params['adminTransactionCode'],
										'adminToken'			=> $params['adminToken']);

			$emailBody 			= $ci->load->view('_emailTemplate/email_admin_verification', $dataMsg, true);
			break;
	};

	$ci->load->library('phpmailer_lib');
	$mail = $ci->phpmailer_lib->load();

	/*=============================*/
	/*

	USE THIS IN DEVELOPEMENT MODE TO PREVENT EMAIL HOSTING LIMITATION

	host: 
	email:
	password: 

	*/
	/*=============================*/
	/*=============================*/
	/*

	USE THIS IN PRODUCTION MODE

	host: 
	email:
	password:

	*/
	/*=============================*/


	$mail->isSMTP();
	$mail->Host     	= 'dadidugroup.com';
	$mail->SMTPAuth 	= true;
	$mail->Username 	= 'no-reply@dadidugroup.com';
	$mail->Password 	= 'No-reply@2024';
	$mail->SMTPSecure 	= 'ssl';
	$mail->Port     	= 465; // 465/587

	$mail->SMTPOptions = array('ssl' => array('verify_peer' => false,
											  'verify_peer_name' => false,
											  'allow_self_signed' => true));

	$mail->setFrom('no-reply@dadidugroup.com', 'KSP Dadidu Group');
	$mail->addReplyTo('support@dadidugroup.com ');
	$mail->addAddress($params['emailRecipient']);
	$mail->Subject = $params['emailSubject'];
	$mail->isHTML(TRUE);
	$mail->Body = $emailBody;

	if ($mail->send()) {
		$result = array('isDelivered' 	=> TRUE,
					 	'errorMessage'	=> 'No Error',
					 	'status' 		=> 'Send Email Successfully');
	} else {
		$result = array('isDelivered' 	=> FALSE,
					    'errorMessage'	=> $mail->ErrorInfo,
					 	'status' 		=> 'Failed to Send Email');
	}

	return $result;
};
