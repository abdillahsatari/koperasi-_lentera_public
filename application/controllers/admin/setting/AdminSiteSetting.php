<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminSiteSetting extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if($this->session->userdata('admin_authStatus') != AuthStatus::AUTHORIZED){
			redirect('pengurus/login');
		}
	}

	public function index() {

		$content 			= '_adminLayouts/siteSetting/index';
		$dataPaymentGateway	= $this->CrudModel->ga("lentera_bank_account");
		$data 	= array('session'				=> SessionType::ADMIN,
						'csrfName'				=> $this->security->get_csrf_token_name(),
						'csrfToken'				=> $this->security->get_csrf_hash(),
						'dataPaymentGateway'	=> $dataPaymentGateway,
						'content'				=> $content );

		$this->load->view('_adminLayouts/wrapper', $data);
	}

	public function save(){

		$input	= $this->input->post(NULL, TRUE);

		switch ($input["site_setting_type"]){
			case SiteSettingType::PAYMENT_GATEWAY:
				$this->form_validation->set_rules('nama_bank', 'Gateway', 'trim|required');
				$this->form_validation->set_rules('nomor_rekening', 'Nomor Rekening', 'trim|required');
				$this->form_validation->set_rules('nama_pemilik_account', 'Atas Nama', 'trim|required');

				if ($this->form_validation->run() == false) {
					$this->index();
				} else {

					$dataGateway	= array("nama_bank"				=> ucwords($input["nama_bank"]),
											"nomor_rekening" 		=> $input["nomor_rekening"],
											"nama_pemilik_account"	=> $input["nama_pemilik_account"],
											"status_is_active"		=> $input["status_is_active"],
											"created_at"			=> date('Y-m-d H:i:s'),
											"created_by"			=> $this->session->userdata("admin_id"));

					$this->CrudModel->i("lentera_bank_account", $dataGateway);

					$reportDescription  = "Membuat payment gateway baru";

					$data	= array("admin_id"				=> $this->session->userdata("admin_id"),
									"user_type"				=> CredentialType::ADMIN,
									"report_description"	=> $reportDescription,
									"created_at"			=> date('Y-m-d H:i:s'));

					$this->ActivityLog->actionLog($data);

					redirect('admin/setting/basic');
				}

				break;
		};

	}

	public function edit(){

		$siteSettingType 	= $this->uri->segment(5);
		$selectedDataId		= $this->uri->segment(6);
		$content 			= '_adminLayouts/siteSetting/index';

		switch ($siteSettingType){
			case SiteSettingType::PAYMENT_GATEWAY:

				$whereId			= array("id" => $selectedDataId);
				$dataPaymentGateway	= $this->CrudModel->gw("lentera_bank_account", $whereId);
				$data 				= array('session'				=> SessionType::ADMIN,
											'csrfName'				=> $this->security->get_csrf_token_name(),
											'csrfToken'				=> $this->security->get_csrf_hash(),
											'dataPaymentGateway'	=> $dataPaymentGateway,
											'content'				=> $content );

				break;
		};

		$this->load->view('_adminLayouts/wrapper', $data);
	}

}
