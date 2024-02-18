<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminMember extends CI_Controller{

	public function __construct(){
		parent::__construct();
		if ($this->session->userdata('admin_authStatus') != AuthStatus::AUTHORIZED) {
			redirect('pengurus/login');
		}
	}

	public function index(){

		$pageType	= $this->input->get('type');
		$content	= '_adminLayouts/member/index';

		switch ($pageType){
			case strtolower(MemberStatus::ACTIVE):
				$where			= array("member_is_activated" => true);
				$getDataMember	= $this->CrudModel->gw("member", $where);
				$dataMember		= array();

				foreach($getDataMember as $member){

					$query 				= 'SELECT member_referal_code, member_full_name FROM member WHERE id = "'.$member->member_parent_id.'"';
					$newParentId		= $this->CrudModel->q($query);
					$collectNewParentId	= current($newParentId);

					$dataLists = new stdClass();
					$dataLists->id 							= $member->id;
					$dataLists->member_referal_code 		= $member->member_referal_code;
					$dataLists->member_full_name 			= $member->member_full_name;
					$dataLists->member_phone_number 		= $member->member_phone_number;
					$dataLists->member_email 				= $member->member_email;
					$dataLists->created_at					= $member->created_at;
					$dataLists->member_is_activated			= "Anggota Aktif";
					$dataLists->parent_member_full_name		= $collectNewParentId->member_full_name;
					$dataLists->parent_member_referal_code	= $collectNewParentId->member_referal_code;

					array_push($dataMember, $dataLists);
				}

				$data	= array('session' 		=> SessionType::ADMIN,
								'pageType'		=> $pageType,
								'csrfName'		=> $this->security->get_csrf_token_name(),
								'csrfToken'		=> $this->security->get_csrf_hash(),
								'dataMember'	=> $dataMember,
								'content'		=> $content);

				$this->load->view('_adminLayouts/wrapper', $data);
				break;
			case strtolower(MemberStatus::REGISTERED):
				$where			= array("member_is_activated" => NULL);
				$getDataMember	= $this->CrudModel->gw("member", $where);
				$dataMember		= array();

				foreach($getDataMember as $member){

					$query 				= 'SELECT member_referal_code, member_full_name FROM member WHERE id = "'.$member->member_parent_id.'"';
					$newParentId		= $this->CrudModel->q($query);
					$collectNewParentId	= current($newParentId);

					$dataLists = new stdClass();
					$dataLists->id 							= $member->id;
					$dataLists->member_referal_code 		= $member->member_referal_code;
					$dataLists->member_full_name 			= $member->member_full_name;
					$dataLists->member_phone_number 		= $member->member_phone_number;
					$dataLists->member_email 				= $member->member_email;
					$dataLists->created_at					= $member->created_at;
					$dataLists->member_is_activated			= "Calon Anggota";
					$dataLists->parent_member_full_name		= $collectNewParentId->member_full_name;
					$dataLists->parent_member_referal_code	= $collectNewParentId->member_referal_code;

					array_push($dataMember, $dataLists);
				}

				$data	= array('session' 		=> SessionType::ADMIN,
								'pageType'		=> $pageType,
								'csrfName'		=> $this->security->get_csrf_token_name(),
								'csrfToken'		=> $this->security->get_csrf_hash(),
								'dataMember'	=> $dataMember,
								'content'		=> $content);

				$this->load->view('_adminLayouts/wrapper', $data);
				break;
			default :
				$this->load->view('errors/html/error_404');
				break;
		}
	}

	public function create() {

		$content	= '_adminLayouts/member/create';

		$data 		= array('session' 		=> SessionType::ADMIN,
							'csrfName'		=> $this->security->get_csrf_token_name(),
							'csrfToken'		=> $this->security->get_csrf_hash(),
							'content'		=> $content);

		$this->load->view("_adminLayouts/wrapper", $data);
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

		$this->form_validation->set_rules('member_birth_place', 'Tempat Lahir', 'trim|required');
		$this->form_validation->set_rules('member_birth_date', 'Tgl Lahir', 'trim|required');
		$this->form_validation->set_rules('member_last_education', 'Pendidikan Terakhir', 'trim|required');
		$this->form_validation->set_rules('member_job', 'Pekerjaan', 'trim|required');
		$this->form_validation->set_rules('member_address', 'Alamat', 'trim|required');
		$this->form_validation->set_rules('member_kelurahan', 'Kelurahan', 'trim|required');
		$this->form_validation->set_rules('member_kecamatan', 'Kecamatan', 'trim|required');
		$this->form_validation->set_rules('member_kota', 'Kota', 'trim|required');
		$this->form_validation->set_rules('member_provinsi', 'Provinsi', 'trim|required');
		$this->form_validation->set_rules('member_kode_pos', 'Kode Pos', 'trim|required');

		if (!empty($input['referal_link'])){
			$this->form_validation->set_rules('referal_link', 'Referral Link', 'trim|required');
		};

		if ($this->form_validation->run() == false){
			generalAllert("Gagal!". "<br/>", validation_errors()." Silahkan coba lagi. Pastikan semua inputan anda telah sesuai", AllertType::FAILED);
			$this->create();
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

			$tokenId		= $this->CrudModel->i2('member_token', $dataToken);

			$dataKycInfo	= array('member_id'				=> $memberId,
									"member_ktp_number"		=> $input["member_ktp_number"],
									"member_ktp_image" 		=> $input["member_ktp_image"],
									'member_birth_place'	=> $input['member_birth_place'],
									'member_birth_date'		=> $input['member_birth_date'],
									'member_last_education'	=> $input['member_last_education'],
									'member_job'			=> $input['member_job'],
									'member_address'		=> $input['member_address'],
									'member_kelurahan'		=> $input['member_kelurahan'],
									'member_kecamatan'		=> $input['member_kecamatan'],
									'member_kota'			=> $input['member_kota'],
									'member_provinsi'		=> $input['member_provinsi'],
									'member_kode_pos'		=> $input['member_kode_pos'],
									'created_by'			=> $this->session->userdata('admin_id'),
									'created_at'			=> $memberCreated);

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
												'member_is_kyc'					=> 1,
												'created_at'					=> $memberCreated,
												'created_by'					=> $uplineId ?: 1);

					$whereId = array('id' => $memberId);

					$this->CrudModel->u('member', $dataRegisterUpdate, $whereId);
					$this->SimpananModel->setSimpananContentMemberAnggota($memberId);

					if (!empty($input['referal_link'])) {
						$reportDescription  = "Mendaftarkan anggota [".$getMemberCode."] menggunakan kode referal [ ".$input['referal_link']." ]";
					} else {
						$reportDescription  = "Mendaftarkan anggota [".$getMemberCode."]";
					}

					$data	= array("admin_id"				=> $this->session->userdata("admin_id"),
									"user_type"				=> CredentialType::ADMIN,
									"report_description"	=> $reportDescription,
									"created_at"			=> $memberCreated);

					$this->ActivityLog->actionLog($data);

					generalAllert("Pendaftaran Berhasil!","Silahkan arahkan calon anggota untuk melakukan verifikasi email dalam 1x24jam.", AllertType::SUCCESS);

					$dataRedirect = 'admin/keanggotaan/index?type='.strtolower(MemberStatus::REGISTERED);

					break;
				case FALSE:

					$whereId		= array('id'	=> $memberId);
					$whereKycId		= array('id' 	=> $memberKycId);
					$whereTokenId	= array('id'	=> $tokenId);

					$this->CrudModel->d('member', $whereId);
					$this->CrudModel->d('member_kyc_info', $whereKycId);
					$this->CrudModel->d('member_token', $whereTokenId);

					generalAllert("Pendaftaran Gagal!", "Terjadi kesalahan saat melakukan verifikasi pendaftaran. Silahkan menghubungi developer it anda.", AllertType::FAILED);
					$dataRedirect = 'admin/keanggotaan/index?type='.strtolower(MemberStatus::REGISTERED);

					break;
			};

			redirect($dataRedirect);
		}
	}

	public function edit($id){
		$content	= '_adminLayouts/member/edit';

		$query		= 'SELECT * FROM member 
    					JOIN member_kyc_info ON member.member_kyc_id = member_kyc_info.id WHERE member.id = '.$id.' ';
		$dataMember	= $this->CrudModel->q($query);

		$data 			= array('session'		=> SessionType::ADMIN,
								'csrfName'		=> $this->security->get_csrf_token_name(),
								'csrfToken'		=> $this->security->get_csrf_hash(),
								'dataMember'	=> current($dataMember),
								'content'		=> $content);

		$this->load->view("_adminLayouts/wrapper", $data);
	}

	public function update(){

		$input = $this->input->post(NULL, TRUE);

		$this->form_validation->set_rules('member_full_name', 'Nama Lengkap', 'trim|required');
		$this->form_validation->set_rules('member_phone_number', 'No. Hp', 'trim|required');
		$this->form_validation->set_rules('member_ktp_number', 'No. Ktp', 'trim|required');
		$this->form_validation->set_rules('member_birth_place', 'Tempat Lahir', 'trim|required');
		$this->form_validation->set_rules('member_birth_date', 'Tgl Lahir', 'trim|required');
		$this->form_validation->set_rules('member_last_education', 'Pendidikan Terakhir', 'trim|required');
		$this->form_validation->set_rules('member_job', 'Pekerjaan', 'trim|required');
		$this->form_validation->set_rules('member_address', 'Alamat', 'trim|required');
		$this->form_validation->set_rules('member_kelurahan', 'Kelurahan', 'trim|required');
		$this->form_validation->set_rules('member_kecamatan', 'Kecamatan', 'trim|required');
		$this->form_validation->set_rules('member_kota', 'Kota', 'trim|required');
		$this->form_validation->set_rules('member_provinsi', 'Provinsi', 'trim|required');
		$this->form_validation->set_rules('member_kode_pos', 'Kode Pos', 'trim|required');

		if ($this->form_validation->run() == false) {
			$this->edit($input["member_id"]);
		} else {

			$whereSimpo	= array("id" 				=> $input["member_id"],
								"member_is_simpo" 	=> true);
			$whereSimwa	= array("id" 				=> $input["member_id"],
								"member_is_simwa" 	=> true);

			$memberSimpoStatus = $this->CrudModel->cw("member", $whereSimpo);
			$memberSimwaStatus = $this->CrudModel->cw("member", $whereSimwa);

			if ($memberSimpoStatus > 0 && $memberSimwaStatus > 0){
				$memberIsActivated	= TRUE;
			}

			$dataMember	= array("member_email"			=> $input["member_email"],
								"member_phone_number" 	=> $input["member_phone_number"],
								"member_full_name"		=> $input["member_full_name"],
								"member_is_kyc"			=> TRUE,
								"member_is_activated"	=> isset($memberIsActivated) ?: null,
								"updated_at"			=> date('Y-m-d H:i:s'));

			$dataKyc	= array("member_ktp_number"		=> $input["member_ktp_number"],
								"member_ktp_image" 		=> $input["member_ktp_image"],
								"member_birth_place"	=> $input["member_birth_place"],
								"member_birth_date"		=> $input["member_birth_date"],
								"member_last_education"	=> $input["member_last_education"],
								"member_job"			=> $input["member_job"],
								"member_address"		=> $input["member_address"],
								"member_kelurahan"		=> $input["member_kelurahan"],
								"member_kecamatan"		=> $input["member_kecamatan"],
								"member_kota"			=> $input["member_kota"],
								"member_provinsi"		=> $input["member_provinsi"],
								"member_kode_pos"		=> $input["member_kode_pos"],
								"updated_at"			=> date('Y-m-d H:i:s'));

			$whereMemberId	= array("id" => $input["member_id"]);
			$whereKycId		= array("id" => $input["member_kyc_id"]);

			$updateMember 	= $this->CrudModel->ud("member", $dataMember, $whereMemberId);
			$updateMemberKyc = $this->CrudModel->ud("member_kyc_info", $dataKyc, $whereKycId);


			if ($updateMember == 'success' && $updateMemberKyc == 'success') {

				$reportDescription  = "Mengupdate data kyc member [".$this->MemberModel->getMemberReferalCode($input["member_id"])."]";

				$data	= array("admin_id"				=> $this->session->userdata("admin_id"),
								"user_type"				=> CredentialType::ADMIN,
								"report_description"	=> $reportDescription,
								"created_at"			=> date('Y-m-d H:i:s'));

				$this->ActivityLog->actionLog($data);
				generalAllert("Update Data Berhasil!", "Data anggota telah berhasil diubah.", AllertType::SUCCESS);

			} elseif ($updateMember != 'success' && $updateMemberKyc == 'success') {
				generalAllert("Update Data Gagal!", "Terjadi kesalahan saat melakukan perubahan data anggota. Silahkan hubungi developer IT anda.", AllertType::FAILED);
			} elseif ($updateMember == 'success' && $updateMemberKyc != 'success') {
				generalAllert("Update Data Gagal!", "Terjadi kesalahan saat melakukan perubahan data Lengkap anggota. Silahkan hubungi developer IT anda.", AllertType::FAILED);
			} else {
				generalAllert("Update Data Gagal!", "Terjadi kesalahan saat melakukan perubahan seluruh data Lengkap anggota. Silahkan hubungi developer IT anda.", AllertType::FAILED);
			}

			$this->edit($input["member_id"]);
		}

	}

	public function report(){

		$content 	= '_adminLayouts/member/report';
		$query		= 'SELECT TL.*, ME.member_full_name, ME.member_referal_code 
						FROM transaction_log TL JOIN member ME ON TL.member_id = ME.id ORDER BY created_at DESC';
		$dataLog	= $this->CrudModel->q($query);
		$data 		= array('session'			=> SessionType::ADMIN,
							'dataTransactionLog'=> $dataLog,
							'csrfName'			=> $this->security->get_csrf_token_name(),
							'csrfToken'			=> $this->security->get_csrf_hash(),
							'content'			=> $content );

		$this->load->view('_adminLayouts/wrapper', $data);
	}

}
