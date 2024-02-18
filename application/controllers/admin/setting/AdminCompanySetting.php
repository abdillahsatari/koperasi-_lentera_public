<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminCompanySetting extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if($this->session->userdata('admin_authStatus') != AuthStatus::AUTHORIZED){
			redirect('pengurus/login');
		}
	}

	public function index() {

		$content 			= '_adminLayouts/companySetting/index';
		$dataSettingCompany	= $this->CrudModel->ga("admin_setting_company");
		$data 	= array('session'				=> SessionType::ADMIN,
						'csrfName'				=> $this->security->get_csrf_token_name(),
						'csrfToken'				=> $this->security->get_csrf_hash(),
						'dataSettingCompany'	=> current($dataSettingCompany),
						'content'				=> $content );

		$this->load->view('_adminLayouts/wrapper', $data);
	}

	public function save(){

		$input	= $this->input->post(NULL, TRUE);

		$this->form_validation->set_rules('setcom_document', 'File AD/ART', 'trim|required');
		$this->form_validation->set_rules('setcom_rules', 'Keterangan aturan dan sanksi anggota', 'trim|required');

		if ($this->form_validation->run() == false) {
			$this->index();
		} else {

			$dataGateway	= array("setcom_document"	=> $input["setcom_document"],
									"setcom_rules" 		=> $input["setcom_rules"],
									"created_at"		=> date('Y-m-d H:i:s'),
									"created_by"		=> $this->session->userdata("admin_id"));

			$dataCompanySetting = $this->CrudModel->ga("admin_setting_company");

			if (count($dataCompanySetting) > 0) {

				$id 		= current($dataCompanySetting)->id;
				$whereId	= array("id" => $id);
				$this->CrudModel->u("admin_setting_company", $dataGateway, $whereId);

				$reportDescription  = "Mengupdate informasi peraturan anggota";

				$data	= array("admin_id"				=> $this->session->userdata("admin_id"),
								"user_type"				=> CredentialType::ADMIN,
								"report_description"	=> $reportDescription,
								"created_at"			=> date('Y-m-d H:i:s'));

				$this->ActivityLog->actionLog($data);

				generalAllert("Penyimpanan Berhasil!","Informasi Tentang Peraturan Koperasi Telah Diperbaharui.", AllertType::SUCCESS);

			} else {

				$this->CrudModel->i("admin_setting_company", $dataGateway);
				generalAllert("Penyimpanan Berhasil!","Informasi Tentang Peraturan Koperasi Telah Ditambahkan.", AllertType::SUCCESS);
			}

			redirect('admin/setting/company');
		}

	}

}
