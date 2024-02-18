<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MemberTeam extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if($this->session->userdata('member_authStatus') != AuthStatus::AUTHORIZED){
			redirect('member/login');
		}
	}

	public function index() {
		$content 		= '_memberLayouts/team/index';
		$uplineId		= array("member_parent_id" => $this->session->userdata("member_id"));
		$myTeam			= $this->CrudModel->gw("member", $uplineId);
		$query			= 'SELECT member_referal_code FROM member WHERE id = "'.$this->session->userdata("member_id").'"';
		$dataReferal	= $this->CrudModel->q($query);
		$memberId		= $this->session->userdata('member_id');
		$memberStatus 	= $this->MemberModel->getMemberActiveStatus($memberId);

		$data 		= array('session'     		=> SessionType::MEMBER,
							'memberIsKyc'		=> $memberStatus["member_is_kyc"],
							'memberIsSimwa'		=> $memberStatus["member_is_simwa"],
							'memberIsSimpo'		=> $memberStatus["member_is_simpo"],
							'memberIsActivated'	=> $memberStatus["member_is_activated"],
							'csrfName'			=> $this->security->get_csrf_token_name(),
							'csrfToken'			=> $this->security->get_csrf_hash(),
							'dataReferal'		=> $dataReferal,
							'dataMember'		=> $myTeam,
							'content'			=> $content);

		$this->load->view('_memberLayouts/wrapper', $data);
	}

	public function save(){

		$input = $this->input->post(NULL, TRUE);

		$this->form_validation->set_rules('member_full_name', 'Nama Lengkap', 'trim|required');
		$this->form_validation->set_rules('member_email', 'Email', 'trim|required|valid_email|is_unique[member.member_email]',
											array('is_unique'	=> 'Email Sudah Terdaftar!'));
		$this->form_validation->set_rules('member_phone_number', 'No. Hp', 'trim|required|is_unique[member.member_phone_number]',
											array('is_unique'=> 'No. Hp Sudah Terdaftar!.'));
		$this->form_validation->set_rules('member_ktp_number', 'No. Hp', 'trim|required|is_unique[member_kyc_info.member_ktp_number]',
											array('is_unique'=> 'No. KTP Sudah Terdaftar!.'));

		if (!empty($input['referal_link'])){
			$this->form_validation->set_rules('referal_link', 'Referral Link', 'trim|callback_referralValidation');
		};

		if ($this->form_validation->run() == false){
			$this->index();
		} else {

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

			$tokenId		= $this->CrudModel->i2('member_token', $dataToken);

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
					$dataRegisterUpdate	= array('member_parent_id'				=> $this->session->userdata('member_id'),
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

					$reportDescription  = "[".$input['referal_link']."] Mendaftarkan anggota baru "."[".$getMemberCode."]";
					$notifDescription	= "[".$input['referal_link']."] Mendaftarkan anggota baru "."[".$getMemberCode."]";

					$data	= array("member_id"				=> $this->session->userdata("member_id"),
									"user_type"				=> CredentialType::MEMBER,
									"receiver"				=> CredentialType::ADMIN,
									"report_description"	=> $reportDescription,
									"notif_description"		=> $notifDescription,
									"reference_link"		=> 'admin/keanggotaan/index?type='.strtolower(MemberStatus::REGISTERED),
									"created_at"			=> $memberCreated);

					$this->ActivityLog->actionLog($data);

					generalAllert("Pendaftaran Berhasil!","Silahkan hubungi tim anda untuk melakukan verifikasi email dalam 1x24jam.", AllertType::SUCCESS);

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

			$this->index();
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
}
