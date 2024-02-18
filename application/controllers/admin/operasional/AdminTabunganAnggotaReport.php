<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminTabunganAnggotaReport extends CI_Controller{

	public function __construct() {
		parent::__construct();
		if ($this->session->userdata('admin_authStatus') != AuthStatus::AUTHORIZED) {
			redirect('pengurus/login');
		}
	}

	public function index(){
		$pageType	= $this->input->get('type');
		$content	= '_adminLayouts/tabunganAnggotaReport/index';

		switch ($pageType){
			case strtolower(MemberTabunganReportType::TABUNGAN_REPORT_MUTASI_TABUNGAN):
				$dataReportTabungan = $this->TabunganModel->getAllTabunganMemberReportList(MemberTabunganReportType::TABUNGAN_REPORT_MUTASI_TABUNGAN, NULL);
				$data				= array('session' 				=> SessionType::ADMIN,
					'dataTotalTabungan'		=> current($this->FinanceModel->memberFinance())->totalTabunganBalance,
					'dataTotalImbalJasa'	=> current($this->FinanceModel->memberFinance())->countTabunganImbalJasa,
											'pageType'				=> MemberTabunganReportType::TABUNGAN_REPORT_MUTASI_TABUNGAN,
											'dataReportTabungan'	=> $dataReportTabungan,
											'content'				=> $content);

				$this->load->view('_adminLayouts/wrapper', $data);
				break;
			case strtolower(MemberTabunganReportType::TABUNGAN_REPORT_IMBAL_JASA):
				$dataReportTabungan 	= $this->TabunganModel->getAllTabunganMemberReportList(MemberTabunganReportType::TABUNGAN_REPORT_IMBAL_JASA, NULL);
				$data			= array('session' 				=> SessionType::ADMIN,
					'dataTotalTabungan'		=> current($this->FinanceModel->memberFinance())->totalTabunganBalance,
					'dataTotalImbalJasa'	=> current($this->FinanceModel->memberFinance())->countTabunganImbalJasa,
										'pageType'				=> MemberTabunganReportType::TABUNGAN_REPORT_IMBAL_JASA,
										'dataReportTabungan'	=> $dataReportTabungan,
										'content'				=> $content);

				$this->load->view('_adminLayouts/wrapper', $data);
				break;
			default:
				$this->load->view('errors/html/error_404');
				break;
		}
	}
	
}
