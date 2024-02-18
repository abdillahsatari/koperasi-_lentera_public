<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct() {
		parent::__construct();
	
		if($this->session->userdata('admin_authStatus') != AuthStatus::AUTHORIZED){
			redirect('pengurus/login');
		}
	}

	public function index() {

		$whereMemberIsActivated		= array("member_is_activated" => TRUE);
		$dataCountActivedMember		= $this->CrudModel->cw("member", $whereMemberIsActivated);
		$dataCountRegisteredMember	= $this->CrudModel->cw("member", "member_is_activated IS NULL");
		$dataFinance				= $this->FinanceModel->memberFinance();

		$content 	= '_adminLayouts/dashboard/index';
		$data 		= array('session'						=> SessionType::ADMIN,
							'dataCountActiveMember'			=> $dataCountActivedMember,
							'dataCountRegisteredMember'		=> $dataCountRegisteredMember,
							'dataTabungan'					=> current($dataFinance)->totalTabunganBalance,
							'dataPinjaman'					=> current($dataFinance)->countPinjaman,
							'dataSimpanan'					=> current($dataFinance)->totalSimpananAnggota,
							'dataDeposito'					=> current($dataFinance)->totalDeposito,
							'dataBalance'					=> current($dataFinance)->totalWalletBalance,
							'dataActivity'					=> $this->CrudModel->gao("action_log", "created_at DESC"),
							'csrfName'						=> $this->security->get_csrf_token_name(),
							'csrfToken'						=> $this->security->get_csrf_hash(),
							'content'						=> $content );

		$this->load->view('_adminLayouts/wrapper', $data);
	}
}
