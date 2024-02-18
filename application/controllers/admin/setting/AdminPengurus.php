<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminPengurus extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if($this->session->userdata('admin_authStatus') != AuthStatus::AUTHORIZED){
			redirect('pengurus/login');
		}
	}

	public function index() {

		$content 		= '_adminLayouts/pengurus/index';
		$query			= 'SELECT A.*, AR.role_type FROM admin A JOIN admin_role AR ON A.admin_role_id = AR.id';
		$dataPengurus	= $this->CrudModel->q($query);
		$data 			= array('session'		=> SessionType::ADMIN,
								'dataPengurus'	=> $dataPengurus,
								'csrfName'		=> $this->security->get_csrf_token_name(),
								'csrfToken'		=> $this->security->get_csrf_hash(),
								'content'		=> $content );

		$this->load->view('_adminLayouts/wrapper', $data);
	}

	public function create() {
		$content 		= '_adminLayouts/pengurus/create';
		$dataAdminRole	= $this->CrudModel->ga("admin_role");

		$data 			= array('session'		=> SessionType::ADMIN,
								'dataAdminRole'	=> $dataAdminRole,
								'csrfName'		=> $this->security->get_csrf_token_name(),
								'csrfToken'		=> $this->security->get_csrf_hash(),
								'content'		=> $content);

		$this->load->view('_adminLayouts/wrapper', $data);
	}

	public function save(){
		$input = $this->input->post(NULL, TRUE);

		$this->form_validation->set_rules('admin_full_name', 'Nama Lengkap', 'trim|required');
		$this->form_validation->set_rules('admin_email', 'Email', 'trim|required|valid_email|is_unique[admin.admin_email]',
											array('is_unique'	=> 'Email Sudah Terdaftar!'));
		$this->form_validation->set_rules('admin_phone_number', 'No. Hp', 'trim|required|is_unique[admin.admin_phone_number]',
											array('is_unique'=> 'No. Hp Sudah Terdaftar!.'));
		$this->form_validation->set_rules('admin_ktp_number', 'No. Hp', 'trim|required|is_unique[admin_kyc_info.admin_ktp_number]',
											array('is_unique'=> 'No. KTP Sudah Terdaftar!.'));
		$this->form_validation->set_rules('admin_role_id', 'Role Admin', 'trim|required');
		$this->form_validation->set_rules('admin_status', 'Status Admin', 'trim|required');

		$this->form_validation->set_rules('admin_birth_place', 'Tempat Lahir', 'trim|required');
		$this->form_validation->set_rules('admin_birth_date', 'Tgl Lahir', 'trim|required');
		$this->form_validation->set_rules('admin_last_education', 'Pendidikan Terakhir', 'trim|required');
		$this->form_validation->set_rules('admin_job', 'Pekerjaan', 'trim|required');
		$this->form_validation->set_rules('admin_address', 'Alamat', 'trim|required');
		$this->form_validation->set_rules('admin_kelurahan', 'Kelurahan', 'trim|required');
		$this->form_validation->set_rules('admin_kecamatan', 'Kecamatan', 'trim|required');
		$this->form_validation->set_rules('admin_kota', 'Kota', 'trim|required');
		$this->form_validation->set_rules('admin_provinsi', 'Provinsi', 'trim|required');
		$this->form_validation->set_rules('admin_kode_pos', 'Kode Pos', 'trim|required');

		if ($this->form_validation->run() == false){
			$this->create();
		} else {

			$dataregister	= array('admin_full_name'		=> $input['admin_full_name'],
									'admin_email'			=> $input['admin_email'],
									'admin_phone_number'	=> $input['admin_phone_number'],
									'admin_role_id'			=> $input['admin_role_id'],
									'admin_status'			=> $input['admin_status']);

			$adminId		= $this->CrudModel->i2('admin', $dataregister);

			$generatePassword			= generateAutomaticPassword();
			$generateTransactionCode	= generateAutomaticPassword();
			$setadminPassword			= password_hash($generatePassword, PASSWORD_BCRYPT);
			$setadminTransactionCode	= password_hash($generateTransactionCode, PASSWORD_BCRYPT);
			$adminCreated				= date('Y-m-d H:i:s');
			$token 						= generateToken();
			$tokenCreated				= time();

			$dataToken		= array('admin_id'	=> $adminId,
									'token'		=> $token,
									'created_at'=> $tokenCreated);

			$tokenId		= $this->CrudModel->i2('admin_token', $dataToken);

			$dataKycInfo	= array('admin_id'				=> $adminId,
									"admin_ktp_number"		=> $input["admin_ktp_number"],
									"admin_ktp_image" 		=> $input["admin_ktp_image"],
									'admin_birth_place'		=> $input['admin_birth_place'],
									'admin_birth_date'		=> $input['admin_birth_date'],
									'admin_last_education'	=> $input['admin_last_education'],
									'admin_job'				=> $input['admin_job'],
									'admin_address'			=> $input['admin_address'],
									'admin_kelurahan'		=> $input['admin_kelurahan'],
									'admin_kecamatan'		=> $input['admin_kecamatan'],
									'admin_kota'			=> $input['admin_kota'],
									'admin_provinsi'		=> $input['admin_provinsi'],
									'admin_kode_pos'		=> $input['admin_kode_pos'],
									'created_by'			=> $this->session->userdata('admin_id'),
									'created_at'			=> $adminCreated);

			$adminKycId	= $this->CrudModel->i2('admin_kyc_info', $dataKycInfo);

			$dataSendEmail	= array('adminFullName'			=> $input['admin_full_name'],
									'adminPhoneNumber'		=> $input['admin_phone_number'],
									'adminPassword'			=> $generatePassword,
									'adminTransactionCode'	=> $generateTransactionCode,
									'adminToken'			=> $token,
									'emailRecipient'		=> $input['admin_email'],
									'emailType'				=> EmailType::NEW_ADMIN_REGISTRATION,
									'emailSubject'			=> SubjectEmailType::VERIFIKASI_EMAIL);

			$emailService = sendEmail($dataSendEmail);

			switch ($emailService['isDelivered']){
				case TRUE:
					$dataRegisterUpdate	= array(
												'admin_password'				=> $setadminPassword,
												'admin_transaction_code'		=> $setadminTransactionCode,
												'admin_kyc_id'					=> $adminKycId,
												'admin_is_email_regist_sent'	=> 1,
												'admin_is_registered'			=> 1,
												'admin_is_kyc'					=> 1,
												'created_at'					=> $adminCreated,
												'created_by'					=> $this->session->userdata('admin_id'));

					$whereId = array('id' => $adminId);

					$this->CrudModel->u('admin', $dataRegisterUpdate, $whereId);

					$reportDescription  = "Membuat akun pengurus baru";

					$data	= array("admin_id"				=> $this->session->userdata("admin_id"),
									"user_type"				=> CredentialType::ADMIN,
									"report_description"	=> $reportDescription,
									"created_at"			=> date('Y-m-d H:i:s'));

					$this->ActivityLog->actionLog($data);

					generalAllert("Pendaftaran Berhasil!","Silahkan arahkan calon pengurus untuk melakukan verifikasi email dalam 1x24jam.", AllertType::SUCCESS);

					$dataRedirect = 'admin/pengurus/index';

					break;
				case FALSE:

					$whereId		= array('id'	=> $adminId);
					$whereKycId		= array('id' 	=> $adminKycId);
					$whereTokenId	= array('id'	=> $tokenId);

					$this->CrudModel->d('admin', $whereId);
					$this->CrudModel->d('admin_kyc_info', $whereKycId);
					$this->CrudModel->d('admin_token', $whereTokenId);

					generalAllert("Pendaftaran Gagal!", "Terjadi kesalahan saat melakukan verifikasi pendaftaran. Silahkan hubungi developer IT anda.", AllertType::FAILED);
					$dataRedirect = 'admin/pengurus/create';

					break;
			};

			redirect($dataRedirect);
		}

	}

	public function edit($id) {
		$content 	= '_adminLayouts/pengurus/edit';
		$query		= 'SELECT * FROM admin 
    					JOIN admin_kyc_info ON admin.admin_kyc_id = admin_kyc_info.id 
						JOIN admin_role ON admin.admin_role_id = admin_role.id WHERE admin.id = '.$id.' ';
		$dataAdmin	= $this->CrudModel->q($query);
		$dataAdminRole	= $this->CrudModel->ga("admin_role");

		$data 			= array('session'		=> SessionType::ADMIN,
								'csrfName'		=> $this->security->get_csrf_token_name(),
								'csrfToken'		=> $this->security->get_csrf_hash(),
								'dataAdmin'		=> current($dataAdmin),
								'dataAdminRole'	=> $dataAdminRole,
								'content'		=> $content);

		$this->load->view('_adminLayouts/wrapper', $data);
	}

	public function update(){

		$input = $this->input->post(NULL, TRUE);

		$this->form_validation->set_rules('admin_full_name', 'Nama Lengkap', 'trim|required');
		$this->form_validation->set_rules('admin_phone_number', 'No. Hp', 'trim|required');
		$this->form_validation->set_rules('admin_ktp_number', 'No. Ktp', 'trim|required');
		$this->form_validation->set_rules('admin_role_id', 'Role Admin', 'trim|required');
		$this->form_validation->set_rules('admin_status', 'Status Admin', 'trim|required');
		$this->form_validation->set_rules('admin_birth_place', 'Tempat Lahir', 'trim|required');
		$this->form_validation->set_rules('admin_birth_date', 'Tgl Lahir', 'trim|required');
		$this->form_validation->set_rules('admin_last_education', 'Pendidikan Terakhir', 'trim|required');
		$this->form_validation->set_rules('admin_job', 'Pekerjaan', 'trim|required');
		$this->form_validation->set_rules('admin_address', 'Alamat', 'trim|required');
		$this->form_validation->set_rules('admin_kelurahan', 'Kelurahan', 'trim|required');
		$this->form_validation->set_rules('admin_kecamatan', 'Kecamatan', 'trim|required');
		$this->form_validation->set_rules('admin_kota', 'Kota', 'trim|required');
		$this->form_validation->set_rules('admin_provinsi', 'Provinsi', 'trim|required');
		$this->form_validation->set_rules('admin_kode_pos', 'Kode Pos', 'trim|required');

		if ($this->form_validation->run() == false) {
			$this->edit($input["admin_id"]);
		} else {

			$dataAdmin	= array("admin_email"			=> $input["admin_email"],
								"admin_phone_number" 	=> $input["admin_phone_number"],
								"admin_full_name"		=> $input["admin_full_name"],
								'admin_role_id'			=> $input['admin_role_id'],
								'admin_status'			=> $input['admin_status'],
								"admin_is_kyc"			=> TRUE,
								"updated_at"			=> date('Y-m-d H:i:s'),
								"updated_by"			=> $this->session->userdata("admin_id"));

			$dataKyc	= array("admin_ktp_number"		=> $input["admin_ktp_number"],
								"admin_ktp_image" 		=> $input["admin_ktp_image"],
								"admin_birth_place"		=> $input["admin_birth_place"],
								"admin_birth_date"		=> $input["admin_birth_date"],
								"admin_last_education"	=> $input["admin_last_education"],
								"admin_job"				=> $input["admin_job"],
								"admin_address"			=> $input["admin_address"],
								"admin_kelurahan"		=> $input["admin_kelurahan"],
								"admin_kecamatan"		=> $input["admin_kecamatan"],
								"admin_kota"			=> $input["admin_kota"],
								"admin_provinsi"		=> $input["admin_provinsi"],
								"admin_kode_pos"		=> $input["admin_kode_pos"],
								"updated_at"			=> date('Y-m-d H:i:s'),
								"updated_by"			=> $this->session->userdata("admin_id"));

			$whereAdminId	= array("id" => $input["admin_id"]);
			$whereKycId		= array("id" => $input["admin_kyc_id"]);

			$updateAdmin 	= $this->CrudModel->ud("admin", $dataAdmin, $whereAdminId);
			$updateAdminKyc = $this->CrudModel->ud("admin_kyc_info", $dataKyc, $whereKycId);


			if ($updateAdmin == 'success' && $updateAdminKyc == 'success') {

				$reportDescription  = "Mengaupdate data profil pengurus ".$input["admin_id"];
				$data	= array("admin_id"				=> $this->session->userdata("admin_id"),
								"user_type"				=> CredentialType::ADMIN,
								"report_description"	=> $reportDescription,
								"created_at"			=> date('Y-m-d H:i:s'));

				$this->ActivityLog->actionLog($data);
				generalAllert("Update Data Berhasil!", "Data pengurus telah berhasil diubah.", AllertType::SUCCESS);

			} elseif ($updateAdmin != 'success' && $updateAdminKyc == 'success') {
				generalAllert("Update Data Gagal!", "Terjadi kesalahan saat melakukan perubahan data pengurus. Silahkan hubungi developer IT anda.", AllertType::FAILED);
			} elseif ($updateAdmin == 'success' && $updateAdminKyc != 'success') {
				generalAllert("Update Data Gagal!", "Terjadi kesalahan saat melakukan perubahan data Lengkap pengurus. Silahkan hubungi developer IT anda.", AllertType::FAILED);
			} else {
				generalAllert("Update Data Gagal!", "Terjadi kesalahan saat melakukan perubahan seluruh data Lengkap pengurus. Silahkan hubungi developer IT anda.", AllertType::FAILED);
			}

			redirect('admin/pengurus/index');
		}

	}

}
