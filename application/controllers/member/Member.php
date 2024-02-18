<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if($this->session->userdata('member_authStatus') != AuthStatus::AUTHORIZED){
			redirect('member/login');
		}
	}

	public function index() {
		$input 			= $this->input->post(NULL, TRUE);
		$content 		= '_memberLayouts/dashboard/index';
		$memberId		= $this->session->userdata('member_id');
		$whereId		= array('id'	=> $memberId);
		$getMember 		= $this->CrudModel->gw('member', $whereId);
		$dataFinance	= $this->FinanceModel->memberFinance($memberId);
		$wherememberId	= array('member_id'	=> $memberId);


		if (isset($input["start_date"]) || isset($input["end_date"])){
			$date = strtotime('01/01/2000');
			$defaultStartDate 	= date('Y-m-d',$date);
			$defaultEndDate		= date('Y-m-d');

			$startDate	= $input["start_date"] ?: $defaultStartDate;
			$endDate	= $input["end_date"] ?: $defaultEndDate;

			$query = 'SELECT * FROM transaction_log 
					WHERE created_at between "'.$startDate.'"
					AND "'.$endDate.'" ORDER BY created_at DESC';

			$dataMutasi 	= $this->CrudModel->q($query);
		} else {
			$dataMutasi		= $this->CrudModel->gwo("transaction_log", $wherememberId, 'created_at DESC');
		}

		$data 			= array('session'     	=> SessionType::MEMBER,
								'csrfName'		=> $this->security->get_csrf_token_name(),
								'csrfToken'		=> $this->security->get_csrf_hash(),
								'dataMember'	=> $getMember,
								'dataMutasi'	=> $dataMutasi,
								'dataBalance'	=> current($dataFinance)->totalWalletBalance,
								'dataTabungan'	=> current($dataFinance)->totalTabunganBalance,
								'dataLpoint'	=> current($dataFinance)->totalLpointBalance,
								'dataSimpanan'	=> current($dataFinance)->countSimpanan,
								'dataImbalJasa'	=> current($dataFinance)->totalImbaJasa,
								'dataHutang'	=> current($dataFinance)->countPinjamanDetailLeft,
								'content'		=> $content);

		$this->load->view('_memberLayouts/wrapper', $data);
	}

}
