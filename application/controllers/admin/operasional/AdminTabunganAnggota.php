<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminTabunganAnggota extends CI_Controller{

	public function __construct() {
		parent::__construct();
		if ($this->session->userdata('admin_authStatus') != AuthStatus::AUTHORIZED) {
			redirect('pengurus/login');
		}
	}

	public function index(){
		$pageType	= $this->input->get('type');
		$content	= '_adminLayouts/tabunganAnggota/index';

		switch ($pageType){
			case strtolower(MemberTabunganApproval::TABUNGAN_NEW):
				$dataTabungan 	= $this->TabunganModel->getAllTabunganMemberList(MemberTabunganApproval::TABUNGAN_NEW);
				$data			= array('session' 				=> SessionType::ADMIN,
										'pageType'				=> MemberTabunganApproval::TABUNGAN_NEW,
										'dataTabungan'			=> $dataTabungan,
										'csrfName'				=> $this->security->get_csrf_token_name(),
										'csrfToken'				=> $this->security->get_csrf_hash(),
										'content'				=> $content);

				$this->load->view('_adminLayouts/wrapper', $data);
				break;
			case strtolower(MemberTabunganApproval::TABUNGAN_PROCESSED):
				$dataTabungan 	= $this->TabunganModel->getAllTabunganMemberList(MemberTabunganApproval::TABUNGAN_PROCESSED);
				$data			= array('session' 				=> SessionType::ADMIN,
										'pageType'				=> MemberTabunganApproval::TABUNGAN_PROCESSED,
										'dataTabungan'			=> $dataTabungan,
										'csrfName'				=> $this->security->get_csrf_token_name(),
										'csrfToken'				=> $this->security->get_csrf_hash(),
										'content'				=> $content);

				$this->load->view('_adminLayouts/wrapper', $data);
				break;
			case strtolower(MemberTabunganApproval::TABUNGAN_REJECTED):
				$dataTabungan 	= $this->TabunganModel->getAllTabunganMemberList(MemberTabunganApproval::TABUNGAN_REJECTED);
				$data			= array('session' 				=> SessionType::ADMIN,
										'pageType'				=> MemberTabunganApproval::TABUNGAN_REJECTED,
										'dataTabungan'			=> $dataTabungan,
										'csrfName'				=> $this->security->get_csrf_token_name(),
										'csrfToken'				=> $this->security->get_csrf_hash(),
										'content'				=> $content);

				$this->load->view('_adminLayouts/wrapper', $data);
				break;
			default:
				$this->load->view('errors/html/error_404');
				break;
		}
	}

	public function update(){
		$input = $this->input->post(NULL, TRUE);
		$this->form_validation->set_rules('tabungan_approval', 'Status Tabungan', 'trim|required');

		if ($this->form_validation->run() == false || $input["tabungan_approval"] == MemberTabunganApproval::TABUNGAN_NEW) {
			switch ($input["tabungan_approval"]){
				case MemberTabunganApproval::TABUNGAN_NEW:
					generalAllert("Woops!", "Anda belum merubah status tabungan",AllertType::INFO);
					break;
				default:
					generalAllert("Perubahan Status Gagal!". "<br/>", validation_errors(), AllertType::FAILED);
					break;
			}
			$dataredirect = "admin/tabungan/anggota?type=" . strtolower(MemberTabunganApproval::TABUNGAN_NEW);
			redirect($dataredirect);
		} else {

			$params			= array("tabungan_id"				=> $input["tabungan_member_id"],
									"member_id"					=> $input["member_id"],
									"tabungan_tr_code"			=> $input["tabungan_tr_code"],
									"tabungan_approval" 		=> $input["tabungan_approval"],
									"tabungan_approved_date"	=> date('Y-m-d H:i:s'),
									"tabungan_approved_by"		=> $this->session->userdata("admin_id"),
									"updated_at"				=> date('Y-m-d H:i:s'));

			$updatedStatus	= $this->TabunganModel->setTabunganMemberApproval($params);

			switch ($updatedStatus["status"]){
				case "success":
					generalAllert("Perubahan Status Berhasil!", $updatedStatus["data"], AllertType::SUCCESS);
					break;
				default:
					generalAllert("Perubahan Status Gagal!", $updatedStatus["data"], AllertType::FAILED);
			}


			$dataredirect = "admin/tabungan/anggota?type=" . strtolower($input["tabungan_approval"]);
			redirect($dataredirect);
		}
	}
}
