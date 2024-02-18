<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminProfile extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if($this->session->userdata('admin_authStatus') != AuthStatus::AUTHORIZED){
			redirect('pengurus/login');
		}
	}

	public function index() {

		$content 	= '_adminLayouts/profile/index';
		$query		= 'SELECT * FROM admin JOIN admin_kyc_info ON admin.admin_kyc_id = admin_kyc_info.id WHERE admin.id = '.$this->session->userdata("admin_id").' ';
		$dataAdmin	= $this->CrudModel->q($query);
		$data 		= array('session'     	=> SessionType::ADMIN,
							'dataAdmin'		=> $dataAdmin,
							'appProfile'	=> true,
							'content'		=> $content);

		$this->load->view('_adminLayouts/wrapper', $data);
	}

	public function edit($id){
		$content 	= '_adminLayouts/profile/edit';
		$query		= 'SELECT * FROM admin JOIN admin_kyc_info ON admin.admin_kyc_id = admin_kyc_info.id WHERE admin.id = '.$id.' ';
		$dataAdmin	= $this->CrudModel->q($query);
		$data 		= array('session'     	=> SessionType::ADMIN,
							'csrfName'		=> $this->security->get_csrf_token_name(),
							'csrfToken'		=> $this->security->get_csrf_hash(),
							'dataAdmin'		=> $dataAdmin,
							'appProfile'	=> true,
							'content'		=> $content);

		$this->load->view('_adminLayouts/wrapper', $data);
	}

	public function update() {
		$input = $this->input->post(NULL, TRUE);

		$this->form_validation->set_rules('admin_email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('admin_phone_number', 'No. Hp', 'trim|required');
		$this->form_validation->set_rules('admin_full_name', 'Nama Lengkap', 'trim|required');
		$this->form_validation->set_rules('admin_ktp_number', 'No. Ktp', 'trim|required');
//		$this->form_validation->set_rules('admin_ktp_image', 'Gambar Ktp', 'trim|required');
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
			$this->edit($this->session->userdata("admin_id"));
		} else {

			$dataAdmin	= array("admin_email"			=> $input["admin_email"],
								"admin_phone_number" 	=> $input["admin_phone_number"],
								"admin_full_name"		=> $input["admin_full_name"],
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

			$whereAdminId	= array("id" => $this->session->userdata("admin_id"));
			$whereKycId		= array("id" => $input["admin_kyc_id"]);

			$updateAdmin	= $this->CrudModel->ud("admin", $dataAdmin, $whereAdminId);
			$updateAdminKyc	= $this->CrudModel->ud("admin_kyc_info", $dataKyc, $whereKycId);


			if ($updateAdmin == 'success' && $updateAdminKyc == 'success') {
				$reportDescription  = "Mengupdate informasi profil";

				$data	= array("admin_id"				=> $this->session->userdata("admin_id"),
								"user_type"				=> CredentialType::ADMIN,
								"report_description"	=> $reportDescription,
								"created_at"			=> date('Y-m-d H:i:s'));

				$this->ActivityLog->actionLog($data);

				generalAllert("Update Data Berhasil!", "Data profil anda telah berhasil diubah.", AllertType::SUCCESS);
				$this->session->set_userdata('admin_full_name', $input["admin_full_name"]);
			} elseif ($updateAdmin != 'success' && $updateAdminKyc == 'success') {
				generalAllert("Update Data Gagal!", "Terjadi kesalahan saat melakukan perubahan data profil. Silahkan hubungi developer IT anda.", AllertType::FAILED);
			} elseif ($updateAdmin == 'success' && $updateAdminKyc != 'success') {
				generalAllert("Update Data Gagal!", "Terjadi kesalahan saat melakukan perubahan data profil Lengkap pengurus. Silahkan hubungi developer IT anda.", AllertType::FAILED);
			} else {
				generalAllert("Update Data Gagal!", "Terjadi kesalahan saat melakukan perubahan seluruh data profil Lengkap pengurus. Silahkan hubungi developer IT anda.", AllertType::FAILED);
			}

			redirect('admin-profile');
		}
	}
}
