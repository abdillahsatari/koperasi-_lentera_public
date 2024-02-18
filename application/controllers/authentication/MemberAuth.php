<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class MemberAuth extends CI_Controller {

	public function login() {
		
		if ($this->session->userdata('member_authStatus') == AuthStatus::AUTHORIZED){
			redirect('member/dashboard');
		} else {
			$data	= array("session"	=> SessionType::MEMBER);
			$this->load->view('authentication/index', $data);
		}
	}

	public function authentication(){
		$input		= $this->input->post(NULL, TRUE);
		$this->form_validation->set_rules('uid', 'No. Hp', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');

		if ($this->form_validation->run() == false){
			$this->index();
		} else {
			$suffix		= CredentialType::MEMBER;
			$phoneNumber= $input['uid'];
			$password 	= $input['password'];

			$authService 	= authenticate($phoneNumber, $password, $suffix);
			$dataMsgInfo	= $authService['message']['info'];
			$dataMsg		= $authService['message']['msg'];
			$dataMsgType	= $authService['message']['type'];

			generalAllert($dataMsgInfo, $dataMsg, $dataMsgType);

			switch($authService['status']){
				case AuthStatus::AUTHORIZED:
					$this->session->set_userdata($authService['dataSession']);
					$dataRedirect = 'member/dashboard';
					break;
				case AuthStatus::UNAUTHORIZED:
					$dataRedirect ='member/login';
					break;
			}

			redirect($dataRedirect);
		}
	}

	public function register() {

		$input = $this->input->post(NULL, TRUE);

		$this->form_validation->set_rules('member_full_name', 'Nama Lengkap', 'trim|required');
		$this->form_validation->set_rules('member_email', 'Email', 'trim|required|valid_email|is_unique[member.member_email]',
											array('is_unique'	=> 'Email Sudah Terdaftar!'));
		$this->form_validation->set_rules('member_phone_number', 'No. Hp', 'trim|required|is_unique[member.member_phone_number]',
											array('is_unique'	=> 'No. Hp Sudah Terdaftar!.'));
		$this->form_validation->set_rules('member_ktp_number', 'No. Hp', 'trim|required|is_unique[member_kyc_info.member_ktp_number]',
											array('is_unique'	=> 'No. KTP Sudah Terdaftar!.'));

		if (!empty($input['referal_link'])){
			$this->form_validation->set_rules('referal_link', 'Referral Link', 'trim|callback_referralValidation');
		};

		if ($this->form_validation->run() == false){

			$data	= array("session"	=> SessionType::MEMBER,
				"csrfName" 	=> $this->security->get_csrf_token_name(),
				"csrfToken"	=> $this->security->get_csrf_hash());
			$this->load->view('authentication/memberRegister', $data);

		} else {

			$uplineId	= "";

			if (!empty($input['referal_link'])){
				$referralCode	= array('member_referal_code' => $input['referal_link']);
				$referralOwner 	= $this->CrudModel->gw('member', $referralCode);
				$uplineId		= $referralOwner[0]->id;
			}

			$dataregister	= array('member_full_name'		=> $input['member_full_name'],
									'member_email'			=> $input['member_email'],
									'member_phone_number'	=> $input['member_phone_number']);

			$memberId		= $this->CrudModel->i2('member', $dataregister);

			$getMemberCode				= generateReferralCode($memberId);
			$generatePassword			= generateAutomaticPassword();
			$generateTransactionCode	= generateAutomaticPassword();
			$setMemberPassword			= password_hash($generatePassword, PASSWORD_BCRYPT);
			$setMemberTransactionCode	= password_hash($generateTransactionCode, PASSWORD_BCRYPT);
			$memberCreated				= date('Y-m-d H:i:s');
			$token 						= generateToken();
			$tokenCreated				= time();

			$dataToken		= array('member_id'	=> $memberId,
									'token'		=> $token,
									'created_at'=> $tokenCreated);

			$tokenId 		= $this->CrudModel->i2('member_token', $dataToken);

			$dataKycInfo	= array('member_ktp_number' => $input['member_ktp_number'],
									'member_id'			=> $memberId,
									'created_by'		=> $memberId,
									'created_at'		=> $memberCreated);

			$memberKycId	= $this->CrudModel->i2('member_kyc_info', $dataKycInfo);

			$dataBankAccount= array("member_id"		=> $memberId,
									"created_at"	=> $memberCreated,
									"created_by"	=> $memberId);

			$this->CrudModel->i("member_bank_account", $dataBankAccount);

			$dataSendEmail	= array('memberFullName'		=> $input['member_full_name'],
									'emailRecipient'		=> $input['member_email'],
									'memberPhoneNumber'		=> $input['member_phone_number'],
									'memberPassword'		=> $generatePassword,
									'memberTransactionCode'	=> $generateTransactionCode,
									'memberToken'			=> $token,
									'emailType'				=> EmailType::NEW_MEMBER_REGISTRATION,
									'emailSubject'			=> SubjectEmailType::VERIFIKASI_EMAIL);

			$emailService = sendEmail($dataSendEmail);

			switch ($emailService['isDelivered']){
				case TRUE:
					$dataRegisterUpdate	= array('member_parent_id'				=> $uplineId ?: 1,
												'member_referal_code'			=> $getMemberCode,
												'member_password'				=> $setMemberPassword,
												'member_transaction_code'		=> $setMemberTransactionCode,
												'member_kyc_id'					=> $memberKycId,
												'member_is_email_regist_sent'	=> 1,
												'member_is_registered'			=> 1,
												'created_at'					=> $memberCreated);

					$whereId			= array('id' => $memberId);

					$this->CrudModel->u('member', $dataRegisterUpdate, $whereId);
					$this->SimpananModel->setSimpananContentMemberAnggota($memberId);

					if (!empty($input['referal_link'])) {
						$reportDescription  = "Buat akun dengan kode referal "."[".$input['referal_link']."]";
						$notifDescription	= "[".$getMemberCode."] Mendaftar menggunakan kode referal "."[".$input['referal_link']."]";
					} else {
						$reportDescription  = "Buat Akun Baru";
						$notifDescription	= "[".$getMemberCode."] Mendaftar sebagai anggota baru";
					}

					$data	= array("member_id"				=> $memberId,
									"user_type"				=> CredentialType::MEMBER,
									"receiver"				=> CredentialType::ADMIN,
									"report_description"	=> $reportDescription,
									"notif_description"		=> $notifDescription,
									"reference_link"		=> 'admin/keanggotaan/index?type='.strtolower(MemberStatus::REGISTERED),
									"created_at"			=> $memberCreated);

					$this->ActivityLog->actionLog($data);

					generalAllert("Pendaftaran Berhasil!","Silahkan cek email Anda dan segera melakukan verifikasi 1x24jam.", AllertType::SUCCESS);

					break;
				case FALSE:

					$whereId		= array('id'	=> $memberId);
					$whereKycId		= array('id' 	=> $memberKycId);
					$whereTokenId	= array('id'	=> $tokenId);

					$this->CrudModel->d('member', $whereId);
					$this->CrudModel->d('member_kyc_info', $whereKycId);
					$this->CrudModel->d('member_token', $whereTokenId);

					generalAllert("Pendaftaran Gagal!", "Terjadi kesalahan saat melakukan verifikasi pendaftaran. Silahkan menghubungi pengurus.", AllertType::FAILED);
					break;
			};

			redirect('member/login');
		}
	}

	public function referralValidation($params = null) {
		$getAvailabelReferralCode = $this->CrudModel->gw('member', array('member_referal_code' => $params));
		if (count($getAvailabelReferralCode) > 0) {
			return TRUE;
		} else {
			$this->form_validation->set_message('referralValidation', '{field} tidak ditemukan.');
			return FALSE;
		}
	}

	public function verify(){
		$memberEmail			= $this->input->get('email');
		$memberToken			= $this->input->get('token');
		$memberPassword			= $this->input->get('password');
		$memberPhone			= $this->input->get('phone_number');
		$memberTransactionCode	= $this->input->get('transaction_code');

		$query			= 'SELECT member_token.*, member.member_email, member.member_full_name, member.created_at as member_created_at FROM member
						   JOIN member_token ON member_token.member_id = member.id
						   WHERE member.member_email = "'.$memberEmail.'" AND member_token.token = "'.$memberToken.'"';
		$dataVerify		= $this->CrudModel->q($query);

		if (count($dataVerify) > 0) {
			foreach($dataVerify as $data){

				if (time() - $data->created_at < (60 * 60 * 24)) {
					$memberId			= array('id'	=> $data->member_id);
					$memberDataVerified	= array('member_is_verified'	=> true,
												'updated_at'			=> date('Y-m-d H:i:s'));

					$this->CrudModel->u('member', $memberDataVerified, $memberId);


					$reportDescription  = "[".$this->MemberModel->getMemberReferalCode($data->member_id)."] Melakukan verifikasi email";
					$notifDescription	= "[".$this->MemberModel->getMemberReferalCode($data->member_id)."] Melakukan verifikasi email";

					$data	= array("member_id"				=> $data->member_id,
									"user_type"				=> CredentialType::MEMBER,
									"receiver"				=> CredentialType::ADMIN,
									"report_description"	=> $reportDescription,
									"notif_description"		=> $notifDescription,
									"reference_link"		=> 'admin/keanggotaan/index?type='.strtolower(MemberStatus::REGISTERED),
									"created_at"			=> date('Y-m-d H:i:s'));

					$this->ActivityLog->actionLog($data);

					generalAllert("Verifikasi Berhasil!", "Akun Anda Telah Diaktifkan !.", AllertType::SUCCESS);

					// add current member to all simpanan keanggotaan type
				} else {

					$newToken		= generateToken();
					$tokenCreated	= time();
					$tokenId		= array('id'		=> $data->id);
					$dataToken		= array('member_id'	=> $data->member_id,
						'token'		=> $newToken,
						'created_at'=> $tokenCreated);

					$this->CrudModel->i('member_token', $dataToken);
					$this->CrudModel->d('member_token', $tokenId);

					$dataSendEmail	= array('memberFullName'		=> $data->member_full_name,
						'emailRecipient'		=> $data->member_email,
						'memberPhoneNumber'		=> $memberPhone,
						'memberPassword'		=> $memberPassword,
						'memberTransactionCode'	=> $memberTransactionCode,
						'memberToken'			=> $newToken,
						'emailType'				=> EmailType::NEW_MEMBER_REGISTRATION,
						'emailSubject'			=> SubjectEmailType::VERIFIKASI_EMAIL);

					$emailService = sendEmail($dataSendEmail);

					switch ($emailService['isDelivered']){
						case TRUE:
							generalAllert("Verifikasi Gagal!", "Batas Verifikasi Telah Berakhir. Email Verifikasi Baru Telah Terkirim Ke Email Anda. Silahkan Lakukan Verifikasi Ulang ", AllertType::FAILED);
							break;
						case FALSE:
							generalAllert("Verifikasi Gagal!", "Batas Verifikasi Telah Berakhir. Tidak Dapat Mengirim Ulang Email Verifikasi. Silahkan Hubungi Pengurus", AllertType::FAILED);
							break;
					};
				}
			}
		} else {
			generalAllert("Verifikasi Gagal!", "Akun Belum Terdaftar", AllertType::FAILED);
		}

		redirect("member/login");
	}

	public function password(){
		if ($this->session->userdata('member_authStatus') != AuthStatus::AUTHORIZED){
			redirect('member/login');
		} else {

			$content 	= '_memberLayouts/password/edit';

			$where		= array("member_id" => $this->session->userdata("member_id"));
			$dataBankAccount= $this->CrudModel->gw("member_bank_account", $where);

			$data 		= array('session'     		=> SessionType::MEMBER,
								'csrfName'			=> $this->security->get_csrf_token_name(),
								'csrfToken'			=> $this->security->get_csrf_hash(),
								'dataBankAccount'	=> $dataBankAccount,
								'appProfile'		=> true,
								'content'			=> $content);

			$this->load->view('_memberLayouts/wrapper', $data);
		}
	}

	public function passwordUpdate(){
		$input = $this->input->post(NULL, TRUE);

		switch ($this->session->userdata("member_authStatus")){
			case AuthStatus::AUTHORIZED:
				$memberId	= $this->session->userdata("member_id");
				$suffix 	= $input["suffix"];

				if ($suffix == "reset_password"){

					$getMemberPassword 	= $input["member_password_retype"];
					$setMemberPassword	= array("member_password" => password_hash($getMemberPassword, PASSWORD_BCRYPT));
					$whereMemberId		= array("id" => $memberId);

					$isPasswordUpdated = $this->CrudModel->ud("member", $setMemberPassword, $whereMemberId);

					switch ($isPasswordUpdated){
						case "success":
							$reportDescription  = "[".$this->MemberModel->getMemberReferalCode($memberId)."] Mengganti password";
							$data	= array("member_id"				=> $memberId,
								"user_type"				=> CredentialType::MEMBER,
								"report_description"	=> $reportDescription,
								"created_at"			=> date('Y-m-d H:i:s'));
							$this->ActivityLog->actionLog($data);
							generalAllert("Update Password Berhasil!", "Password anda telah berhasil diubah.", AllertType::SUCCESS);
							break;
						case "failed":
							generalAllert("Update Password Gagal!", "Terjadi kesalahan saat mengganti password. Silahkan hubungi developer IT anda.", AllertType::FAILED);
							break;
						default:
							generalAllert("Update Password Gagal!", "Sistem mengalami gangguan saat mengganti password. Silahkan hubungi developer IT anda.", AllertType::FAILED);
					}

				} elseif ($suffix == "reset_transaction_code") {

					$getMemberPassword 	= $input["member_transaction_code_retype"];
					$setMemberPassword	= array("member_transaction_code" => password_hash($getMemberPassword, PASSWORD_BCRYPT));
					$whereMemberId		= array("id" => $this->session->userdata("member_id"));

					$isPasswordUpdated = $this->CrudModel->ud("member", $setMemberPassword, $whereMemberId);

					switch ($isPasswordUpdated){
						case "success":
							$reportDescription  = "[".$this->MemberModel->getMemberReferalCode($memberId)."] Mengganti kode transaksi";
							$data	= array("member_id"				=> $memberId,
								"user_type"				=> CredentialType::MEMBER,
								"report_description"	=> $reportDescription,
								"created_at"			=> date('Y-m-d H:i:s'));
							$this->ActivityLog->actionLog($data);
							generalAllert("Update Kode Transaksi Berhasil!", "Kode transaksi anda telah berhasil diubah.", AllertType::SUCCESS);
							break;
						case "failed":
							generalAllert("Update Kode Transaksi Gagal!", "Terjadi kesalahan saat mengganti Kode transaksi. Silahkan hubungi pengurus koperasi.", AllertType::FAILED);
							break;
						default:
							generalAllert("Update Kode Transaksi Gagal!", "Sistem mengalami gangguan saat mengganti Kode transaksi. Silahkan hubungi pengurus koperasi.", AllertType::FAILED);
					}

				} elseif ($suffix == "set_member_bank_account_info") {

					$this->form_validation->set_rules('bank_account_name', 'Kode Transaksi', 'trim|required');
					$this->form_validation->set_rules('bank_account_number', 'Nominal', 'trim|required');
					$this->form_validation->set_rules('bank_account_owner', 'Nominal', 'trim|required');

					if ($this->form_validation->run() == false) {
						$this->password();
					} else {
						$memberId 			= $this->session->userdata("member_id");
						$whereMemberId		= array("member_id" => $memberId);

						$dataBankAccount	= array("bank_account_name" 	=> strtoupper($input["bank_account_name"]),
							"bank_account_number"	=> $input["bank_account_number"],
							"bank_account_owner"	=> $input["bank_account_owner"],
							"updated_at"			=> date('Y-m-d H:i:s'),
							"updated_by"			=> $memberId);

						$isUpdated = $this->CrudModel->ud("member_bank_account", $dataBankAccount, $whereMemberId);

						switch ($isUpdated){
							case "success":
								$reportDescription  = "[".$this->MemberModel->getMemberReferalCode($memberId)."] Mengganti data rekening";
								$data	= array("member_id"				=> $memberId,
									"user_type"				=> CredentialType::MEMBER,
									"report_description"	=> $reportDescription,
									"created_at"			=> date('Y-m-d H:i:s'));
								$this->ActivityLog->actionLog($data);
								generalAllert("Update Data Rekening Berhasil!", "Data rekening anda telah berhasil diubah.", AllertType::SUCCESS);
								break;
							case "failed":
								generalAllert("Update Data Rekening Gagal!", "Terjadi kesalahan saat memperbaharui data rekening. Silahkan hubungi pengurus koperasi.", AllertType::FAILED);
								break;
							default:
								generalAllert("Update Data Rekening Gagal!", "Sistem mengalami gangguan saat memperbaharui data rekening. Silahkan hubungi pengurus koperasi.", AllertType::FAILED);
						}
					}
				}

				redirect('member/profile/password');
				break;
			default:
				redirect('member/login');
				break;

		}
	}

	function setNotificationOpened($id){

		if ($this->session->userdata('member_authStatus') != AuthStatus::AUTHORIZED){
			redirect('member/login');
		} else {
			$whereId 	= array("id" => $id);
			$dataStatus	= array("is_opened" => true);

			$dataNotif 	= current($this->CrudModel->gw("action_log", $whereId));
			$this->CrudModel->ud("action_log", $dataStatus, $whereId);

			redirect($dataNotif->reference_link);
		}
	}
	
	public function logout(){
		$this->session->sess_destroy();
		redirect('member/login');
	}
}
