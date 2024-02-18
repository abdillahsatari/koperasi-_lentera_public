<?php
defined('BASEPATH') OR exit ('No direct access allowed');

class Pinjaman extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->session->userdata('member_authStatus') != AuthStatus::AUTHORIZED) {
			redirect('member/login');
		}
	}

	public function index() {
		$pageType	= urldecode($this->uri->segment(3));
		$memberId	= $this->session->userdata("member_id");
		$content	= '_memberLayouts/pinjaman/index';

		switch ($pageType){
			case 'summary':
				$dataPinjamanMember	= $this->PinjamanModel->getMemberPinjamanSummary($memberId);
				$memberPinjamanList	= $this->PinjamanModel->getMemberPinjamanList($memberId, NULL);
				$data				= array('session' 						=> SessionType::MEMBER,
											'pageType'						=> "Pinjaman Saya",
											'csrfName'						=> $this->security->get_csrf_token_name(),
											'csrfToken'						=> $this->security->get_csrf_hash(),
											'dataAllPinjaman'				=> current($dataPinjamanMember)->countAllPinjaman,
											'dataPaidPinjaman'				=> current($dataPinjamanMember)->countPaidPinjaman,
											'dataUnpaidPinjaman'			=> current($dataPinjamanMember)->countUnpaidPinjaman,
											"dataMemberPinjamanList"		=> $memberPinjamanList,
											'dataMemberPinjamanContent'		=> $this->PinjamanModel->getMemberPinjamanContent($memberId),
											'content'						=> $content);

				$this->load->view('_memberLayouts/wrapper', $data);
				break;
			case 'pembayaran':
				$dataPinjamanMember	= $this->PinjamanModel->getMemberPinjamanSummary($memberId);
				$memberPinjamanList	= $this->PinjamanModel->getMemberPinjamanList($memberId, MemberPinjamanStatus::PINJAMAN_UNPAID);
				$data				= array('session' 						=> SessionType::MEMBER,
											'pageType'						=> "Pembayaran Pinjaman",
											'csrfName'						=> $this->security->get_csrf_token_name(),
											'csrfToken'						=> $this->security->get_csrf_hash(),
											'dataPaidPinjaman'				=> current($dataPinjamanMember)->countPaidPinjaman,
											'dataUnpaidPinjaman'			=> current($dataPinjamanMember)->countUnpaidPinjaman,
											'dataPinjamanList'				=> $memberPinjamanList,
											'content'						=> $content);

				$this->load->view('_memberLayouts/wrapper', $data);
				break;
			case 'laporan':
				$dataPinjamanMember	= $this->PinjamanModel->getMemberPinjamanSummary($memberId);
				$memberPinjamanList	= $this->PinjamanModel->getMemberPinjamanList($memberId, MemberPinjamanStatus::PINJAMAN_PAID);
				$data				= array('session' 						=> SessionType::MEMBER,
											'pageType'						=> "Laporan Pinjaman Dibayar",
											'csrfName'						=> $this->security->get_csrf_token_name(),
											'csrfToken'						=> $this->security->get_csrf_hash(),
											'dataPaidPinjaman'				=> current($dataPinjamanMember)->countPaidPinjaman,
											'dataPinjamanList'				=> $memberPinjamanList,
											'content'						=> $content);
				$this->load->view('_memberLayouts/wrapper', $data);
				break;
			default:
				$this->load->view('errors/html/error_404');
				break;

		}
	}

	public function create(){
		$content			= '_memberLayouts/pinjaman/create';
		$memberId			= $this->session->userdata('member_id');
		$memberStatus 		= $this->MemberModel->getMemberActiveStatus($memberId);
		$where 				= array("pinjaman_content_status" => true);
		$programPinjaman	= $this->CrudModel->gw("lentera_pinjaman_content", $where);

		$data				= array('session' 				=> SessionType::MEMBER,
									'memberIsKyc'			=> $memberStatus["member_is_kyc"],
									'memberIsSimwa'			=> $memberStatus["member_is_simwa"],
									'memberIsSimpo'			=> $memberStatus["member_is_simpo"],
									'memberIsActivated'		=> $memberStatus["member_is_activated"],
									'csrfName'				=> $this->security->get_csrf_token_name(),
									'csrfToken'				=> $this->security->get_csrf_hash(),
									'dataProgramPinjaman'	=> $programPinjaman,
									'content'				=> $content);

		$this->load->view('_memberLayouts/wrapper', $data);
	}

	public function save(){
		$input = $this->input->post(NULL, TRUE);
		$this->form_validation->set_rules('member_transaction_code', 'Kode Transaksi', 'trim|required');
		$this->form_validation->set_rules('pinjaman_member_ammount', 'Nominal Pinjaman', 'trim|required');
		$this->form_validation->set_rules('pinjaman_member_description', 'Deskripsi Pinjaman', 'trim|required');

		if ($this->form_validation->run() == false) {
			generalAllert("Pengajuan Pinjaman Gagal!". "<br/>", validation_errors(), AllertType::FAILED);
			$this->create();
		} else {

			$memberId		= $this->session->userdata("member_id");
			$pinjamanCode	= "PJ-".generatePaymentTransactionCode($memberId);

			$params		= array("member_id"						=> $memberId,
								"pinjaman_content_id"			=> $input["lentera_pinjaman_content_id"],
								"pinjaman_content_detail"		=> $input["pinjaman_content_detail_id"],
								"pinjaman_member_ammount"		=> $input["pinjaman_member_ammount"],
								"pinjaman_member_tenor"			=> $input["pinjaman_member_tenor"],
								"pinjaman_transaction_code"		=> $pinjamanCode,
								"pinjaman_member_description"	=> $input["pinjaman_member_description"],
								"member_transaction_code"		=> $input["member_transaction_code"],
								"created_by"					=> $memberId,
								"created_at"					=> date('Y-m-d H:i:s'));

			$setPinjamanMember = $this->PinjamanModel->setPinjamanMember($params);

			switch ($setPinjamanMember["status"]){
				case "success":
					generalAllert("Pengajuan Berhasil!", $setPinjamanMember["data"], AllertType::SUCCESS);
					break;
				default:
					generalAllert("Pengajuan Gagal!", $setPinjamanMember["data"], AllertType::FAILED);
			}
		}

		redirect('member/pinjaman/summary');
	}

	public function update(){
		$input 		= $this->input->post(NULL, TRUE);

		// initiate rquired information
		$params	= array("id"						=> $input["pinjaman_detail_id"],
						"member_id" 				=> $input["member_id"],
						"pinjaman_id"				=> $input["pinjaman_id"],
						"pinjaman_tenor_number"		=> $input["pinjaman_tenor_number"],
						"pinjaman_bunga_ammount"	=> $input["pinjaman_bunga_ammount"]);


		// initiate previous Paid Status
		$tenorNumber		= $input["pinjaman_tenor_number"] == 1 ? $input["pinjaman_tenor_number"] : $input["pinjaman_tenor_number"] - 1;
		$where 				= array("pinjaman_id" 			=> $input["pinjaman_id"],
									"member_id"				=> $input["member_id"],
									"pinjaman_tenor_number"	=> $tenorNumber);
		$dataPinjamanDetail = $this->CrudModel->gw("member_pinjaman_detail", $where);
		$previousPaidStatus = current($dataPinjamanDetail)->pinjaman_payment_status;

		// initiate Member Balance
		$dataBalance		= current($this->FinanceModel->memberFinance($input["member_id"]))->totalWalletBalance;

		// do actions
		if ($dataBalance < intval($input["pinjaman_bunga_ammount"])){
			$updatedStatus = array("status" => "failed",
									"data"	=> "Saldo Dompet Anda Tidak Cukup. Mohon lakukan penambahan saldo terlebih dahulu");
		} else {
			switch ($input["pinjaman_tenor_number"]){
				case 1:
					$updatedStatus = $this->PinjamanModel->setBayarPinjamanDetail($params);
					break;
				default:
					if ($previousPaidStatus == MemberPinjamanStatus::PINJAMAN_PAID){
						$updatedStatus = $this->PinjamanModel->setBayarPinjamanDetail($params);
					} else {
						$updatedStatus = array("status" => "failed",
												"data"	=> "Harap Membayar Tagihan Sebelumnya");
					}
					break;
			}
		}

		// getting result
		switch ($updatedStatus["status"]){
			case "success":
				generalAllert("Pembayaran Berhasil!", $updatedStatus["data"], AllertType::SUCCESS);
				break;
			default:
				generalAllert("Pembayaran Gagal!", $updatedStatus["data"], AllertType::FAILED);
		}

		redirect('member/pinjaman/pembayaran');
	}
}
