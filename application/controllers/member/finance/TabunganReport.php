<?php
defined('BASEPATH') OR exit ('No direct access allowed');

class TabunganReport extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->session->userdata('member_authStatus') != AuthStatus::AUTHORIZED) {
			redirect('member/login');
		}
	}

	public function index() {
		$pageType	= $this->input->get('type');
		$content	= '_memberLayouts/tabunganReport/index';
		$memberId	= $this->session->userdata("member_id");

		switch ($pageType){
			case strtolower(MemberTabunganReportType::TABUNGAN_REPORT_MUTASI_TABUNGAN):
				$dataReportTabungan = $this->TabunganModel->getAllTabunganMemberReportList(MemberTabunganReportType::TABUNGAN_REPORT_MUTASI_TABUNGAN, $memberId);
				$data				= array('session' 				=> SessionType::MEMBER,
											'dataTotalTabungan'		=> current($this->FinanceModel->memberFinance($memberId))->totalTabunganBalance,
											'dataTotalImbalJasa'	=> current($this->FinanceModel->memberFinance($memberId))->countTabunganImbalJasa,
											'pageType'				=> MemberTabunganReportType::TABUNGAN_REPORT_MUTASI_TABUNGAN,
											'dataReportTabungan'	=> $dataReportTabungan,
											'content'				=> $content);

				$this->load->view('_memberLayouts/wrapper', $data);
				break;
			case strtolower(MemberTabunganReportType::TABUNGAN_REPORT_IMBAL_JASA):
				$dataReportTabungan = $this->TabunganModel->getAllTabunganMemberReportList(MemberTabunganReportType::TABUNGAN_REPORT_IMBAL_JASA, $memberId);
				$data				= array('session' 				=> SessionType::MEMBER,
											'dataTotalTabungan'		=> current($this->FinanceModel->memberFinance($memberId))->totalTabunganBalance,
											'dataTotalImbalJasa'	=> current($this->FinanceModel->memberFinance($memberId))->countTabunganImbalJasa,
											'pageType'				=> MemberTabunganReportType::TABUNGAN_REPORT_IMBAL_JASA,
											'dataReportTabungan'	=> $dataReportTabungan,
											'content'				=> $content);

				$this->load->view('_memberLayouts/wrapper', $data);
				break;
			default:
				$this->load->view('errors/html/error_404');
				break;
		}
	}
}
