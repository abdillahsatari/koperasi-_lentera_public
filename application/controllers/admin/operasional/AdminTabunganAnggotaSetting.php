<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminTabunganAnggotaSetting extends CI_Controller{

	public function __construct() {
		parent::__construct();
		if ($this->session->userdata('admin_authStatus') != AuthStatus::AUTHORIZED) {
			redirect('pengurus/login');
		}
	}

	public function index(){

		$content= '_adminLayouts/tabunganAnggotaSetting/index';
		$whereId= array("id" => 1);
		$dataTabunganSetting = $this->CrudModel->gw("tabungan_setting", $whereId);
		$data	= array('session' 				=> SessionType::ADMIN,
						'csrfName'				=> $this->security->get_csrf_token_name(),
						'csrfToken'				=> $this->security->get_csrf_hash(),
						'dataTabunganSetting'	=> current($dataTabunganSetting),
						'content'				=> $content);

		$this->load->view('_adminLayouts/wrapper', $data);

	}

	public function update(){
		$input 	= $this->input->post(NULL, TRUE);
		$this->form_validation->set_rules('required_ammount', 'Min. Tabungan', 'trim|required');
		$this->form_validation->set_rules('interest_percentage', 'Persentas Bunga', 'trim|required');
		$this->form_validation->set_rules('admin_fee', 'Biaya Admin', 'trim|required');

		if ($this->form_validation->run() == false) {
			$this->index();
		} else {
			$whereId	= array("id" => $input["id"]);
			$data		= array("required_ammount" 		=> $input["required_ammount"],
								"interest_percentage"	=> $input["interest_percentage"],
								"admin_fee"				=> $input["admin_fee"]);
			$updateStatus = $this->CrudModel->ud("tabungan_setting", $data, $whereId);

			if ($updateStatus == "success"){

				$reportDescription  = "Mengupdate Pengaturan Tabungan";

				$data	= array("admin_id"				=> $this->session->userdata("admin_id"),
								"user_type"				=> CredentialType::ADMIN,
								"report_description"	=> $reportDescription,
								"created_at"			=> date('Y-m-d H:i:s'));

				$this->ActivityLog->actionLog($data);
				generalAllert("Update Berhasil!", "Pengaturan Tabungan Telah Berhasil Diperbaharui", AllertType::SUCCESS);
			} else {
				generalAllert("Update Gagal !", "Terjadi kesalahan saat memperbaharui pengaturan tabungan. Silahkan coba lagi", AllertType::FAILED);
			}

			$this->index();
		}

	}

}
