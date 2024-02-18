<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminTabunganAnggotaTf extends CI_Controller{

	public function __construct() {
		parent::__construct();
		if ($this->session->userdata('admin_authStatus') != AuthStatus::AUTHORIZED) {
			redirect('pengurus/login');
		}
	}

	public function index(){
		$pageType	= $this->input->get('type');
		$content	= '_adminLayouts/tabunganAnggotaTf/index';

		switch ($pageType){
			case strtolower(MemberTabunganTfApproval::TABUNGAN_TF_NEW):
				$dataTabungan 	= $this->TabunganModel->getAllTabunganTfMemberList(MemberTabunganTfApproval::TABUNGAN_TF_NEW, NULL);
				$data			= array('session' 				=> SessionType::ADMIN,
										'pageType'				=> MemberTabunganTfApproval::TABUNGAN_TF_NEW,
										'dataTabungan'			=> $dataTabungan,
										'csrfName'				=> $this->security->get_csrf_token_name(),
										'csrfToken'				=> $this->security->get_csrf_hash(),
										'content'				=> $content);

				$this->load->view('_adminLayouts/wrapper', $data);
				break;
			case strtolower(MemberTabunganTfApproval::TABUNGAN_TF_PROCESSED):
				$dataTabungan 	= $this->TabunganModel->getAllTabunganTfMemberList(MemberTabunganTfApproval::TABUNGAN_TF_PROCESSED, NULL);
				$data			= array('session' 				=> SessionType::ADMIN,
										'pageType'				=> MemberTabunganTfApproval::TABUNGAN_TF_PROCESSED,
										'dataTabungan'			=> $dataTabungan,
										'csrfName'				=> $this->security->get_csrf_token_name(),
										'csrfToken'				=> $this->security->get_csrf_hash(),
										'content'				=> $content);

				$this->load->view('_adminLayouts/wrapper', $data);
				break;
			case strtolower(MemberTabunganTfApproval::TABUNGAN_TF_REJECTED):
				$dataTabungan 	= $this->TabunganModel->getAllTabunganTfMemberList(MemberTabunganTfApproval::TABUNGAN_TF_REJECTED, NULL);
				$data			= array('session' 				=> SessionType::ADMIN,
										'pageType'				=> MemberTabunganTfApproval::TABUNGAN_TF_REJECTED,
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
		$this->form_validation->set_rules('tabungan_tf_approval', 'Status Transfer Tabungan', 'trim|required');

		if ($this->form_validation->run() == false || $input["tabungan_tf_approval"] == MemberTabunganTfApproval::TABUNGAN_TF_NEW) {
			switch ($input["tabungan_tf_approval"]){
				case MemberTabunganTfApproval::TABUNGAN_TF_NEW:
					generalAllert("Woops!", "Anda belum merubah status transfer tabungan",AllertType::INFO);
					break;
				default:
					generalAllert("Perubahan Status Gagal!". "<br/>", validation_errors(), AllertType::FAILED);
					break;
			}
			$dataredirect = "admin/tabungan/transfer-tabungan?type=" . strtolower(MemberTabunganTfApproval::TABUNGAN_TF_NEW);
			redirect($dataredirect);
		} else {

			$params	= array("tabungan_tf_id"			=> $input["tabungan_tf_id"],
							"member_id"					=> $input["member_id"],
							"tabungan_tf_tr_code"		=> $input["tabungan_tf_tr_code"],
							"tabungan_tf_member_bank_id"=> $input["tabungan_tf_member_bank_id"],
							"tabungan_tf_approval" 		=> $input["tabungan_tf_approval"],
							"tabungan_tf_approved_date"	=> date('Y-m-d H:i:s'),
							"tabungan_tf_approved_by"	=> $this->session->userdata("admin_id"),
							"updated_at"				=> date('Y-m-d H:i:s'));

			$updatedStatus	= $this->TabunganModel->setTabunganMemberTfApproval($params);

			switch ($updatedStatus["status"]){
				case "success":
					generalAllert("Perubahan Status Berhasil!", $updatedStatus["data"], AllertType::SUCCESS);
					break;
				default:
					generalAllert("Perubahan Status Gagal!", $updatedStatus["data"], AllertType::FAILED);
			}


			$dataredirect = "admin/tabungan/transfer-tabungan?type=" . strtolower($input["tabungan_tf_approval"]);
			redirect($dataredirect);
		}
	}

}
