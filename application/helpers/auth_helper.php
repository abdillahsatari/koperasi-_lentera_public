<?php
defined('BASEPATH') or exit('No direct script access allowed');


function authenticate($username, $password, $suffix){

	$ci = &get_instance();

	if ($suffix == CredentialType::ADMIN){
		$where	= array(''.$suffix.'_email'			=> $username,
						''.$suffix.'_is_verified' 	=> true,
						''.$suffix.'_status' 		=> true,);
	} else {
		$where	= array(''.$suffix.'_phone_number'=> $username,
						''.$suffix.'_is_verified' => true);
	}

	$dataUser = $ci->CrudModel->gw($suffix, $where);

	if (count($dataUser)>0) {

		$dataCollected		= current($dataUser);
		$recordedEmail		= "$suffix"."_email";
		$recordedImage		= "$suffix"."_image";
		$recordedPhoneNumber= "$suffix"."_phone_number";
		$recordedFullName	= "$suffix"."_full_name";
		$recordedPassword 	= "$suffix"."_password";
		$recordedKycStatus	= "$suffix"."_is_kyc";
		$recordedIsActivated= $suffix == CredentialType::MEMBER ? "$suffix"."_is_activated" : "$suffix"."_status";

		$isGrantedPassword 	= $dataCollected->$recordedPassword;

		if (password_verify($password, $isGrantedPassword)) {

			$collectAdminRole = NULL;

			if ($suffix == CredentialType::ADMIN) {
				$queryUserRole		= 'SELECT AR.role_type FROM admin A JOIN admin_role AR ON A.admin_role_id = AR.id WHERE A.id = "'.$dataCollected->id.'"';
				$dataUserRole		= $ci->CrudModel->q	($queryUserRole);
				$collectAdminRole	= current($dataUserRole)->role_type;
			}

			$suffix == 'user' ? $sess_kcfinder = 'KCFINDER' : $sess_kcfinder = 'ses_member';

			$dataSession = array("$suffix"."_id" 			=> $dataCollected->id,
								"$suffix"."_email"			=> $dataCollected->$recordedEmail,
								"$suffix"."_image"			=> $dataCollected->$recordedImage,
								"$suffix"."_full_name"		=> $dataCollected->$recordedFullName,
								"$suffix"."_phone_number"	=> $dataCollected->$recordedPhoneNumber,
								"$suffix"."_is_kyc"			=> $dataCollected->$recordedKycStatus,
								"$suffix"."_is_activated"	=> $dataCollected->$recordedIsActivated,
								"$suffix"."_authStatus" 	=> AuthStatus::AUTHORIZED,
								"$sess_kcfinder"			=> array('disabled' => false),
								"$suffix"."_role_type" 		=> $collectAdminRole ?: "Member");

			$ci->ActivityLog->authLog($dataCollected->id, $suffix);

			$result = array('status' 		=> AuthStatus::AUTHORIZED,
							'message'		=> array("info"	=> "Login Berhasil !!",
							"msg"			=> "Selamat Datang ".$dataSession["$suffix"."_full_name"]."",
							"type"			=> AllertType::SUCCESS),
							'dataSession'	=> $dataSession);

		} else {
			$result = array('status' 	=> AuthStatus::UNAUTHORIZED,
							'message'	=> array("info"	=> "Login Gagal !!",
												 "msg"	=> "Mohon Periksa Kembali Password Anda",
												 "type"	=> AllertType::FAILED));
		}
	} else {
		$uid			= $suffix == CredentialType::MEMBER ? "No. Hp" : "Email";
		$dataMessage 	= 'Mohon Periksa Kembali '.$uid.' Anda.';
		$result 		= array('status' 	=> AuthStatus::UNAUTHORIZED,
								'message'	=> array("info"	=> "Login Gagal !!",
													 "msg"	=> $dataMessage,
													 "type"	=> AllertType::FAILED));
	}

	return $result;
	die();
}

function authenticateMemberTrCode($memberId, $transactionCode){

	$ci = &get_instance();

	$where			= array("id" => $memberId);
	$dataMember		= $ci->CrudModel->gw("member", $where);
	$MemberTrCode	= current($dataMember)->member_transaction_code;

	if (password_verify($transactionCode, $MemberTrCode)){
		$result = array('status' => AuthStatus::AUTHORIZED);
	} else {
		$result = array('status' => AuthStatus::UNAUTHORIZED);
	}

	return $result;
	die();
}
